import "server-only";
import { query } from "@/lib/db";

// Read query for the admin settings page (SettingController::index).

export type SettingRow = { id: number; key: string; group: string | null; type: string | null; label: string | null; value: string | null };

export async function settingsForGroup(group: string): Promise<SettingRow[]> {
  return query<SettingRow>("SELECT id, `key`, `group`, type, label, value FROM settings WHERE `group` = ? ORDER BY id", [group]);
}
