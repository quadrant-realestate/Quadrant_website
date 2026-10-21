"use server";

import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { insert, queryOne, remove, update } from "@/lib/db";
import { flash } from "@/lib/flash";
import { str } from "@/lib/form";
import { failed, type AdminFormState } from "@/lib/admin/form-state";

// AmenityController — store / update / delete

async function validate(form: FormData, ignoreId?: number): Promise<Record<string, string>> {
  const errors: Record<string, string> = {};
  const name = str(form, "name");
  if (name === null) errors.name = "The name field is required.";
  else if (name.length > 100) errors.name = "The name field must not be greater than 100 characters.";
  else {
    const taken = await queryOne(
      `SELECT id FROM amenities WHERE name = ?${ignoreId ? " AND id <> ?" : ""} LIMIT 1`,
      ignoreId ? [name, ignoreId] : [name],
    );
    if (taken) errors.name = "The name has already been taken.";
  }
  const category = str(form, "category");
  if (category === null) errors.category = "The category field is required.";
  else if (category.length > 80) errors.category = "The category field must not be greater than 80 characters.";
  return errors;
}

function fields(form: FormData) {
  return {
    name: str(form, "name") ?? "",
    // trim($request->icon) ?? null — an empty icon is stored as "" (trim(null) returns "")
    icon: str(form, "icon") ?? "",
    category: str(form, "category") ?? "",
    sort_order: str(form, "sort_order") ?? 0,
  };
}

export async function storeAmenity(prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form);
  if (Object.keys(errors).length > 0) return failed(prev, form, errors);

  // Laravel's query builder insert() — no timestamps
  await insert("amenities", fields(form), false);

  await flash("success", "Amenity added successfully.");
  redirect("/admin/amenities");
}

export async function updateAmenity(id: number, prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, id);
  if (Object.keys(errors).length > 0) return failed(prev, form, errors);

  await update("amenities", id, fields(form), false);

  await flash("success", "Amenity updated successfully.");
  redirect("/admin/amenities");
}

export async function deleteAmenity(id: number): Promise<void> {
  await requireAdmin();
  // Check if any property is using this amenity
  const row = await queryOne<{ n: number }>("SELECT COUNT(*) AS n FROM property_amenities WHERE amenity_id = ?", [id]);
  const inUse = Number(row?.n ?? 0);
  if (inUse > 0) {
    await flash("error", `Cannot delete. ${inUse} properties are using this amenity.`);
    redirect("/admin/amenities");
  }

  await remove("amenities", id);

  await flash("success", "Amenity deleted successfully.");
  redirect("/admin/amenities");
}
