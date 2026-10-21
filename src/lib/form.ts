// Helpers for reading admin form submissions (FormData) the way Laravel's $request did.

/** Trimmed string or null when empty (Laravel's ConvertEmptyStringsToNull). */
export function str(form: FormData, key: string): string | null {
  const v = form.get(key);
  if (typeof v !== "string") return null;
  const t = v.trim();
  return t === "" ? null : t;
}

/** Number or null. */
export function num(form: FormData, key: string): number | null {
  const v = str(form, key);
  if (v === null) return null;
  const n = Number(v.replace(/,/g, ""));
  return Number.isFinite(n) ? n : null;
}

/** Integer or null. */
export function int(form: FormData, key: string): number | null {
  const n = num(form, key);
  return n === null ? null : Math.trunc(n);
}

/** Checkbox → 1 / 0 ($request->has('x') ? 1 : 0). */
export function bool(form: FormData, key: string): 0 | 1 {
  const v = form.get(key);
  return v !== null && v !== "0" && v !== "false" ? 1 : 0;
}

/** All values for a repeated field like "amenities[]" or "amenities". */
export function list(form: FormData, key: string): string[] {
  return [...form.getAll(key), ...form.getAll(`${key}[]`)].filter((v): v is string => typeof v === "string" && v !== "");
}

/** Route param → positive integer id, or null. */
export function toId(value: string | string[] | undefined): number | null {
  const n = Number(Array.isArray(value) ? value[0] : value);
  return Number.isInteger(n) && n > 0 ? n : null;
}

/** Simple required-field validation that returns Laravel-style messages. */
export function requireFields(form: FormData, fields: Record<string, string>): Record<string, string> {
  const errors: Record<string, string> = {};
  for (const [key, label] of Object.entries(fields)) {
    if (str(form, key) === null) errors[key] = `The ${label} field is required.`;
  }
  return errors;
}
