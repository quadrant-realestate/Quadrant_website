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

// Port of InvestmentController (store / update / delete / toggleFeatured / toggleStatus).

function rules(ignoreId?: number): Record<string, string> {
  return {
    title: `required|string|max:255|unique:investments,title${ignoreId ? `,${ignoreId}` : ""}`,
    community_id: "nullable|exists:communities,id",
    property_id: "nullable|exists:properties,id",
    price: "nullable|numeric",
    roi_percentage: "nullable|numeric",
    gross_yield: "nullable|numeric",
    net_yield: "nullable|numeric",
    annual_rental: "nullable|numeric",
  };
}

function fields(form: FormData) {
  const title = str(form, "title") ?? "";
  return {
    title,
    slug: slugify(title),
    property_id: str(form, "property_id"),
    community_id: str(form, "community_id"),
    description: str(form, "description"),
    investment_type: str(form, "investment_type"),
    price: str(form, "price"),
    price_currency: str(form, "price_currency") ?? "AED",
    roi_percentage: str(form, "roi_percentage"),
    gross_yield: str(form, "gross_yield"),
    net_yield: str(form, "net_yield"),
    annual_rental: str(form, "annual_rental"),
    is_featured: form.has("is_featured") ? 1 : 0,
    is_active: form.has("is_active") ? 1 : 0,
  };
}

export async function createInvestment(prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, rules());
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const file = form.get("main_image");
  const mainImage = isFile(file) ? await saveUploadAs(file, "investments") : null;

  await insert("investments", { ...fields(form), main_image: mainImage });

  await flash("success", "Investment listing added successfully.");
  redirect("/admin/investments");
}

export async function updateInvestment(id: number, prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, rules(id));
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const investment = await queryOne<{ main_image: string | null }>("SELECT main_image FROM investments WHERE id = ?", [id]);
  if (!investment) redirect("/admin/projects-record-missing?m=investment");

  let mainImage = investment.main_image;
  const file = form.get("main_image");
  if (isFile(file)) {
    await deleteUpload(mainImage);
    mainImage = await saveUploadAs(file, "investments");
  }

  await update("investments", id, { ...fields(form), main_image: mainImage });

  await flash("success", "Investment listing updated successfully.");
  redirect("/admin/investments");
}

export async function deleteInvestment(id: number): Promise<void> {
  await requireAdmin();
  const investment = await queryOne<{ main_image: string | null }>("SELECT main_image FROM investments WHERE id = ?", [id]);
  if (!investment) {
    await flash("error", "Investment not found.");
    redirect("/admin/investments");
  }

  await deleteUpload(investment.main_image);
  await execute("DELETE FROM investments WHERE id = ?", [id]);

  await flash("success", "Investment deleted successfully.");
  redirect("/admin/investments");
}

export async function toggleInvestmentFeatured(id: number): Promise<void> {
  await requireAdmin();
  const investment = await queryOne<{ is_featured: number }>("SELECT is_featured FROM investments WHERE id = ?", [id]);
  if (investment) await update("investments", id, { is_featured: investment.is_featured ? 0 : 1 });
  const back = await backUrl("/admin/investments");
  await flash("success", "Featured status updated.");
  redirect(back);
}

export async function toggleInvestmentStatus(id: number): Promise<void> {
  await requireAdmin();
  const investment = await queryOne<{ is_active: number }>("SELECT is_active FROM investments WHERE id = ?", [id]);
  if (investment) await update("investments", id, { is_active: investment.is_active ? 0 : 1 });
  const back = await backUrl("/admin/investments");
  await flash("success", "Status updated successfully.");
  redirect(back);
}
