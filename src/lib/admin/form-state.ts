import "server-only";
import { headers } from "next/headers";

// Helpers shared by the admin actions for dashboard / profile / properties / property types /
// amenities / inquiries. They reproduce what Laravel did implicitly ($errors, old(), redirect()->back()).

/** State returned to a form through useActionState: field errors + the submitted values (old()). */
export type AdminFormState = {
  errors?: Record<string, string>;
  /** Submitted values, keyed by field name ("amenities[]" style keys are stored without the brackets). */
  values?: Record<string, string | string[]>;
  /** A session('error') style message shown as an alert. */
  error?: string;
  /** Increments on every failed submission so the form remounts with the old() values. */
  n?: number;
};

/** All non-file values of a submitted form (Laravel's old input). */
export function formValues(form: FormData): Record<string, string | string[]> {
  const values: Record<string, string | string[]> = {};
  for (const [rawKey, value] of form.entries()) {
    if (typeof value !== "string" || rawKey.startsWith("$ACTION")) continue;
    if (rawKey.endsWith("[]")) {
      const key = rawKey.slice(0, -2);
      const current = values[key];
      values[key] = Array.isArray(current) ? [...current, value] : [value];
    } else {
      values[rawKey] = value;
    }
  }
  return values;
}

/** The failed-validation state for a form submission. */
export function failed(prev: AdminFormState, form: FormData, errors: Record<string, string>, error?: string): AdminFormState {
  return { errors, error, values: formValues(form), n: (prev.n ?? 0) + 1 };
}

/** redirect()->back(): the page the form was submitted from (same-origin path only), or a fallback. */
export async function backUrl(fallback: string): Promise<string> {
  const referer = (await headers()).get("referer");
  if (!referer) return fallback;
  try {
    const url = new URL(referer);
    return url.pathname.startsWith("/admin") ? `${url.pathname}${url.search}` : fallback;
  } catch {
    return fallback;
  }
}

/** PHP is_numeric() (Laravel's "numeric" rule). */
export function isNumeric(value: string): boolean {
  return /^\s*[+-]?(\d+(\.\d*)?|\.\d+)([eE][+-]?\d+)?\s*$/.test(value);
}

/** FILTER_VALIDATE_INT (Laravel's "integer" rule). */
export function isInteger(value: string): boolean {
  return /^\s*[+-]?\d+\s*$/.test(value);
}

/** Laravel's "email" rule (basic RFC check). */
export function isEmail(value: string): boolean {
  return /^[^\s@]+@[^\s@]+$/.test(value) && !value.includes("..");
}

/** Laravel's attribute name in messages: "property_type_id" → "property type id". */
export function attr(field: string): string {
  return field.replace(/_/g, " ");
}
