import "server-only";
import { query, queryOne, type Params } from "@/lib/db";

// Read queries for the admin developments, floor plans and development amenities pages
// (DevelopmentController / DevelopmentFloorPlanController / DevelopmentAmenityController).

export type DevelopmentRow = {
  id: number;
  title: string | null;
  slug: string | null;
  developer_name: string | null;
  developer_logo: string | null;
  community_id: number | null;
  description: string | null;
  short_description: string | null;
  property_types: string | null;
  total_units: number | null;
  floors: number | null;
  price_from: number | null;
  price_currency: string | null;
  payment_plan: string | null;
  down_payment_pct: number | null;
  handover_date: string | null;
  completion_pct: number | null;
  main_image: string | null;
  banner_image: string | null;
  brochure_pdf: string | null;
  video_url: string | null;
  status: string | null;
  is_featured: number;
  is_active: number;
  meta_title: string | null;
  meta_desc: string | null;
  rera_permit: string | null;
  rera_qr_image: string | null;
  quadrant_view: string | null;
  bedroom_range: string | null;
  size_from: number | null;
  size_to: number | null;
  og_image: string | null;
  nearby_landmarks: string | null;
};

export type DevelopmentListRow = DevelopmentRow & { community_name: string | null };
export type CommunityOption = { id: number; name: string | null };

/** index(): optional status filter and title / developer search, newest first. */
export async function listDevelopments(filters: { status?: string; search?: string }): Promise<DevelopmentListRow[]> {
  const where: string[] = [];
  const params: Params = [];
  if (filters.status) {
    where.push("developments.status = ?");
    params.push(filters.status);
  }
  if (filters.search) {
    where.push("(developments.title LIKE ? OR developments.developer_name LIKE ?)");
    params.push(`%${filters.search}%`, `%${filters.search}%`);
  }
  return query<DevelopmentListRow>(
    `SELECT developments.*, communities.name AS community_name
       FROM developments
       LEFT JOIN communities ON developments.community_id = communities.id
       ${where.length ? `WHERE ${where.join(" AND ")}` : ""}
      ORDER BY developments.created_at DESC`,
    params,
  );
}

/** Active communities for the community dropdowns. */
export async function activeCommunities(): Promise<CommunityOption[]> {
  return query<CommunityOption>("SELECT id, name FROM communities WHERE is_active = 1 ORDER BY name");
}

export async function getDevelopment(id: number): Promise<DevelopmentRow | null> {
  return queryOne<DevelopmentRow>("SELECT * FROM developments WHERE id = ?", [id]);
}

export type GalleryImage = { id: number; image_path: string | null; sort_order: number | null };

export async function developmentGallery(id: number): Promise<GalleryImage[]> {
  return query<GalleryImage>(
    "SELECT id, image_path, sort_order FROM development_images WHERE development_id = ? ORDER BY sort_order",
    [id],
  );
}

// ---------------------------------------------------------------------------
// Floor plans
// ---------------------------------------------------------------------------

export type FloorPlanRow = {
  id: number;
  development_id: number;
  unit_type: string | null;
  size_sqft: number | null;
  image: string | null;
  pdf_file: string | null;
  sort_order: number | null;
};

export async function floorPlans(developmentId: number): Promise<FloorPlanRow[]> {
  return query<FloorPlanRow>("SELECT * FROM development_floor_plans WHERE development_id = ? ORDER BY sort_order", [
    developmentId,
  ]);
}

/** Like the controller, looked up by id only. */
export async function getFloorPlan(id: number): Promise<FloorPlanRow | null> {
  return queryOne<FloorPlanRow>("SELECT * FROM development_floor_plans WHERE id = ?", [id]);
}

// ---------------------------------------------------------------------------
// Development amenities
// ---------------------------------------------------------------------------

export type AmenityOption = { id: number; name: string | null; category: string | null };

export async function allAmenities(): Promise<AmenityOption[]> {
  return query<AmenityOption>("SELECT id, name, category FROM amenities ORDER BY category, name");
}

export async function selectedAmenityIds(developmentId: number): Promise<number[]> {
  const rows = await query<{ amenity_id: number }>("SELECT amenity_id FROM development_amenities WHERE development_id = ?", [
    developmentId,
  ]);
  return rows.map((r) => Number(r.amenity_id));
}
