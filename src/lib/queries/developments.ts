import "server-only";
import { paginate, query, queryOne, type Paginated, type Params } from "@/lib/db";

// Read queries for the public development pages (DevelopmentFrontController).
// DECIMAL columns are selected as CHAR so they print exactly like PHP did ("666000.0000"),
// and PHP truthiness ("0.00" is truthy) can be reproduced.

export type DevelopmentCard = {
  id: number;
  title: string;
  slug: string;
  main_image: string | null;
  developer_name: string | null;
  developer_logo: string | null;
  price_from: string | null;
  price_currency: string | null;
  status: string | null;
  handover_date: string | null;
  property_types: string | null;
  bedroom_range: string | null;
  payment_plan: string | null;
  rera_permit: string | null;
  is_featured: number | null;
  community_name: string | null;
};

export type DevelopmentFilters = {
  search?: string;
  community_id?: string;
  developer?: string;
  property_type?: string;
  bedrooms?: string;
  min_price?: string;
  max_price?: string;
  handover_year?: string;
  status?: string;
  sort?: string;
};

/** PHP truthiness for request values (null, "" and "0" are falsy). */
const filled = (v: string | undefined): v is string => v !== undefined && v !== "" && v !== "0";

/** DevelopmentFrontController@index — filtered, sorted, 12 per page. */
export async function getDevelopmentsPage(f: DevelopmentFilters, page: number): Promise<Paginated<DevelopmentCard>> {
  const where: string[] = ["developments.is_active = 1"];
  const params: Params = [];

  if (filled(f.search)) {
    where.push("(developments.title LIKE ? OR developments.developer_name LIKE ? OR communities.name LIKE ?)");
    const like = `%${f.search}%`;
    params.push(like, like, like);
  }
  if (filled(f.community_id)) {
    where.push("developments.community_id = ?");
    params.push(f.community_id);
  }
  if (filled(f.developer)) {
    where.push("developments.developer_name = ?");
    params.push(f.developer);
  }
  if (filled(f.property_type)) {
    where.push("developments.property_types LIKE ?");
    params.push(`%${f.property_type}%`);
  }
  if (filled(f.bedrooms)) {
    where.push("developments.bedroom_range LIKE ?");
    params.push(`%${f.bedrooms}%`);
  }
  if (filled(f.min_price)) {
    where.push("developments.price_from >= ?");
    params.push(f.min_price);
  }
  if (filled(f.max_price)) {
    where.push("developments.price_from <= ?");
    params.push(f.max_price);
  }
  if (filled(f.handover_year)) {
    where.push("YEAR(developments.handover_date) = ?");
    params.push(f.handover_year);
  }
  if (filled(f.status)) {
    where.push("developments.status = ?");
    params.push(f.status);
  }

  let orderBy: string;
  switch (f.sort) {
    case "price_asc":
      orderBy = "developments.price_from ASC";
      break;
    case "price_desc":
      orderBy = "developments.price_from DESC";
      break;
    case "handover_soonest":
      orderBy = "developments.handover_date ASC";
      break;
    case "featured":
      orderBy = "developments.is_featured DESC, developments.created_at DESC";
      break;
    default:
      orderBy = "developments.created_at DESC";
  }

  const from = `FROM developments LEFT JOIN communities ON developments.community_id = communities.id WHERE ${where.join(" AND ")}`;
  const sql = `SELECT developments.id, developments.title, developments.slug, developments.main_image,
      developments.developer_name, developments.developer_logo,
      CAST(developments.price_from AS CHAR) AS price_from, developments.price_currency, developments.status,
      developments.handover_date, developments.property_types, developments.bedroom_range, developments.payment_plan,
      developments.rera_permit, developments.is_featured, communities.name AS community_name
    ${from} ORDER BY ${orderBy}`;

  return paginate<DevelopmentCard>(sql, `SELECT COUNT(*) AS total ${from}`, params, page, 12);
}

export type FilterCommunity = { id: number; name: string };

const FILTER_COMMUNITY_SLUGS = [
  "downtown-dubai",
  "dubai-marina",
  "palm-jebel-ali",
  "dubai-creek-harbour",
  "dubai-hills-estate",
  "jvc",
  "dubai-south",
];

/** Community dropdown on /buy. */
export function getFilterCommunities(): Promise<FilterCommunity[]> {
  return query<FilterCommunity>(
    `SELECT id, name FROM communities WHERE slug IN (${FILTER_COMMUNITY_SLUGS.map(() => "?").join(", ")}) ORDER BY sort_order`,
    FILTER_COMMUNITY_SLUGS,
  );
}

