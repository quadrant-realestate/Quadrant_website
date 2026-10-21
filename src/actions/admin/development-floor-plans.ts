"use server";

import { notFound, redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { execute, insert, queryOne, update } from "@/lib/db";
import { flash } from "@/lib/flash";
import { str } from "@/lib/form";
import { failed, type AdminFormState } from "@/lib/admin/form-state";
import { saveUploadAs, validate } from "@/lib/admin/projects-validate";
import { isFile } from "@/lib/uploads";

// Port of DevelopmentFloorPlanController (store / update / destroy / reorder).

const RULES = {
  unit_type: "required|string|max:100",
  size_sqft: "nullable|string|max:50",
};

export async function createFloorPlan(developmentId: number, prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, RULES);
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const image = form.get("image");
  const pdf = form.get("pdf_file");
  const imagePath = isFile(image) ? await saveUploadAs(image, "floor_plans") : null;
  const pdfPath = isFile(pdf) ? await saveUploadAs(pdf, "floor_plans/pdf") : null;

  const max = await queryOne<{ m: number | null }>(
    "SELECT MAX(sort_order) AS m FROM development_floor_plans WHERE development_id = ?",
    [developmentId],
  );

  await insert("development_floor_plans", {
    development_id: developmentId,
    unit_type: str(form, "unit_type"),
    size_sqft: str(form, "size_sqft"),
    image: imagePath,
    pdf_file: pdfPath,
    sort_order: Number(max?.m ?? 0) + 1,
  });

  await flash("success", "Floor plan added successfully.");
  redirect(`/admin/developments/${developmentId}/floor-plans`);
}

export async function updateFloorPlan(
  developmentId: number,
  id: number,
  prev: AdminFormState,
  form: FormData,
): Promise<AdminFormState> {
  await requireAdmin();
  const floorPlan = await queryOne<{ image: string | null; pdf_file: string | null }>(
    "SELECT image, pdf_file FROM development_floor_plans WHERE id = ?",
    [id],
  );
  if (!floorPlan) notFound();

  const errors = await validate(form, RULES);
  if (Object.keys(errors).length) return failed(prev, form, errors);

  // Like the controller, the previous files are not deleted when replaced.
  const image = form.get("image");
  const pdf = form.get("pdf_file");
  const imagePath = isFile(image) ? await saveUploadAs(image, "floor_plans") : floorPlan.image;
  const pdfPath = isFile(pdf) ? await saveUploadAs(pdf, "floor_plans/pdf") : floorPlan.pdf_file;

  await update("development_floor_plans", id, {
    unit_type: str(form, "unit_type"),
    size_sqft: str(form, "size_sqft"),
    image: imagePath,
    pdf_file: pdfPath,
  });

  await flash("success", "Floor plan updated successfully.");
  redirect(`/admin/developments/${developmentId}/floor-plans`);
}

export async function deleteFloorPlan(developmentId: number, id: number): Promise<void> {
  await requireAdmin();
  await execute("DELETE FROM development_floor_plans WHERE id = ?", [id]);
  await flash("success", "Floor plan deleted.");
  redirect(`/admin/developments/${developmentId}/floor-plans`);
}

/** reorder(): `order` is the list of floor plan ids in their new order (was a JSON POST). */
export async function reorderFloorPlans(developmentId: number, order: (number | string)[]): Promise<{ success: true }> {
  await requireAdmin();
  for (const [index, id] of (Array.isArray(order) ? order : []).entries()) {
    await execute("UPDATE development_floor_plans SET sort_order = ? WHERE id = ? AND development_id = ?", [
      index + 1,
      Number(id),
      developmentId,
    ]);
  }
  return { success: true };
}
