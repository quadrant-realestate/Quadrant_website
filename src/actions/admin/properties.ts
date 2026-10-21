"use server";

import { randomInt } from "node:crypto";
import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { execute, insert, query, queryOne, remove, update } from "@/lib/db";
import { flash } from "@/lib/flash";
import { bool, list, str } from "@/lib/form";
import { slugify } from "@/lib/format";
import { attr, backUrl, failed, isInteger, isNumeric, type AdminFormState } from "@/lib/admin/form-state";
import { deleteUpload, isFile, saveUpload } from "@/lib/uploads";

// PropertyController — store / update / delete / deleteGalleryImage / toggleFeatured / toggleStatus

const LISTING_TYPES = ["sale", "rent", "off_plan", "international", "private"];

/** File with a different original name, so saveUpload() produces "<time>_fp_<name>" etc. like the controller. */
const renamed = (file: File, name: string) => new File([file], name, { type: file.type });

/** Str::random(6) uppercased. */
function randomRef(): string {
  const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  let s = "";
  for (let i = 0; i < 6; i++) s += chars[randomInt(chars.length)];
  return s.toUpperCase();
}

async function validate(form: FormData): Promise<Record<string, string>> {
  const errors: Record<string, string> = {};
  const title = str(form, "title");
  if (title === null) errors.title = "The title field is required.";
  else if (title.length > 255) errors.title = "The title field must not be greater than 255 characters.";

  const listingType = str(form, "listing_type");
  if (listingType === null) errors.listing_type = "The listing type field is required.";
  else if (!LISTING_TYPES.includes(listingType)) errors.listing_type = "The selected listing type is invalid.";

  for (const [field, table] of [
    ["property_type_id", "property_types"],
    ["community_id", "communities"],
  ] as const) {
    const v = str(form, field);
    if (v !== null && !(await queryOne(`SELECT id FROM ${table} WHERE id = ? LIMIT 1`, [v]))) {
      errors[field] = `The selected ${attr(field)} is invalid.`;
    }
  }

  for (const field of ["price", "area_sqft"]) {
    const v = str(form, field);
    if (v !== null && !isNumeric(v)) errors[field] = `The ${attr(field)} field must be a number.`;
  }
  for (const field of ["bedrooms", "bathrooms"]) {
    const v = str(form, field);
    if (v !== null && !isInteger(v)) errors[field] = `The ${attr(field)} field must be an integer.`;
  }
  return errors;
}

/** The columns both store() and update() write. */
function fields(form: FormData) {
  const title = str(form, "title") ?? "";
  return {
    title,
    slug: slugify(title),
    listing_type: str(form, "listing_type"),
    property_type_id: str(form, "property_type_id"),
    community_id: str(form, "community_id"),
    address: str(form, "address"),
    city: str(form, "city") ?? "Dubai",
    country: str(form, "country") ?? "UAE",
    latitude: str(form, "latitude"),
    longitude: str(form, "longitude"),
    google_maps_link: str(form, "google_maps_link"),
    bedrooms: str(form, "bedrooms"),
    bathrooms: str(form, "bathrooms"),
    area_sqft: str(form, "area_sqft"),
    plot_area_sqft: str(form, "plot_area_sqft"),
    floor_number: str(form, "floor_number"),
    total_floors: str(form, "total_floors"),
    parking_spaces: str(form, "parking_spaces"),
    furnished: str(form, "furnished"),
    price: str(form, "price"),
    price_currency: str(form, "price_currency") ?? "AED",
    price_on_request: bool(form, "price_on_request"),
    service_charge: str(form, "service_charge"),
    short_description: str(form, "short_description"),
    description: str(form, "description"),
    video_url: str(form, "video_url"),
    virtual_tour_url: str(form, "virtual_tour_url"),
    is_featured: bool(form, "is_featured"),
    is_exclusive: bool(form, "is_exclusive"),
    is_new: bool(form, "is_new"),
    status: str(form, "status") ?? "active",
    meta_title: str(form, "meta_title"),
    meta_desc: str(form, "meta_desc"),
    meta_keywords: str(form, "meta_keywords"),
  };
}

const galleryFiles = (form: FormData) => form.getAll("gallery[]").filter(isFile);

async function insertAmenities(propertyId: number, form: FormData) {
  for (const amenityId of list(form, "amenities")) {
    await insert("property_amenities", { property_id: propertyId, amenity_id: amenityId }, false);
  }
}

export async function storeProperty(prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form);
  if (Object.keys(errors).length > 0) return failed(prev, form, errors);

  // Generate reference number
  const referenceNo = `QDR-${randomRef()}`;

  const main = form.get("main_image");
  const mainImage = isFile(main) ? await saveUpload(main, "properties") : null;

  const fp = form.get("floor_plan_image");
  const floorPlan = isFile(fp) ? await saveUpload(renamed(fp, `fp_${fp.name}`), "properties") : null;

  const pdf = form.get("brochure_pdf");
  const brochure = isFile(pdf) ? await saveUpload(pdf, "properties/brochures") : null;

  const now = new Date();
  const propertyId = await insert(
    "properties",
    {
      ...fields(form),
      reference_no: referenceNo,
      main_image: mainImage,
      floor_plan_image: floorPlan,
      brochure_pdf: brochure,
      published_at: now,
      created_at: now,
      updated_at: now,
    },
    false,
  );

  // Gallery images
  const gallery = galleryFiles(form);
  for (let index = 0; index < gallery.length; index++) {
    const file = gallery[index];
    const path = await saveUpload(renamed(file, `${index}_${file.name}`), "properties/gallery");
    await insert(
      "property_images",
      { property_id: propertyId, image_path: path, is_primary: index === 0 ? 1 : 0, sort_order: index, created_at: new Date() },
      false,
    );
  }

  await insertAmenities(propertyId, form);

  await flash("success", `Property added successfully. Reference: ${referenceNo}`);
  redirect("/admin/properties");
}

