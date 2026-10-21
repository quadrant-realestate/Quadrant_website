import "server-only";
import { cache } from "react";
import { query } from "@/lib/db";

export type Settings = Record<string, string | null>;

/** All rows of the `settings` table as key → value, loaded once per request. */
export const getSettings = cache(async (): Promise<Settings> => {
  try {
    const rows = await query<{ key: string; value: string | null }>("SELECT `key`, `value` FROM settings");
    return Object.fromEntries(rows.map((r) => [r.key, r.value]));
  } catch {
    return {};
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
