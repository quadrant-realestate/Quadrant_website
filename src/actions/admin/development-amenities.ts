"use server";

import { notFound, redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { execute, insert, queryOne } from "@/lib/db";
import { flash } from "@/lib/flash";
import { list } from "@/lib/form";

// Port of DevelopmentAmenityController::update — sync the checked amenities for a development.
export async function updateDevelopmentAmenities(developmentId: number, form: FormData): Promise<void> {
  await requireAdmin();
  const development = await queryOne("SELECT id FROM developments WHERE id = ?", [developmentId]);
  if (!development) notFound();

  const selectedIds = list(form, "amenity_ids");

  // Remove existing links, then re-insert selected ones
  await execute("DELETE FROM development_amenities WHERE development_id = ?", [developmentId]);
  for (const amenityId of selectedIds) {
    await insert("development_amenities", { development_id: developmentId, amenity_id: amenityId }, false);
  }

  await flash("success", "Amenities updated successfully.");
  redirect(`/admin/developments/${developmentId}/amenities`);
}
