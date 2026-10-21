// Small helpers shared by the developments / investments / branded residences / communities /
// recognitions / settings admin pages (pure functions, safe in client components).

import type { AdminFormState } from "./form-state";

/**
 * Laravel old($key, $default): after a failed submission the submitted value (missing keys, e.g. unchecked
 * checkboxes, fall back to the default), otherwise the default.
 */
export function old(state: AdminFormState, key: string, fallback: unknown = ""): string {
  if (state.values) {
    const v = state.values[key];
    if (v !== undefined) return Array.isArray(v) ? (v[0] ?? "") : v;
  }
  return fallback === null || fallback === undefined ? "" : String(fallback);
}

/** Checkbox `{{ old('x', $default) ? 'checked' : '' }}` */
export function oldChecked(state: AdminFormState, key: string, fallback: unknown): boolean {
  if (state.values && state.values[key] !== undefined) return true;
  return !!fallback && fallback !== "0";
}

/** DECIMAL(16,4) columns come back from Laravel as strings like "666000.0000" — keep that in form values. */
export function dec(value: number | string | null | undefined): string {
  if (value === null || value === undefined || value === "") return "";
  const n = Number(value);
  return Number.isFinite(n) ? n.toFixed(4) : String(value);
}

/** Carbon::parse($date)->format('M Y') for a "YYYY-MM-DD" (or other parseable) string. */
export function monthYear(value: string | Date | null | undefined): string {
  if (!value) return "";
  let date: Date;
  if (typeof value === "string" && /^\d{4}-\d{2}-\d{2}/.test(value)) {
    const [y, m, d] = value.slice(0, 10).split("-").map(Number);
    date = new Date(Date.UTC(y, m - 1, d));
  } else {
    date = value instanceof Date ? value : new Date(value);
  }
  if (Number.isNaN(date.getTime())) return String(value);
  // UTC getters: DB dates are read as UTC (Laravel's app timezone)
  return `${["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"][date.getUTCMonth()]} ${date.getUTCFullYear()}`;
}

/** JS port of the Blade pages' generateSlug() (communities). */
export function jsSlug(name: string): string {
  return name
    .trim()
    .toLowerCase()
    .replace(/&/g, "-and-")
    .replace(/[\s\W-]+/g, "-")
    .replace(/^-+|-+$/g, "");
}