/** Developer dropdown on /buy. */
export async function getFilterDevelopers(): Promise<string[]> {
  const rows = await query<{ developer_name: string }>(
    "SELECT DISTINCT developer_name FROM developments WHERE is_active = 1 AND developer_name IS NOT NULL ORDER BY developer_name",
  );
  return rows.map((r) => r.developer_name);
}

/** Handover year dropdown on /buy (years computed from the stored string, like the PHP code). */
export async function getHandoverYears(): Promise<number[]> {
  const rows = await query<{ handover_date: string }>(
    "SELECT handover_date FROM developments WHERE is_active = 1 AND handover_date IS NOT NULL",
  );
  const years = rows.map((r) => parseInt(String(r.handover_date).slice(0, 4), 10)).filter((y) => Number.isFinite(y) && y !== 0);
  return [...new Set(years)].sort((a, b) => a - b);
}

// ---------------------------------------------------------------------------
// Detail page
// ---------------------------------------------------------------------------

export type DevelopmentDetail = {
  id: number;
  title: string;
  slug: string;
  community_id: number | null;
  developer_name: string | null;
  main_image: string | null;
  price_from: string | null;
  price_currency: string | null;
  status: string | null;
  handover_date: string | null;
  property_types: string | null;
  payment_plan: string | null;
  description: string | null;
  short_description: string | null;
  nearby_landmarks: string | null;
  brochure_pdf: string | null;
  community_name: string | null;
  community_latitude: string | null;
  community_longitude: string | null;
};

export async function getDevelopmentBySlug(slug: string): Promise<DevelopmentDetail | null> {
  return queryOne<DevelopmentDetail>(
    `SELECT developments.id, developments.title, developments.slug, developments.community_id,
        developments.developer_name, developments.main_image, CAST(developments.price_from AS CHAR) AS price_from,
        developments.price_currency, developments.status, developments.handover_date, developments.property_types,
        developments.payment_plan, developments.description, developments.short_description,
        developments.nearby_landmarks, developments.brochure_pdf,
        communities.name AS community_name,
        CAST(communities.latitude AS CHAR) AS community_latitude,
        CAST(communities.longitude AS CHAR) AS community_longitude
      FROM developments
      LEFT JOIN communities ON developments.community_id = communities.id
      WHERE developments.slug = ? AND developments.is_active = 1
      LIMIT 1`,
    [slug],
  );
}

export type GalleryImage = { id: number; image_path: string };
export type FloorPlan = { id: number; unit_type: string | null; image: string | null; pdf_file: string | null; size_sqft: string | null };
export type Amenity = { id: number; name: string; category: string | null; icon: string | null };
export type RelatedDevelopment = {
  id: number;
  title: string;
  slug: string;
  main_image: string | null;
  price_from: string | null;
  price_currency: string | null;
  community_name: string | null;
};

export function getDevelopmentGallery(id: number): Promise<GalleryImage[]> {
  return query<GalleryImage>("SELECT id, image_path FROM development_images WHERE development_id = ? ORDER BY sort_order", [id]);
}

export function getDevelopmentFloorPlans(id: number): Promise<FloorPlan[]> {
  return query<FloorPlan>(
    `SELECT id, unit_type, image, pdf_file, CAST(size_sqft AS CHAR) AS size_sqft
      FROM development_floor_plans WHERE development_id = ? ORDER BY sort_order`,
    [id],
  );
}

export function getDevelopmentAmenities(id: number): Promise<Amenity[]> {
  return query<Amenity>(
    `SELECT amenities.id, amenities.name, amenities.category, amenities.icon
      FROM development_amenities
      INNER JOIN amenities ON development_amenities.amenity_id = amenities.id
      WHERE development_amenities.development_id = ?
      ORDER BY amenities.category`,
    [id],
  );
}

/** Same community OR same developer (Laravel turns `= null` into IS NULL), no ordering, 3 rows. */
export function getRelatedDevelopments(d: DevelopmentDetail): Promise<RelatedDevelopment[]> {
  const params: Params = [d.id];
  const community = d.community_id === null ? "developments.community_id IS NULL" : "developments.community_id = ?";
  if (d.community_id !== null) params.push(d.community_id);
  const developer = d.developer_name === null ? "developments.developer_name IS NULL" : "developments.developer_name = ?";
  if (d.developer_name !== null) params.push(d.developer_name);

  return query<RelatedDevelopment>(
    `SELECT developments.id, developments.title, developments.slug, developments.main_image,
        CAST(developments.price_from AS CHAR) AS price_from, developments.price_currency,
        communities.name AS community_name
      FROM developments
      LEFT JOIN communities ON developments.community_id = communities.id
      WHERE developments.is_active = 1 AND developments.id != ? AND (${community} OR ${developer})
      LIMIT 3`,
    params,
  );
}
