"use server";

import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { execute, insert, queryOne, update } from "@/lib/db";
import { flash } from "@/lib/flash";
import { str } from "@/lib/form";
import { slugify } from "@/lib/format";
import { failed, type AdminFormState } from "@/lib/admin/form-state";
import { saveUploadAs, validate } from "@/lib/admin/projects-validate";
import { deleteUpload, isFile } from "@/lib/uploads";

// Port of CommunityController (store / update / delete / toggleStatus / toggleFeatured).
// Note: like the controller, the "slug" input is ignored — the slug is always Str::slug(name).

function rules(ignoreId?: number): Record<string, string> {
  return {
    name: `required|string|max:100|unique:communities,name${ignoreId ? `,${ignoreId}` : ""}`,
    city: "required|string|max:100",
    description: "nullable|string",
    latitude: "nullable|numeric",
    longitude: "nullable|numeric",
    meta_title: "nullable|string|max:200",
    meta_desc: "nullable|string|max:300",
  };
}

function fields(form: FormData) {
  const name = str(form, "name") ?? "";
  return {
    name,
    slug: slugify(name),
    description: str(form, "description"),
    city: str(form, "city") ?? "Dubai",
    latitude: str(form, "latitude"),
    longitude: str(form, "longitude"),
    is_featured: form.has("is_featured") ? 1 : 0,
    is_active: form.has("is_active") ? 1 : 0,
    sort_order: str(form, "sort_order") ?? 0,
    meta_title: str(form, "meta_title"),
    meta_desc: str(form, "meta_desc"),
  };
}

type Files = { image: string | null; banner_image: string | null };

async function handleFiles(form: FormData, current: Files | null): Promise<Files> {
  const result: Files = { image: current?.image ?? null, banner_image: current?.banner_image ?? null };
  const image = form.get("image");
  if (isFile(image)) {
    if (current) await deleteUpload(current.image);
    result.image = await saveUploadAs(image, "communities");
  }
  const banner = form.get("banner_image");
  if (isFile(banner)) {
    if (current) await deleteUpload(current.banner_image);
    result.banner_image = await saveUploadAs(banner, "communities", "banner_");
  }
  return result;
}

export async function createCommunity(prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, rules());
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const files = await handleFiles(form, null);
  await insert("communities", { ...fields(form), ...files });

  await flash("success", "Community added successfully.");
  redirect("/admin/communities");
}

export async function updateCommunity(id: number, prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  await requireAdmin();
  const errors = await validate(form, rules(id));
  if (Object.keys(errors).length) return failed(prev, form, errors);

  const community = await queryOne<Files>("SELECT image, banner_image FROM communities WHERE id = ?", [id]);
  if (!community) redirect("/admin/projects-record-missing?m=community");

  const files = await handleFiles(form, community);
  await update("communities", id, { ...fields(form), ...files });

  await flash("success", "Community updated successfully.");
  redirect("/admin/communities");
}

export async function deleteCommunity(id: number): Promise<void> {
  await requireAdmin();
  const inUse = await queryOne<{ n: number }>("SELECT COUNT(*) AS n FROM properties WHERE community_id = ?", [id]);
  const count = Number(inUse?.n ?? 0);
  if (count > 0) {
    await flash("error", `Cannot delete. ${count} properties are linked to this community.`);
    redirect("/admin/communities");
  }

  const community = await queryOne<Files>("SELECT image, banner_image FROM communities WHERE id = ?", [id]);
  if (community) {
    await deleteUpload(community.image);
    await deleteUpload(community.banner_image);
  }
  await execute("DELETE FROM communities WHERE id = ?", [id]);

  await flash("success", "Community deleted successfully.");
  redirect("/admin/communities");
}

export async function toggleCommunityStatus(id: number): Promise<void> {
  await requireAdmin();
  const community = await queryOne<{ is_active: number }>("SELECT is_active FROM communities WHERE id = ?", [id]);
  if (!community) {
    await flash("error", "Community not found.");
    redirect("/admin/communities");
  }
  await update("communities", id, { is_active: community.is_active ? 0 : 1 });
  await flash("success", "Status updated successfully.");
  redirect("/admin/communities");
}

export async function toggleCommunityFeatured(id: number): Promise<void> {
  await requireAdmin();
  const community = await queryOne<{ is_featured: number }>("SELECT is_featured FROM communities WHERE id = ?", [id]);
  if (!community) {
    await flash("error", "Community not found.");
    redirect("/admin/communities");
  }
  await update("communities", id, { is_featured: community.is_featured ? 0 : 1 });
  await flash("success", "Featured status updated successfully.");
  redirect("/admin/communities");
}
