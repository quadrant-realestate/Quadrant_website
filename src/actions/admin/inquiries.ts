"use server";

import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { remove, update } from "@/lib/db";
import { flash } from "@/lib/flash";
import { backUrl } from "@/lib/admin/form-state";

// InquiryController — updateStatus / updateNotes / delete

const STATUSES = ["new", "contacted", "qualified", "closed"];

export async function updateInquiryStatus(id: number, form: FormData): Promise<void> {
  await requireAdmin();
  const back = await backUrl(`/admin/inquiries/${id}`);
  const status = form.get("status");
  // 'status' => 'required|in:new,contacted,qualified,closed' — the view shows no $errors, so just go back
  if (typeof status !== "string" || !STATUSES.includes(status)) redirect(back);

  await update("inquiries", id, { status });

  await flash("success", "Inquiry status updated successfully.");
  redirect(back);
}

export async function updateInquiryNotes(id: number, form: FormData): Promise<void> {
  await requireAdmin();
  const back = await backUrl(`/admin/inquiries/${id}`);
  const raw = form.get("notes");
  // ConvertEmptyStringsToNull + TrimStrings
  const notes = typeof raw === "string" && raw.trim() !== "" ? raw.trim() : null;

  await update("inquiries", id, { notes });

  await flash("success", "Notes saved successfully.");
  redirect(back);
}

export async function deleteInquiry(id: number): Promise<void> {
  await requireAdmin();
  await remove("inquiries", id);

  await flash("success", "Inquiry deleted successfully.");
  redirect("/admin/inquiries");
}
