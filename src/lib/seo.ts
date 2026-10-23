import "server-only";
import type { Metadata } from "next";
import { getSettings, pick } from "@/lib/settings";
import { asset, siteUrl } from "@/lib/url";

type PageSeo = {
  /** <title>. Falls back to the site_name setting. */
  title?: string | null;
  /** Meta description. */
  description?: string | null;
  /** Path of this page, e.g. "/buy/emaar-beachfront" — used for canonical and og:url. */
  path: string;
  /** Share image: absolute URL or an "uploads/..." / "assets/..." path. */
  ogImage?: string | null;
  /** Keep empty listings out of Google. */
  noindex?: boolean;
};

const DEFAULT_DESCRIPTION = "Quadrant Properties Dubai — Luxury Real Estate in Dubai";

/**
 * Meta description Google shows in full: tags stripped, the fallback added when the text is
 * very short, and cut at a word boundary to at most 158 characters.
 */
export function fitDescription(text: string | null | undefined, fallback: string): string {
  const entities: Record<string, string> = { amp: "&", quot: '"', apos: "'", nbsp: " ", rsquo: "’", lsquo: "‘" };
  const clean = (s: string) =>
    s
      .replace(/<[^>]*>/g, " ")
      .replace(/&(#\d+|[a-z]+);/gi, (m, e: string) =>
        e[0] === "#" ? String.fromCharCode(Number(e.slice(1))) : (entities[e.toLowerCase()] ?? m),
      )
      .replace(/\s+/g, " ")
      .trim();
  let d = clean(text ?? "");
  if (d.length < 70) d = d ? `${d.replace(/[.\s]*$/, ".")} ${clean(fallback)}` : clean(fallback);
  if (d.length <= 158) return d;
  const cut = d.slice(0, 157);
  return cut.slice(0, cut.lastIndexOf(" ")).replace(/[\s,;:.—-]+$/, "") + "…";
}

/**
 * Builds the same head tags the Blade layout produced: title, description,
 * Open Graph, Twitter card and canonical.
 */
export async function pageMetadata(seo: PageSeo): Promise<Metadata> {
  const settings = await getSettings();
  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai");
  const title = seo.title || siteName || "Quadrant Properties Dubai";
  const url = siteUrl(seo.path);

  const defaultImage = siteUrl("/public/assets/images/og-image.jpg");
  const custom = seo.ogImage ? (/^https?:/.test(seo.ogImage) ? seo.ogImage : siteUrl(asset(seo.ogImage))) : null;
  // Spaces encoded for WhatsApp previews.
  const image = (custom ?? defaultImage).replace(/ /g, "%20");
  const isDefault = !custom;

  return {
    title: { absolute: title },
    description: seo.description || DEFAULT_DESCRIPTION,
    alternates: { canonical: url },
    robots: seo.noindex ? { index: false, follow: true } : undefined,
    openGraph: {
      type: "website",
      url,
      title,
      description: seo.description ?? "",
      images: [isDefault ? { url: image, width: 1200, height: 630 } : { url: image }],
      siteName: pick(settings, "site_name", "Quadrant Properties") ?? undefined,
    },
    twitter: {
      card: "summary_large_image",
      title,
      description: seo.description ?? "",
      images: [image],
    },
  };
}
