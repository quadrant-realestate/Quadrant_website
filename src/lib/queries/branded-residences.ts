import "server-only";
import { cache } from "react";
import { paginate, query, queryOne, type Params } from "@/lib/db";

// Port of BrandedResidenceFrontController. DECIMAL columns are read as strings (see investments.ts).

export type ResidenceCard = {
  id: number;
  brand_name: string | null;
  brand_logo: string | null;
  title: string | null;
  slug: string | null;
  main_image: string | null;
  price_from: string | null;
  community_name: string | null;
};

export const getResidences = cache(async (search: string | null, communityId: string | null, page: number) => {
  const where = ["branded_residences.is_active = 1"];
  const params: Params = [];

  if (search) {
    where.push(
      "(branded_residences.title LIKE ? OR branded_residences.brand_name LIKE ? OR communities.name LIKE ?)",
    );
    params.push(`%${search}%`, `%${search}%`, `%${search}%`);
  }
  if (communityId) {
    where.push("branded_residences.community_id = ?");
    params.push(communityId);
  }

  const from = `FROM branded_residences
    LEFT JOIN communities ON branded_residences.community_id = communities.id
    WHERE ${where.join(" AND ")}`;

  return paginate<ResidenceCard>(
    `SELECT branded_residences.id, branded_residences.brand_name, branded_residences.brand_logo,
            branded_residences.title, branded_residences.slug, branded_residences.main_image,
            CAST(branded_residences.price_from AS CHAR) AS price_from, communities.name AS community_name
     ${from}
     ORDER BY branded_residences.is_featured DESC, branded_residences.created_at DESC`,
    `SELECT COUNT(*) AS total ${from}`,
    params,
    page,
    12,
  );
});

export type ResidenceDetail = {
  id: number;
  brand_name: string | null;
  brand_logo: string | null;
  title: string | null;
  slug: string | null;
  description: string | null;
  main_image: string | null;
  price_from: string | null;
  community_name: string | null;
  community_latitude: string | null;
  community_longitude: string | null;
  property_title: string | null;
  property_slug: string | null;
  property_area_sqft: string | null;
  property_bedrooms: number | null;
};

export const getResidence = cache((slug: string) =>
  queryOne<ResidenceDetail>(
    `SELECT branded_residences.*,
            CAST(branded_residences.price_from AS CHAR) AS price_from,
            communities.name AS community_name,
            CAST(communities.latitude AS CHAR) AS community_latitude,
            CAST(communities.longitude AS CHAR) AS community_longitude,
            properties.title AS property_title,
            properties.slug AS property_slug,
            CAST(properties.area_sqft AS CHAR) AS property_area_sqft,
            properties.bedrooms AS property_bedrooms
     FROM branded_residences
     LEFT JOIN communities ON branded_residences.community_id = communities.id
     LEFT JOIN properties ON branded_residences.property_id = properties.id
     WHERE branded_residences.slug = ? AND branded_residences.is_active = 1
     LIMIT 1`,
    [slug],
  ),
);

export type RelatedResidence = {
  id: number;
  title: string | null;
  slug: string | null;
  main_image: string | null;
  community_name: string | null;
};

/** Other active residences, 4 max (the controller does not filter by community despite its comment). */
export function getRelatedResidences(id: number): Promise<RelatedResidence[]> {
  return query<RelatedResidence>(
    `SELECT branded_residences.id, branded_residences.title, branded_residences.slug,
            branded_residences.main_image, communities.name AS community_name
     FROM branded_residences
     LEFT JOIN communities ON branded_residences.community_id = communities.id
     WHERE branded_residences.is_active = 1 AND branded_residences.id != ?
     LIMIT 4`,
    [id],
  );
}
