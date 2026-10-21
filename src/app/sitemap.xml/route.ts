import { hasItems, activeSlugs } from "@/lib/queries/sitemap";
import { sanityQuery } from "@/lib/sanity";
import { siteUrl } from "@/lib/url";

// SitemapController@index — sitemap.xml for search engines
export const dynamic = "force-dynamic";

type Entry = { loc: string; lastmod: string | null; priority: string };
type Listing = Parameters<typeof hasItems>;

/** Laravel route() parameter encoding (rawurlencode, with "/" kept). */
const encodeSlug = (slug: string) =>
  encodeURIComponent(slug)
    .replace(/[!'()*]/g, (c) => `%${c.charCodeAt(0).toString(16).toUpperCase()}`)
    .replace(/%2F/g, "/");

/** htmlspecialchars($s, ENT_XML1) */
const escapeXml = (s: string) => s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

/** Carbon::parse($s)->toAtomString() for Sanity's UTC timestamps. */
function atom(value: string): string | null {
  const d = new Date(value);
  if (Number.isNaN(d.getTime())) return null;
  return d.toISOString().replace(/\.\d{3}Z$/, "+00:00");
}

export async function GET() {
  const urls: Entry[] = [];

  // Static pages (route name → path, priority)
  const statics: [name: string, path: string, priority: string][] = [
    ["home", "", "1.0"],
    ["developments.index", "/buy", "0.9"],
    ["properties.sale", "/properties/for-sale", "0.9"],
    ["properties.rent", "/properties/for-rent", "0.9"],
    ["properties.private", "/properties/private-office", "0.7"],
    ["properties.international", "/properties/international", "0.7"],
    ["investments.index", "/investments", "0.8"],
    ["branded-residences.index", "/branded-residences", "0.8"],
    ["communities.index", "/communities", "0.8"],
    ["blogs.index", "/blogs", "0.8"],
    ["sell", "/sell", "0.7"],
    ["about", "/about", "0.7"],
    ["services", "/services", "0.7"],
    ["giving", "/giving", "0.5"],
    ["contact", "/contact", "0.6"],
    ["privacy", "/privacy-policy", "0.2"],
    ["terms", "/terms", "0.2"],
    ["cookies", "/cookie-policy", "0.2"],
  ];
  // Listing pages that show "No results" when empty — Google treats those as
  // soft 404s, so they're left out until they have at least one item.
  const listings: Record<string, Listing> = {
    "properties.sale": ["properties", "listing_type", "sale"],
    "properties.rent": ["properties", "listing_type", "rent"],
    "properties.private": ["properties", "listing_type", "private"],
    "properties.international": ["properties", "listing_type", "international"],
    "investments.index": ["investments", null, null],
    "branded-residences.index": ["branded_residences", null, null],
    "developments.index": ["developments", null, null],
    "communities.index": ["communities", null, null],
  };

  for (const [name, path, priority] of statics) {
    if (listings[name] && !(await hasItems(...listings[name]))) continue;
    urls.push({ loc: siteUrl(path), lastmod: null, priority });
  }

  // Database-driven detail pages (same "active" filters as the front controllers)
  const sources: [table: Listing[0], column: string, value: string | number, prefix: string, priority: string][] = [
    ["properties", "status", "active", "/properties/", "0.8"],
    ["developments", "is_active", 1, "/buy/", "0.8"],
    ["investments", "is_active", 1, "/investments/", "0.7"],
    ["branded_residences", "is_active", 1, "/branded-residences/", "0.7"],
    ["communities", "is_active", 1, "/communities/", "0.7"],
  ];
  for (const [table, column, value, prefix, priority] of sources) {
    const rows = await activeSlugs(table, column, value);
    if (!rows) continue;
    for (const row of rows) {
      urls.push({ loc: siteUrl(prefix + encodeSlug(row.slug)), lastmod: row.lastmod, priority });
    }
  }

  // Blog posts (Sanity)
  const posts = await sanityQuery<{ slug: string; _updatedAt?: string }[]>(
    `*[_type == "post" && defined(slug.current) && publishedAt <= now()] | order(publishedAt desc){
                "slug": slug.current, _updatedAt
            }`,
    {},
    [],
  );
  for (const post of posts) {
    urls.push({
      loc: siteUrl(`/blogs/${encodeSlug(post.slug)}`),
      lastmod: post._updatedAt ? atom(post._updatedAt) : null,
      priority: "0.6",
    });
  }

  let xml = '<?xml version="1.0" encoding="UTF-8"?>\n';
  xml += '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';
  for (const url of urls) {
    xml += "  <url>\n";
    xml += `    <loc>${escapeXml(url.loc)}</loc>\n`;
    if (url.lastmod) xml += `    <lastmod>${url.lastmod}</lastmod>\n`;
    xml += `    <priority>${url.priority}</priority>\n`;
    xml += "  </url>\n";
  }
  xml += "</urlset>";

  return new Response(xml, {
    status: 200,
    headers: {
      "Content-Type": "application/xml; charset=UTF-8",
      "Cache-Control": "public, max-age=3600",
    },
  });
}
