// Small PHP/Laravel equivalents shared by the investments, branded-residences and blogs pages.

export type SearchParams = Record<string, string | string[] | undefined>;

/**
 * request('key') after Laravel's TrimStrings + ConvertEmptyStringsToNull middleware:
 * trimmed string, or null when missing / empty.
 */
export function param(sp: SearchParams, key: string): string | null {
  const raw = sp[key];
  const value = Array.isArray(raw) ? raw[0] : raw;
  if (value === undefined) return null;
  const trimmed = value.trim();
  return trimmed === "" ? null : trimmed;
}

/** PHP truthiness: null, "", "0", 0 and false are falsy; "0.0000" is truthy. */
export function truthy(value: unknown): boolean {
  if (value === null || value === undefined || value === false) return false;
  if (typeof value === "number") return value !== 0;
  if (typeof value === "string") return value !== "" && value !== "0";
  return true;
}

/** PHP urlencode(): like encodeURIComponent, but spaces become "+" and !'()*~ are encoded too. */
export function urlencode(value: string): string {
  return encodeURIComponent(value)
    .replace(/[!'()*~]/g, (c) => `%${c.charCodeAt(0).toString(16).toUpperCase()}`)
    .replace(/%20/g, "+");
}

/** Laravel's paginator page resolution: positive integer, otherwise 1. */
export function pageNumber(sp: SearchParams): number {
  const value = param(sp, "page");
  return value && /^\d+$/.test(value) && Number(value) >= 1 ? Number(value) : 1;
}

/** Query string kept on pagination links (withQueryString()): trimmed, empty values dropped. */
export function queryString(sp: SearchParams): Record<string, string> {
  const out: Record<string, string> = {};
  for (const key of Object.keys(sp)) {
    const value = param(sp, key);
    if (value !== null) out[key] = value;
  }
  return out;
}
