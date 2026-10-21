import "server-only";
import { query, queryOne, type Params } from "@/lib/db";

// Read queries for the admin branded residences pages (BrandedResidenceController).

export type BrandedResidenceRow = {
  id: number;
  brand_name: string | null;
  brand_logo: string | null;
  property_id: number | null;
  title: string | null;
  slug: string | null;
  description: string | null;
  community_id: number | null;
  price_from: number | null;
  main_image: string | null;
  is_featured: number;
  is_active: number;
};

export type BrandedResidenceListRow = BrandedResidenceRow & { community_name: string | null; property_title: string | null };

export async function listBrandedResidences(filters: { search?: string; communityId?: string }): Promise<BrandedResidenceListRow[]> {
  const where: string[] = [];
  const params: Params = [];
  if (filters.search) {
    where.push("(branded_residences.title LIKE ? OR branded_residences.brand_name LIKE ?)");
    params.push(`%${filters.search}%`, `%${filters.search}%`);
  }
  if (filters.communityId) {
    where.push("branded_residences.community_id = ?");
    params.push(filters.communityId);
  }
  return query<BrandedResidenceListRow>(
    `SELECT branded_residences.*, communities.name AS community_name, properties.title AS property_title
       FROM branded_residences
       LEFT JOIN communities ON branded_residences.community_id = communities.id
       LEFT JOIN properties ON branded_residences.property_id = properties.id
       ${where.length ? `WHERE ${where.join(" AND ")}` : ""}
      ORDER BY branded_residences.created_at DESC`,
    params,
  );
}

export async function getBrandedResidence(id: number): Promise<BrandedResidenceRow | null> {
  return queryOne<BrandedResidenceRow>("SELECT * FROM branded_residences WHERE id = ?", [id]);
}
