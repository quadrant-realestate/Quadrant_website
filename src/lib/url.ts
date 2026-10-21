/** Absolute base URL of the site, without a trailing slash. */
export function siteUrl(path = ""): string {
  const base = (process.env.SITE_URL ?? "http://localhost:3000").replace(/\/+$/, "");
  return path ? `${base}${path.startsWith("/") ? "" : "/"}${path}` : base;
}

/**
 * URL of a file that the Laravel site served from /public — static assets ("assets/img/x.svg")
 * and uploaded files stored in the database ("uploads/developments/x.jpg").
 * Kept under /public/... so every existing URL keeps working.
 */
export function asset(path: string | null | undefined): string {
  if (!path) return "";
  if (/^(https?:)?\/\//.test(path) || path.startsWith("data:")) return path;
  return `/public/${path.replace(/^\/+/, "").replace(/^public\//, "")}`;
}

/** Absolute version of asset(), for Open Graph tags and JSON-LD. */
export function assetAbsolute(path: string | null | undefined): string {
  const url = asset(path);
  return url.startsWith("/") ? siteUrl(url) : url;
}
