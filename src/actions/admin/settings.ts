"use server";

import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { execute, queryOne } from "@/lib/db";
import { flash } from "@/lib/flash";
import { str } from "@/lib/form";
import { saveUploadAs } from "@/lib/admin/projects-validate";
import { isFile } from "@/lib/uploads";

// Port of SettingController::update — saves settings[key] values. Image-type settings only change when a
// file is uploaded. Keys that don't exist in the table are ignored (the UPDATE matches no row), as before.
export async function updateSettings(form: FormData): Promise<void> {
  await requireAdmin();
  const group = str(form, "group") ?? "general";

  const keys: string[] = [];
  for (const name of form.keys()) {
    const m = /^settings\[(.+)\]$/.exec(name);
    if (m && !keys.includes(m[1])) keys.push(m[1]);
  }

  for (const key of keys) {
    const field = `settings[${key}]`;
    const setting = await queryOne<{ type: string | null }>("SELECT type FROM settings WHERE `key` = ? LIMIT 1", [key]);
    let value: string | null;

    if (setting && setting.type === "image") {
      const file = form.get(field);
      if (!isFile(file)) continue; // keep existing value
      value = await saveUploadAs(file, "settings");
    } else {
      const raw = form.get(field);
      value = typeof raw === "string" ? str(form, field) : null;
    }

    await execute("UPDATE settings SET value = ?, updated_at = ? WHERE `key` = ?", [value, new Date(), key]);
  }

  await flash("success", "Settings saved successfully.");
  redirect(`/admin/settings?group=${encodeURIComponent(group)}`);
}
