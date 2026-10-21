import "server-only";
import { query } from "@/lib/db";

// HomeController@index

export type FeaturedDevelopment = {
  id: number;
  title: string | null;
  slug: string | null;
  main_image: string | null;
  status: string | null;
  price_from: number | null;
  price_currency: string | null;
  developer_name: string | null;
  handover_date: string | null;
  bedroom_range: string | null;
  community_name: string | null;
};

/** Section 3: Featured Off-Plan Projects (centrepiece). */
export function getFeaturedDevelopments(): Promise<FeaturedDevelopment[]> {
  return query<FeaturedDevelopment>(
    `SELECT developments.id, developments.title, developments.slug, developments.main_image,
            developments.status, developments.price_from, developments.price_currency,
            developments.developer_name, developments.handover_date, developments.bedroom_range,
            communities.name AS community_name
       FROM developments
       LEFT JOIN communities ON developments.community_id = communities.id
      WHERE developments.is_active = ? AND developments.is_featured = ?
      ORDER BY developments.created_at DESC
      LIMIT 8`,
    [1, 1],
  );
}

/** The 7 priority communities shown on the home page, in this fixed order. */
export const PRIORITY_COMMUNITIES: [slug: string, name: string][] = [
  ["downtown-dubai", "Downtown Dubai"],
  ["dubai-marina", "Dubai Marina"],
  ["palm-jebel-ali", "Palm Jebel Ali"],
  ["dubai-creek-harbour", "Dubai Creek Harbour"],
  ["dubai-hills-estate", "Dubai Hills Estate"],
  ["jvc", "JVC"],
  ["dubai-south", "Dubai South"],
];

/** Section 5: slug + image of the priority communities that exist in the database. */
export function getPriorityCommunityRecords(): Promise<{ slug: string; image: string | null }[]> {
  const slugs = PRIORITY_COMMUNITIES.map(([slug]) => slug);
  return query<{ slug: string; image: string | null }>(
    `SELECT slug, image FROM communities WHERE slug IN (${slugs.map(() => "?").join(", ")})`,
    slugs,
  );
}

// Note: HomeController also loaded $developerPartners, but the only section using it is
// commented out (<!-- -->) in index.blade.php, so it is not queried here.
