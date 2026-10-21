"use server";

import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { execute, insert, queryOne, update } from "@/lib/db";
import { flash } from "@/lib/flash";
import { str } from "@/lib/form";
import { failed, type AdminFormState } from "@/lib/admin/form-state";
import { saveUploadAs, validate } from "@/lib/admin/projects-validate";
import { deleteUpload, isFile } from "@/lib/uploads";

// Port of RecognitionController (store / update / delete / toggleStatus).
// The controller wrote no created_at / updated_at for this table, so timestamps are off here too.

const RULES = {
  media_name: "required|string|max:150",
  url: "nullable|url|max:255",
  sort_order: "nullable|integer",
};

function fields(form: FormData) {
  return {
    media_name: str(form, "media_name") ?? "",
    url: str(form, "url"),
    is_active: form.has("is_active") ? 1 : 0,
    sort_order: str(form, "sort_order") ?? 0,
  };
}

export async function createRecognition(prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, RULES);
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const file = form.get("logo");
  const logo = isFile(file) ? await saveUploadAs(file, "recognitions", "logo_") : null;

  await insert("recognitions", { ...fields(form), logo }, false);

  await flash("success", "Recognition added successfully.");
  redirect("/admin/recognitions");
}

export async function updateRecognition(id: number, prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, RULES);
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const recognition = await queryOne<{ logo: string | null }>("SELECT logo FROM recognitions WHERE id = ?", [id]);
  if (!recognition) redirect("/admin/projects-record-missing?m=recognition");

  let logo = recognition.logo;
  const file = form.get("logo");
  if (isFile(file)) {
    await deleteUpload(logo);
    logo = await saveUploadAs(file, "recognitions", "logo_");
  }

  await update("recognitions", id, { ...fields(form), logo }, false);

  await flash("success", "Recognition updated successfully.");
  redirect("/admin/recognitions");
}

export async function deleteRecognition(id: number): Promise<void> {
  await requireAdmin();
  const recognition = await queryOne<{ logo: string | null }>("SELECT logo FROM recognitions WHERE id = ?", [id]);
  if (!recognition) {
    await flash("error", "Recognition not found.");
    redirect("/admin/recognitions");
  }

  await deleteUpload(recognition.logo);
  await execute("DELETE FROM recognitions WHERE id = ?", [id]);

  await flash("success", "Recognition deleted successfully.");
  redirect("/admin/recognitions");
}

export async function toggleRecognitionStatus(id: number): Promise<void> {
  await requireAdmin();
  const recognition = await queryOne<{ is_active: number }>("SELECT is_active FROM recognitions WHERE id = ?", [id]);
  if (!recognition) {
    await flash("error", "Recognition not found.");
    redirect("/admin/recognitions");
  }
  await update("recognitions", id, { is_active: recognition.is_active ? 0 : 1 }, false);
  await flash("success", "Status updated successfully.");
  redirect("/admin/recognitions");
}
