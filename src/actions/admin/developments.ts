"use server";

import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { execute, insert, query, queryOne, update } from "@/lib/db";
import { flash } from "@/lib/flash";
import { str } from "@/lib/form";
import { slugify } from "@/lib/format";
import { backUrl, failed, type AdminFormState } from "@/lib/admin/form-state";
import { saveUploadAs, validate } from "@/lib/admin/projects-validate";
import { deleteUpload, isFile } from "@/lib/uploads";

// Port of DevelopmentController (store / update / delete / deleteGalleryImage / toggleFeatured / toggleStatus).

type DevelopmentFiles = {
  main_image: string | null;
  banner_image: string | null;
  developer_logo: string | null;
  brochure_pdf: string | null;
  rera_qr_image: string | null;
  og_image: string | null;
};

// field → [folder, file name prefix]
const FILES: Record<keyof DevelopmentFiles, [string, string]> = {
  main_image: ["developments", ""],
  banner_image: ["developments", "banner_"],
  developer_logo: ["developments/logos", "logo_"],
  brochure_pdf: ["developments/brochures", ""],
  rera_qr_image: ["developments/rera", "rera_"],
  og_image: ["developments/og", "og_"],
};

function rules(ignoreId?: number): Record<string, string> {
  return {
    title: `required|string|max:255|unique:developments,title${ignoreId ? `,${ignoreId}` : ""}`,
    developer_name: "nullable|string|max:150",
    community_id: "nullable|exists:communities,id",
    price_from: "nullable|numeric",
    handover_date: "nullable|date",
  };
}

/** Upload each file field; on update the old file is deleted first (same as the controller). */
async function handleFiles(form: FormData, current: DevelopmentFiles | null): Promise<DevelopmentFiles> {
  const result = { ...(current ?? {}) } as DevelopmentFiles;
  for (const key of Object.keys(FILES) as (keyof DevelopmentFiles)[]) {
    if (!current) result[key] = null;
    const file = form.get(key);
    if (isFile(file)) {
      if (current?.[key]) await deleteUpload(current[key]);
      const [folder, prefix] = FILES[key];
      result[key] = await saveUploadAs(file, folder, prefix);
    }
  }
  return result;
}

function fields(form: FormData) {
  const title = str(form, "title") ?? "";
  return {
    title: title.trim(),
    slug: slugify(title),
    developer_name: str(form, "developer_name"),
    community_id: str(form, "community_id"),
    description: str(form, "description"),
    short_description: str(form, "short_description"),
    property_types: str(form, "property_types"),
    total_units: str(form, "total_units"),
    floors: str(form, "floors"),
    price_from: str(form, "price_from"),
    price_currency: str(form, "price_currency") ?? "AED",
    payment_plan: str(form, "payment_plan"),
    down_payment_pct: str(form, "down_payment_pct"),
    handover_date: str(form, "handover_date"),
    completion_pct: str(form, "completion_pct"),
    video_url: str(form, "video_url"),
    status: str(form, "status") ?? "launched",
    is_featured: form.has("is_featured") ? 1 : 0,
    is_active: form.has("is_active") ? 1 : 0,
    meta_title: str(form, "meta_title"),
    meta_desc: str(form, "meta_desc"),
    rera_permit: str(form, "rera_permit"),
    quadrant_view: str(form, "quadrant_view"),
    bedroom_range: str(form, "bedroom_range"),
    size_from: str(form, "size_from"),
    size_to: str(form, "size_to"),
    nearby_landmarks: str(form, "nearby_landmarks"),
  };
}

/** Gallery images: time() . '_' . $index . '_' . name, sort_order = offset + index. */
async function saveGallery(form: FormData, developmentId: number, orderOffset: (index: number) => number): Promise<void> {
  const files = form.getAll("gallery[]").concat(form.getAll("gallery")).filter(isFile);
  for (const [index, file] of files.entries()) {
    const path = await saveUploadAs(file, "developments/gallery", `${index}_`);
    await insert(
      "development_images",
      { development_id: developmentId, image_path: path, sort_order: orderOffset(index) },
      false,
    );
  }
}

export async function createDevelopment(prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, rules());
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const files = await handleFiles(form, null);
  const id = await insert("developments", { ...fields(form), ...files });
  await saveGallery(form, id, (index) => index);

  await flash("success", "Development added successfully.");
  redirect("/admin/developments");
}

export async function updateDevelopment(id: number, prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, rules(id));
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const development = await queryOne<DevelopmentFiles>(
    "SELECT main_image, banner_image, developer_logo, brochure_pdf, rera_qr_image, og_image FROM developments WHERE id = ?",
    [id],
  );
  if (!development) redirect("/admin/projects-record-missing?m=development");

  const files = await handleFiles(form, development);
  await update("developments", id, { ...fields(form), ...files });

  const max = await queryOne<{ m: number | null }>("SELECT MAX(sort_order) AS m FROM development_images WHERE development_id = ?", [id]);
  const lastOrder = Number(max?.m ?? 0);
  await saveGallery(form, id, (index) => lastOrder + index + 1);

  await flash("success", "Development updated successfully.");
  redirect("/admin/developments");
}

export async function deleteDevelopment(id: number): Promise<void> {
  await requireAdmin();
  const development = await queryOne<DevelopmentFiles>(
    "SELECT main_image, banner_image, developer_logo, brochure_pdf, rera_qr_image, og_image FROM developments WHERE id = ?",
    [id],
  );
  if (!development) {
    await flash("error", "Development not found.");
    redirect("/admin/developments");
  }

  for (const key of Object.keys(FILES) as (keyof DevelopmentFiles)[]) await deleteUpload(development[key]);

  const gallery = await query<{ image_path: string | null }>("SELECT image_path FROM development_images WHERE development_id = ?", [id]);
  for (const img of gallery) await deleteUpload(img.image_path);

  await execute("DELETE FROM development_images WHERE development_id = ?", [id]);
  await execute("DELETE FROM developments WHERE id = ?", [id]);

  await flash("success", "Development deleted successfully.");
  redirect("/admin/developments");
}

export async function deleteDevelopmentGalleryImage(imageId: number): Promise<void> {
  await requireAdmin();
  const image = await queryOne<{ image_path: string | null }>("SELECT image_path FROM development_images WHERE id = ?", [imageId]);
  if (image) {
    await deleteUpload(image.image_path);
    await execute("DELETE FROM development_images WHERE id = ?", [imageId]);
  }
  const back = await backUrl("/admin/developments");
  await flash("success", "Image deleted successfully.");
  redirect(back);
}

export async function toggleDevelopmentFeatured(id: number): Promise<void> {
  await requireAdmin();
  const development = await queryOne<{ is_featured: number }>("SELECT is_featured FROM developments WHERE id = ?", [id]);
  if (development) await update("developments", id, { is_featured: development.is_featured ? 0 : 1 });
  const back = await backUrl("/admin/developments");
  await flash("success", "Featured status updated.");
  redirect(back);
}

export async function toggleDevelopmentStatus(id: number): Promise<void> {
  await requireAdmin();
  const development = await queryOne<{ is_active: number }>("SELECT is_active FROM developments WHERE id = ?", [id]);
  if (development) await update("developments", id, { is_active: development.is_active ? 0 : 1 });
  const back = await backUrl("/admin/developments");
  await flash("success", "Status updated successfully.");
  redirect(back);
}
