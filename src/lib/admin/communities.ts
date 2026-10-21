import "server-only";
import { query, queryOne } from "@/lib/db";

// Read queries for the admin communities pages (CommunityController).

export type CommunityRow = {
  id: number;
  name: string | null;
  slug: string | null;
  description: string | null;
  image: string | null;
  banner_image: string | null;
  city: string | null;
  latitude: number | null;
  longitude: number | null;
  is_featured: number;
  is_active: number;
  sort_order: number | null;
  meta_title: string | null;
  meta_desc: string | null;
};

/** index(): all communities by sort_order, name — with the per-row properties count the Blade view queried. */
export async function listCommunities(): Promise<(CommunityRow & { property_count: number })[]> {
  return query<CommunityRow & { property_count: number }>(
    `SELECT communities.*, (SELECT COUNT(*) FROM properties WHERE properties.community_id = communities.id) AS property_count
       FROM communities
      ORDER BY sort_order ASC, name ASC`,
  );
}

export async function getCommunity(id: number): Promise<CommunityRow | null> {
  return queryOne<CommunityRow>("SELECT * FROM communities WHERE id = ?", [id]);
}
