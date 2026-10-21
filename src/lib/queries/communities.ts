import "server-only";
import { paginate, query, queryOne, type Paginated, type Params } from "@/lib/db";

// Read queries for the public community pages (CommunityFrontController).

export type CommunityCard = { id: number; name: string; slug: string; image: string | null; city: string | null };

/** CommunityFrontController@index — 12 per page, optional ?search= on name / city. */
export function getCommunitiesPage(search: string | undefined, page: number): Promise<Paginated<CommunityCard>> {
  const where = ["is_active = 1"];
  const params: Params = [];
  if (search !== undefined && search !== "" && search !== "0") {
    where.push("(name LIKE ? OR city LIKE ?)");
    params.push(`%${search}%`, `%${search}%`);
  }
  const from = `FROM communities WHERE ${where.join(" AND ")}`;
  return paginate<CommunityCard>(
    `SELECT id, name, slug, image, city ${from} ORDER BY sort_order ASC, name ASC`,
    `SELECT COUNT(*) AS total ${from}`,
    params,
    page,
    12,
  );
}

export type CommunityDetail = {
  id: number;
  name: string;
  slug: string;
  image: string | null;
  banner_image: string | null;
  city: string | null;
  description: string | null;
  /** DECIMAL as text, printed as-is in the map URL like PHP did. */
  latitude: string | null;
  longitude: string | null;
};

export function getCommunityBySlug(slug: string): Promise<CommunityDetail | null> {
  return queryOne<CommunityDetail>(
    `SELECT id, name, slug, image, banner_image, city, description,
        CAST(latitude AS CHAR) AS latitude, CAST(longitude AS CHAR) AS longitude
      FROM communities WHERE slug = ? AND is_active = 1 LIMIT 1`,
    [slug],
  );
}

/**
 * The "Properties in this community" section of the Blade page is inside an HTML comment, but Blade
 * still rendered it, and its icon <img> tags (no loading attribute) used up the LazyLoadImages
 * middleware's 3 eager slots. Returns how many such icons the old page printed, so the
 * "Other communities" images get the same loading attributes.
 */
export async function getCommentedPropertyIconCount(communityId: number): Promise<number> {
  const rows = await query<{ area_sqft: string | null; bedrooms: number | null; bathrooms: number | null }>(
    `SELECT CAST(area_sqft AS CHAR) AS area_sqft, bedrooms, bathrooms FROM properties
      WHERE status = 'active' AND community_id = ? ORDER BY created_at DESC LIMIT 8`,
    [communityId],
  );
  return rows.reduce(
    (n, r) =>
      n +
      (r.area_sqft !== null && r.area_sqft !== "" && r.area_sqft !== "0" ? 1 : 0) +
      (r.bedrooms !== null ? 1 : 0) +
      (r.bathrooms !== null ? 1 : 0),
    0,
  );
}

export type OtherCommunity ={ id: number; name: string; slug: string; image: string | null };

/** "Explore Other Communities" — 8 active communities except the current one. */
export function getOtherCommunities(id: number): Promise<OtherCommunity[]> {
  return query<OtherCommunity>(
    "SELECT id, name, slug, image FROM communities WHERE is_active = 1 AND id != ? ORDER BY sort_order ASC LIMIT 8",
    [id],
  );
}