export async function updateProperty(id: number, prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form);
  if (Object.keys(errors).length > 0) return failed(prev, form, errors);

  const property = await queryOne<{ main_image: string | null; floor_plan_image: string | null; brochure_pdf: string | null }>(
    "SELECT main_image, floor_plan_image, brochure_pdf FROM properties WHERE id = ? LIMIT 1",
    [id],
  );
  if (!property) {
    await flash("error", "Property not found.");
    redirect("/admin/properties");
  }

  let mainImage = property.main_image;
  const main = form.get("main_image");
  if (isFile(main)) {
    await deleteUpload(mainImage);
    mainImage = await saveUpload(main, "properties");
  }

  let floorPlan = property.floor_plan_image;
  const fp = form.get("floor_plan_image");
  if (isFile(fp)) {
    await deleteUpload(floorPlan);
    floorPlan = await saveUpload(renamed(fp, `fp_${fp.name}`), "properties");
  }

  let brochure = property.brochure_pdf;
  const pdf = form.get("brochure_pdf");
  if (isFile(pdf)) {
    await deleteUpload(brochure);
    brochure = await saveUpload(pdf, "properties/brochures");
  }

  await update("properties", id, { ...fields(form), main_image: mainImage, floor_plan_image: floorPlan, brochure_pdf: brochure });

  // New gallery images are appended after the existing ones
  const gallery = galleryFiles(form);
  if (gallery.length > 0) {
    const row = await queryOne<{ m: number | null }>("SELECT MAX(sort_order) AS m FROM property_images WHERE property_id = ?", [id]);
    const lastOrder = Number(row?.m ?? 0);
    for (let index = 0; index < gallery.length; index++) {
      const file = gallery[index];
      const path = await saveUpload(renamed(file, `${index}_${file.name}`), "properties/gallery");
      await insert(
        "property_images",
        { property_id: id, image_path: path, is_primary: 0, sort_order: lastOrder + index + 1, created_at: new Date() },
        false,
      );
    }
  }

  // Amenities — delete old, insert new
  await execute("DELETE FROM property_amenities WHERE property_id = ?", [id]);
  await insertAmenities(id, form);

  await flash("success", "Property updated successfully.");
  redirect("/admin/properties");
}

export async function deleteProperty(id: number): Promise<void> {
  await requireAdmin();
  const property = await queryOne<{ main_image: string | null }>("SELECT main_image FROM properties WHERE id = ? LIMIT 1", [id]);
  if (!property) {
    await flash("error", "Property not found.");
    redirect("/admin/properties");
  }

  await deleteUpload(property.main_image);
  const gallery = await query<{ image_path: string | null }>("SELECT image_path FROM property_images WHERE property_id = ?", [id]);
  for (const img of gallery) await deleteUpload(img.image_path);

  await execute("DELETE FROM property_images WHERE property_id = ?", [id]);
  await execute("DELETE FROM property_amenities WHERE property_id = ?", [id]);
  await remove("properties", id);

  await flash("success", "Property deleted successfully.");
  redirect("/admin/properties");
}

export async function deletePropertyGalleryImage(imageId: number): Promise<void> {
  await requireAdmin();
  const image = await queryOne<{ image_path: string | null; property_id: number | null }>(
    "SELECT image_path, property_id FROM property_images WHERE id = ? LIMIT 1",
    [imageId],
  );
  if (image) {
    await deleteUpload(image.image_path);
    await remove("property_images", imageId);
  }
  const fallback = image?.property_id ? `/admin/properties/${image.property_id}/edit` : "/admin/properties";
  const back = await backUrl(fallback);
  await flash("success", "Image deleted successfully.");
  redirect(back);
}

export async function togglePropertyFeatured(id: number): Promise<void> {
  await requireAdmin();
  const property = await queryOne<{ is_featured: number }>("SELECT is_featured FROM properties WHERE id = ? LIMIT 1", [id]);
  if (property) await update("properties", id, { is_featured: property.is_featured ? 0 : 1 });
  const back = await backUrl("/admin/properties");
  await flash("success", "Featured status updated.");
  redirect(back);
}

export async function togglePropertyStatus(id: number): Promise<void> {
  await requireAdmin();
  const property = await queryOne<{ status: string | null }>("SELECT status FROM properties WHERE id = ? LIMIT 1", [id]);
  if (property) await update("properties", id, { status: property.status === "active" ? "inactive" : "active" });
  const back = await backUrl("/admin/properties");
  await flash("success", "Status updated successfully.");
  redirect(back);
}
