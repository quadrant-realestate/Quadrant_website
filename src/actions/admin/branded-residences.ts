"use server";

import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { execute, insert, queryOne, update } from "@/lib/db";
import { flash } from "@/lib/flash";
import { str } from "@/lib/form";
import { slugify } from "@/lib/format";
import { backUrl, failed, type AdminFormState } from "@/lib/admin/form-state";
import { saveUploadAs, validate } from "@/lib/admin/projects-validate";
import { deleteUpload, isFile } from "@/lib/uploads";

// Port of BrandedResidenceController (store / update / delete / toggleFeatured / toggleStatus).

function rules(ignoreId?: number): Record<string, string> {
  return {
    brand_name: "required|string|max:150",
    title: `required|string|max:255|unique:branded_residences,title${ignoreId ? `,${ignoreId}` : ""}`,
    community_id: "nullable|exists:communities,id",
    property_id: "nullable|exists:properties,id",
    price_from: "nullable|numeric",
  };
}

function fields(form: FormData) {
  const title = str(form, "title") ?? "";
  return {
    brand_name: str(form, "brand_name") ?? "",
    property_id: str(form, "property_id"),
    title,
    slug: slugify(title),
    description: str(form, "description"),
    community_id: str(form, "community_id"),
    price_from: str(form, "price_from"),
    is_featured: form.has("is_featured") ? 1 : 0,
    is_active: form.has("is_active") ? 1 : 0,
  };
}

type Files = { main_image: string | null; brand_logo: string | null };

async function handleFiles(form: FormData, current: Files | null): Promise<Files> {
  const result: Files = { main_image: current?.main_image ?? null, brand_logo: current?.brand_logo ?? null };
  const main = form.get("main_image");
  if (isFile(main)) {
    if (current) await deleteUpload(current.main_image);
    result.main_image = await saveUploadAs(main, "branded_residences");
  }
  const logo = form.get("brand_logo");
  if (isFile(logo)) {
    if (current) await deleteUpload(current.brand_logo);
    result.brand_logo = await saveUploadAs(logo, "branded_residences/logos", "logo_");
  }
  return result;
}

export async function createBrandedResidence(prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, rules());
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const files = await handleFiles(form, null);
  await insert("branded_residences", { ...fields(form), ...files });

  await flash("success", "Branded residence added successfully.");
  redirect("/admin/branded-residences");
}

export async function updateBrandedResidence(id: number, prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, rules(id));
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const residence = await queryOne<Files>("SELECT main_image, brand_logo FROM branded_residences WHERE id = ?", [id]);
  if (!residence) redirect("/admin/projects-record-missing?m=branded-residence");

  const files = await handleFiles(form, residence);
  await update("branded_residences", id, { ...fields(form), ...files });

  await flash("success", "Branded residence updated successfully.");
  redirect("/admin/branded-residences");
}

export async function deleteBrandedResidence(id: number): Promise<void> {
  await requireAdmin();
  const residence = await queryOne<Files>("SELECT main_image, brand_logo FROM branded_residences WHERE id = ?", [id]);
  if (!residence) {
    await flash("error", "Branded residence not found.");
    redirect("/admin/branded-residences");
  }

  await deleteUpload(residence.main_image);
  await deleteUpload(residence.brand_logo);
  await execute("DELETE FROM branded_residences WHERE id = ?", [id]);

  await flash("success", "Branded residence deleted successfully.");
  redirect("/admin/branded-residences");
}

export async function toggleBrandedResidenceFeatured(id: number): Promise<void> {
  await requireAdmin();
  const residence = await queryOne<{ is_featured: number }>("SELECT is_featured FROM branded_residences WHERE id = ?", [id]);
  if (residence) await update("branded_residences", id, { is_featured: residence.is_featured ? 0 : 1 });
  const back = await backUrl("/admin/branded-residences");
  await flash("success", "Featured status updated.");
  redirect(back);
}

export async function toggleBrandedResidenceStatus(id: number): Promise<void> {
  await requireAdmin();
  const residence = await queryOne<{ is_active: number }>("SELECT is_active FROM branded_residences WHERE id = ?", [id]);
  if (residence) await update("branded_residences", id, { is_active: residence.is_active ? 0 : 1 });
  const back = await backUrl("/admin/branded-residences");
  await flash("success", "Status updated successfully.");
  redirect(back);
}
