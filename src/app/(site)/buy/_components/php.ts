// Small PHP/Laravel behaviour helpers shared by the /buy and /communities pages.

/** PHP truthiness for DB/request values: null, "", "0", 0 and false are falsy ("0.00" is truthy). */
export function truthy<T>(v: T | null | undefined | "" | 0 | false): v is T {
  return v !== null && v !== undefined && v !== "" && v !== "0" && v !== 0 && v !== false;
}

/** PHP trim() — strips spaces, tabs, newlines, NUL and vertical tabs only. */
export function phpTrim(s: string): string {
  return s.replace(/^[ \t\n\r\0\x0B]+|[ \t\n\r\0\x0B]+$/g, "");
}

/** array_filter(array_map('trim', explode("\n", $text))) — keys are kept, so [0] may be missing. */
export function lines(text: string): { index: number; line: string }[] {
  return text
    .split("\n")
    .map((line, index) => ({ index, line: phpTrim(line) }))
    .filter((l) => truthy(l.line));
}

export type SearchParams = Record<string, string | string[] | undefined>;

/**
 * Query string values as Laravel's request() sees them: trimmed (TrimStrings) and
 * empty strings turned into null (ConvertEmptyStringsToNull). Array values are ignored.
 */
export function requestInput(sp: SearchParams): Record<string, string> {
  const out: Record<string, string> = {};
  for (const [key, value] of Object.entries(sp)) {
    if (typeof value !== "string") continue;
    const v = phpTrim(value);
    if (v !== "") out[key] = v;
  }
  return out;
}

/** Laravel's ?page= handling: anything that isn't a positive integer is page 1. */
export function pageNumber(value: string | undefined): number {
  const n = Number(value);
  return Number.isInteger(n) && n >= 1 ? n : 1;
}

/** "/public/<path>" exactly as `URL::to('') . '/public/' . $path` (empty path → "/public/"). */
export function publicPath(path: string | null | undefined): string {
  return `/public/${path ?? ""}`;
}
