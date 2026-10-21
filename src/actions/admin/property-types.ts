"use server";

import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { insert, queryOne, remove, update } from "@/lib/db";
import { flash } from "@/lib/flash";
import { bool, str } from "@/lib/form";
import { slugify } from "@/lib/format";
import { failed, isInteger, type AdminFormState } from "@/lib/admin/form-state";

// PropertyTypeController — store / update / delete / toggleStatus

async function validate(form: FormData, ignoreId?: number): Promise<Record<string, string>> {
  const errors: Record<string, string> = {};
  const name = str(form, "name");
  if (name === null) errors.name = "The name field is required.";
  else if (name.length > 80) errors.name = "The name field must not be greater than 80 characters.";
  else {
    const taken = await queryOne(
      `SELECT id FROM property_types WHERE name = ?${ignoreId ? " AND id <> ?" : ""} LIMIT 1`,
      ignoreId ? [name, ignoreId] : [name],
    );
    if (taken) errors.name = "The name has already been taken.";
  }
  const sortOrder = str(form, "sort_order");
  if (sortOrder !== null && !isInteger(sortOrder)) errors.sort_order = "The sort order field must be an integer.";
  return errors;
}

function fields(form: FormData) {
  const name = str(form, "name") ?? "";
  return {
    name,
    slug: slugify(name),
    is_active: bool(form, "is_active"),
    sort_order: str(form, "sort_order") ?? 0,
  };
}

export async function storePropertyType(prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form);
  if (Object.keys(errors).length > 0) return failed(prev, form, errors);

  // Laravel's query builder insert() — no timestamps
  await insert("property_types", fields(form), false);

  await flash("success", "Property type added successfully.");
  redirect("/admin/property-types");
}

export async function updatePropertyType(id: number, prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, id);
  if (Object.keys(errors).length > 0) return failed(prev, form, errors);

  await update("property_types", id, fields(form), false);

  await flash("success", "Property type updated successfully.");
  redirect("/admin/property-types");
}

export async function deletePropertyType(id: number): Promise<void> {
  await requireAdmin();
  // Check if any property is using this type
  const row = await queryOne<{ n: number }>("SELECT COUNT(*) AS n FROM properties WHERE property_type_id = ?", [id]);
  const inUse = Number(row?.n ?? 0);
  if (inUse > 0) {
    await flash("error", `Cannot delete. ${inUse} properties are using this type.`);
    redirect("/admin/property-types");
  }

  await remove("property_types", id);

  await flash("success", "Property type deleted successfully.");
  redirect("/admin/property-types");
}

export async function togglePropertyTypeStatus(id: number): Promise<void> {
  await requireAdmin();
  const type = await queryOne<{ is_active: number }>("SELECT is_active FROM property_types WHERE id = ? LIMIT 1", [id]);
  if (!type) {
    await flash("error", "Property type not found.");
    redirect("/admin/property-types");
  }

  await update("property_types", id, { is_active: type.is_active ? 0 : 1 }, false);

  await flash("success", "Status updated successfully.");
  redirect("/admin/property-types");
}
