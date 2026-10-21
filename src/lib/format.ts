// Formatting helpers that reproduce the PHP functions the Blade templates used.

/** PHP number_format($n, $decimals) */
export function numberFormat(value: number | string | null | undefined, decimals = 0): string {
  const n = Number(value ?? 0);
  if (!Number.isFinite(n)) return "0";
  return n.toLocaleString("en-US", { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
}

/** Laravel Str::limit($text, $limit) — cuts and appends "..." */
export function strLimit(value: string | null | undefined, limit = 100, end = "..."): string {
  const s = value ?? "";
  return s.length <= limit ? s : s.slice(0, limit).trimEnd() + end;
}

/** PHP strip_tags() */
export function stripTags(html: string | null | undefined): string {
  return (html ?? "").replace(/<[^>]*>/g, "");
}

/** PHP ucfirst() */
export function ucfirst(value: string | null | undefined): string {
  const s = value ?? "";
  return s.charAt(0).toUpperCase() + s.slice(1);
}

/** PHP ucwords() */
export function ucwords(value: string | null | undefined): string {
  return (value ?? "").replace(/(^|[\s-])\S/g, (m) => m.toUpperCase());
}

/** Laravel Str::slug() */
export function slugify(value: string): string {
  return value
    .normalize("NFKD")
    .replace(/[̀-ͯ]/g, "")
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, "")
    .trim()
    .replace(/[\s_-]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

const MONTHS = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const MONTHS_LONG = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const DAYS = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

/**
 * Carbon/PHP date() style formatting for the tokens the templates use:
 * d j D M F m n Y y H i s A a g h
 * Dates are shown in UTC, the timezone the Laravel app used.
 */
export function formatDate(value: Date | string | null | undefined, format = "d M Y"): string {
  if (!value) return "";
  const date = value instanceof Date ? value : new Date(value);
  if (Number.isNaN(date.getTime())) return "";
  const pad = (n: number) => String(n).padStart(2, "0");
  const h = date.getUTCHours();
  const map: Record<string, string> = {
    d: pad(date.getUTCDate()),
    j: String(date.getUTCDate()),
    D: DAYS[date.getUTCDay()],
    M: MONTHS[date.getUTCMonth()],
    F: MONTHS_LONG[date.getUTCMonth()],
    m: pad(date.getUTCMonth() + 1),
    n: String(date.getUTCMonth() + 1),
    Y: String(date.getUTCFullYear()),
    y: String(date.getUTCFullYear()).slice(-2),
    H: pad(h),
    G: String(h),
    i: pad(date.getUTCMinutes()),
    s: pad(date.getUTCSeconds()),
    A: h < 12 ? "AM" : "PM",
    a: h < 12 ? "am" : "pm",
    g: String(h % 12 || 12),
    h: pad(h % 12 || 12),
  };
  return format.replace(/\\?([djDMFmnYyHGisAagh])/g, (m, t) => (m.startsWith("\\") ? t : map[t]));
}

/** Carbon diffForHumans() — "3 days ago" */
export function diffForHumans(value: Date | string | null | undefined): string {
  if (!value) return "";
  const date = value instanceof Date ? value : new Date(value);
  const seconds = Math.round((Date.now() - date.getTime()) / 1000);
  const units: [string, number][] = [
    ["year", 31536000],
    ["month", 2592000],
    ["week", 604800],
    ["day", 86400],
    ["hour", 3600],
    ["minute", 60],
    ["second", 1],
  ];
  const abs = Math.abs(seconds);
  for (const [name, size] of units) {
    if (abs >= size || name === "second") {
      const n = Math.max(1, Math.floor(abs / size));
      return `${n} ${name}${n === 1 ? "" : "s"} ${seconds >= 0 ? "ago" : "from now"}`;
    }
  }
  return "";
}

/** PHP nl2br(e($text)) — returns escaped HTML with <br> for newlines. */
export function nl2brEscaped(value: string | null | undefined): string {
  return escapeHtml(value ?? "").replace(/\r?\n/g, "<br />\n");
}

export function escapeHtml(value: string): string {
  return value
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}
