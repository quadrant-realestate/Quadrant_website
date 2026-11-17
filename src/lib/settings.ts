import "server-only";
import { cache } from "react";
import { query } from "@/lib/db";

export type Settings = Record<string, string | null>;

/**
 * Contact numbers managed in code so they always display correctly, regardless of the
 * (possibly stale) values stored in the `settings` table. These win over the DB rows.
 */
const CONTACT_OVERRIDES: Settings = {
  contact_phone: "+971 52 768 2266", // main phone line
  whatsapp_number: "+971 52 141 3010", // Faisal Farooqui
};
// Office landline, shown under the office address (separate from the main phone above).
export const OFFICE_LANDLINE = "04 572 6027";

/** All rows of the `settings` table as key → value, loaded once per request. */
export const getSettings = cache(async (): Promise<Settings> => {
  try {
    const rows = await query<{ key: string; value: string | null }>("SELECT `key`, `value` FROM settings");
    return { ...Object.fromEntries(rows.map((r) => [r.key, r.value])), ...CONTACT_OVERRIDES };
  } catch {
    return { ...CONTACT_OVERRIDES };
  }
});

/** Same as Laravel's setting($key, $default) helper: falls back only when the key does not exist. */
export async function setting(key: string, fallback: string | null = null): Promise<string | null> {
  const settings = await getSettings();
  return key in settings ? settings[key] : fallback;
}

/** Synchronous lookup on an already loaded settings object. */
export function pick(settings: Settings, key: string, fallback: string | null = null): string | null {
  return key in settings ? settings[key] : fallback;
}
