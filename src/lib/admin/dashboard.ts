import "server-only";
import { query } from "@/lib/db";

export type DashboardRecentInquiry = {
  id: number;
  name: string | null;
  email: string | null;
  phone: string | null;
  inquiry_type: string | null;
  message: string | null;
  status: string | null;
  created_at: Date | null;
  property_title: string | null;
};

export type DashboardRecentProperty = {
  id: number;
  title: string | null;
  reference_no: string | null;
  listing_type: string | null;
  price: number | null;
  price_currency: string | null;
  price_on_request: number | null;
  bedrooms: number | null;
  status: string | null;
  main_image: string | null;
  created_at: Date | null;
  community_name: string | null;
};

/** Everything AdminController@dashboard passed to admin/index.blade.php. */
export async function getDashboardData() {
  const [counts] = await query<Record<string, number>>(
    `SELECT
       (SELECT COUNT(*) FROM properties) AS totalProperties,
       (SELECT COUNT(*) FROM properties WHERE status = 'active') AS activeProperties,
       (SELECT COUNT(*) FROM properties WHERE status = 'sold') AS soldProperties,
       (SELECT COUNT(*) FROM properties WHERE status = 'rented') AS rentedProperties,
       (SELECT COUNT(*) FROM properties WHERE listing_type = 'sale') AS forSale,
       (SELECT COUNT(*) FROM properties WHERE listing_type = 'rent') AS forRent,
       (SELECT COUNT(*) FROM properties WHERE listing_type = 'off_plan') AS offPlan,
       (SELECT COUNT(*) FROM developments) AS totalDevelopments,
       (SELECT COUNT(*) FROM investments) AS totalInvestments,
       (SELECT COUNT(*) FROM branded_residences) AS totalBrandedRes,
       (SELECT COUNT(*) FROM communities) AS totalCommunities,
       (SELECT COUNT(*) FROM inquiries) AS totalInquiries,
       (SELECT COUNT(*) FROM inquiries WHERE status = 'new') AS newInquiries,
       (SELECT COUNT(*) FROM inquiries WHERE status = 'contacted') AS contactedInquiries,
       (SELECT COUNT(*) FROM inquiries WHERE status = 'qualified') AS qualifiedInquiries,
       (SELECT COUNT(*) FROM properties WHERE is_featured = 1) AS featuredProperties,
       (SELECT COUNT(*) FROM developments WHERE is_featured = 1) AS featuredDevelopments`,
  );

  const [recentInquiries, recentProperties, devByStatus] = await Promise.all([
    query<DashboardRecentInquiry>(
      `SELECT inquiries.*, properties.title AS property_title
         FROM inquiries
         LEFT JOIN properties ON inquiries.property_id = properties.id
        ORDER BY inquiries.created_at DESC
        LIMIT 8`,
    ),
    query<DashboardRecentProperty>(
      `SELECT properties.id, properties.title, properties.reference_no, properties.listing_type, properties.price,
              properties.price_currency, properties.price_on_request, properties.bedrooms, properties.status,
              properties.main_image, properties.created_at, communities.name AS community_name
         FROM properties
         LEFT JOIN communities ON properties.community_id = communities.id
        ORDER BY properties.created_at DESC
        LIMIT 6`,
    ),
    query<{ status: string | null; total: number }>("SELECT status, COUNT(*) AS total FROM developments GROUP BY status"),
  ]);

  const c = Object.fromEntries(Object.entries(counts ?? {}).map(([k, v]) => [k, Number(v ?? 0)]));
  return { counts: c, recentInquiries, recentProperties, devByStatus };
}
