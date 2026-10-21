import "server-only";
import { cache } from "react";
import { paginate, query, queryOne, type Params } from "@/lib/db";

// Port of InvestmentFrontController. DECIMAL columns are read as strings (CAST ... AS CHAR)
// because the Blade templates printed them raw ("8.5000%") and tested them with PHP truthiness.

export type InvestmentCard = {
  id: number;
  title: string | null;
  slug: string | null;
  investment_type: string | null;
  price: string | null;
  price_currency: string | null;
  roi_percentage: string | null;
  gross_yield: string | null;
  main_image: string | null;
  community_name: string | null;
  property_area_sqft: string | null;
};

export type Community = { id: number; name: string };

export const getInvestments = cache(
  async (
    search: string | null,
    investmentType: string | null,
    communityId: string | null,
    minPrice: string | null,
    maxPrice: string | null,
    sort: string | null,
    page: number,
  ) => {
    const where = ["investments.is_active = 1"];
    const params: Params = [];

    if (search) {
      where.push("(investments.title LIKE ? OR communities.name LIKE ? OR investments.investment_type LIKE ?)");
      params.push(`%${search}%`, `%${search}%`, `%${search}%`);
    }
    if (investmentType) {
      where.push("investments.investment_type = ?");
      params.push(investmentType);
    }
    if (communityId) {
      where.push("investments.community_id = ?");
      params.push(communityId);
    }
    if (minPrice) {
      where.push("investments.price >= ?");
      params.push(minPrice);
    }
    if (maxPrice) {
      where.push("investments.price <= ?");
      params.push(maxPrice);
    }

    let order = "investments.created_at DESC";
    if (sort === "price_asc") order = "investments.price ASC";
    else if (sort === "price_desc") order = "investments.price DESC";
    else if (sort === "roi_desc") order = "investments.roi_percentage DESC";

    const from = `FROM investments
      LEFT JOIN communities ON investments.community_id = communities.id
      LEFT JOIN properties ON investments.property_id = properties.id
      WHERE ${where.join(" AND ")}`;

    return paginate<InvestmentCard>(
      `SELECT investments.id, investments.title, investments.slug, investments.investment_type,
              CAST(investments.price AS CHAR) AS price, investments.price_currency,
              CAST(investments.roi_percentage AS CHAR) AS roi_percentage,
              CAST(investments.gross_yield AS CHAR) AS gross_yield,
              investments.main_image, communities.name AS community_name,
              CAST(properties.area_sqft AS CHAR) AS property_area_sqft
       ${from} ORDER BY ${order}`,
      `SELECT COUNT(*) AS total ${from}`,
      params,
      page,
      12,
    );
  },
);

export const getActiveCommunities = cache(() =>
  query<Community>("SELECT * FROM communities WHERE is_active = 1 ORDER BY name ASC"),
);

export const getInvestmentTypes = cache(async () => {
  const rows = await query<{ investment_type: string }>(
    `SELECT DISTINCT investment_type FROM investments
     WHERE is_active = 1 AND investment_type IS NOT NULL ORDER BY investment_type ASC`,
  );
  return rows.map((r) => r.investment_type);
});

export type InvestmentDetail = {
  id: number;
  title: string | null;
  slug: string | null;
  description: string | null;
  investment_type: string | null;
  price: string | null;
  price_currency: string | null;
  roi_percentage: string | null;
  gross_yield: string | null;
  net_yield: string | null;
  annual_rental: string | null;
  main_image: string | null;
  community_id: number | null;
  community_name: string | null;
  community_latitude: string | null;
  community_longitude: string | null;
  property_area_sqft: string | null;
  property_bedrooms: number | null;
  property_bathrooms: number | null;
  property_title: string | null;
  property_slug: string | null;
};

export const getInvestment = cache((slug: string) =>
  queryOne<InvestmentDetail>(
    `SELECT investments.*,
            CAST(investments.price AS CHAR) AS price,
            CAST(investments.roi_percentage AS CHAR) AS roi_percentage,
            CAST(investments.gross_yield AS CHAR) AS gross_yield,
            CAST(investments.net_yield AS CHAR) AS net_yield,
            communities.name AS community_name,
            CAST(communities.latitude AS CHAR) AS community_latitude,
            CAST(communities.longitude AS CHAR) AS community_longitude,
            CAST(properties.area_sqft AS CHAR) AS property_area_sqft,
            properties.bedrooms AS property_bedrooms,
            properties.bathrooms AS property_bathrooms,
            properties.title AS property_title,
            properties.slug AS property_slug
     FROM investments
     LEFT JOIN communities ON investments.community_id = communities.id
     LEFT JOIN properties ON investments.property_id = properties.id
     WHERE investments.slug = ? AND investments.is_active = 1
     LIMIT 1`,
    [slug],
  ),
);

export type RelatedInvestment = {
  id: number;
  title: string | null;
  slug: string | null;
  main_image: string | null;
  price: string | null;
  price_currency: string | null;
  investment_type: string | null;
  community_name: string | null;
};

/** Same community, excluding this one, 4 max. */
export async function getRelatedInvestments(id: number, communityId: number | null): Promise<RelatedInvestment[]> {
  if (communityId === null) {
    // ->where('investments.community_id', null) becomes "community_id IS NULL" in Laravel.
    return query<RelatedInvestment>(
      `${RELATED_SELECT} AND investments.community_id IS NULL LIMIT 4`,
      [id],
    );
  }
  return query<RelatedInvestment>(`${RELATED_SELECT} AND investments.community_id = ? LIMIT 4`, [id, communityId]);
}

const RELATED_SELECT = `SELECT investments.id, investments.title, investments.slug, investments.main_image,
       CAST(investments.price AS CHAR) AS price, investments.price_currency, investments.investment_type,
       communities.name AS community_name
  FROM investments
  LEFT JOIN communities ON investments.community_id = communities.id
  WHERE investments.is_active = 1 AND investments.id != ?`;
