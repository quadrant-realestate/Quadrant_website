// Small PHP-semantics helpers so the JSX behaves exactly like the Blade templates.

/**
 * PHP truthiness of a DECIMAL column. PDO returns decimals as strings ("0.0000"),
 * and a non-empty string other than "0" is truthy in PHP — so any non-null decimal,
 * including 0, counted as "set" in the Blade @if checks.
 */
export function decFilled(v: number | string | null | undefined): boolean {
  return v !== null && v !== undefined;
}

/** PHP truthiness of a string column ("" and "0" are falsy). */
export function strFilled(v: string | null | undefined): v is string {
  return v !== null && v !== undefined && v !== "" && v !== "0";
}

/** How PHP prints a DECIMAL(16,4) column: "40.0000". */
export function decString(v: number | string | null | undefined): string {
  if (v === null || v === undefined) return "";
  return Number(v).toFixed(4);
}

/** PHP loose comparison `request('x') == $value` for request strings vs ids / option values. */
export function looseEq(req: string | undefined, value: string | number): boolean {
  if (req === undefined) return false; // null == '1' / null == 5 → false
  const numeric = (s: string) => s.trim() !== "" && Number.isFinite(Number(s));
  const v = String(value);
  if (numeric(req) && numeric(v)) return Number(req) === Number(v);
  return req === v;
}

/** PHP urlencode(): like encodeURIComponent, but spaces → "+" and !'()* encoded too. */
export function phpUrlencode(value: string): string {
  return encodeURIComponent(value)
    .replace(/[!'()*~]/g, (c) => (c === "~" ? "%7E" : `%${c.charCodeAt(0).toString(16).toUpperCase()}`))
    .replace(/%20/g, "+");
}
