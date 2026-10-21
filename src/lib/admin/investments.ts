import "server-only";
import { query, queryOne, type Params } from "@/lib/db";

// Read queries for the admin investments pages (InvestmentController).

export type InvestmentRow = {
  id: number;
  title: string | null;
  slug: string | null;
  property_id: number | null;
  community_id: number | null;
  description: string | null;
  investment_type: string | null;
  price: number | null;
  price_currency: string | null;
  roi_percentage: number | null;
  gross_yield: number | null;
  net_yield: number | null;
  annual_rental: string | null;
  main_image: string | null;
  is_featured: number;
  is_active: number;
};

export type InvestmentListRow = InvestmentRow & { community_name: string | null; property_title: string | null };
export type PropertyOption = { id: number; title: string | null; reference_no: string | null };

export async function listInvestments(filters: { search?: string; communityId?: string }): Promise<InvestmentListRow[]> {
  const where: string[] = [];
  const params: Params = [];
  if (filters.search) {
    where.push("(investments.title LIKE ? OR investments.investment_type LIKE ?)");
    params.push(`%${filters.search}%`, `%${filters.search}%`);
  }
  if (filters.communityId) {
    where.push("investments.community_id = ?");
    params.push(filters.communityId);
  }
  return query<InvestmentListRow>(
    `SELECT investments.*, communities.name AS community_name, properties.title AS property_title
       FROM investments
       LEFT JOIN communities ON investments.community_id = communities.id
       LEFT JOIN properties ON investments.property_id = properties.id
       ${where.length ? `WHERE ${where.join(" AND ")}` : ""}
      ORDER BY investments.created_at DESC`,
    params,
  );
}

export async function getInvestment(id: number): Promise<InvestmentRow | null> {
  return queryOne<InvestmentRow>("SELECT * FROM investments WHERE id = ?", [id]);
}

/** Active properties for the "Linked Property" dropdowns (investments + branded residences). */
export async function activeProperties(): Promise<PropertyOption[]> {
  return query<PropertyOption>("SELECT id, title, reference_no FROM properties WHERE status = 'active' ORDER BY title");
}
