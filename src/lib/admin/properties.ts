import "server-only";
import { query, queryOne, type Params } from "@/lib/db";

export type PropertyListRow = {
  id: number;
  title: string | null;
  reference_no: string | null;
  listing_type: string | null;
  price: number | null;
  price_currency: string | null;
  price_on_request: number | null;
  bedrooms: number | null;
  bathrooms: number | null;
  is_featured: number;
  status: string | null;
  main_image: string | null;
  community_name: string | null;
  type_name: string | null;
};

export type PropertyFilters = { listing_type?: string; status?: string; community_id?: string; search?: string };

/** PropertyController@index */
export async function listProperties(f: PropertyFilters): Promise<PropertyListRow[]> {
  const where: string[] = [];
  const params: Params = [];
  if (f.listing_type) {
    where.push("properties.listing_type = ?");
    params.push(f.listing_type);
  }
  if (f.status) {
    where.push("properties.status = ?");
    params.push(f.status);
  }
  if (f.community_id) {
    where.push("properties.community_id = ?");
    params.push(f.community_id);
  }
  if (f.search) {
    where.push("(properties.title LIKE ? OR properties.reference_no LIKE ?)");
    params.push(`%${f.search}%`, `%${f.search}%`);
  }
  return query<PropertyListRow>(
    `SELECT properties.*, communities.name AS community_name, property_types.name AS type_name
       FROM properties
       LEFT JOIN communities ON properties.community_id = communities.id
       LEFT JOIN property_types ON properties.property_type_id = property_types.id
      ${where.length ? `WHERE ${where.join(" AND ")}` : ""}
      ORDER BY properties.created_at DESC`,
    params,
  );
}

export type Option = { id: number; name: string | null };
export type AmenityOption = { id: number; name: string | null; icon: string | null; category: string | null };

export async function activeCommunities(): Promise<Option[]> {
  return query<Option>("SELECT id, name FROM communities WHERE is_active = 1 ORDER BY name");
}

/** Data for the create / edit forms. */
export async function propertyFormOptions() {
  const [communities, propertyTypes, amenities] = await Promise.all([
    activeCommunities(),
    query<Option>("SELECT id, name FROM property_types WHERE is_active = 1 ORDER BY sort_order"),
    query<AmenityOption>("SELECT id, name, icon, category FROM amenities ORDER BY category, sort_order"),
  ]);
  return { communities, propertyTypes, amenities };
}

export type PropertyRow = Record<string, unknown> & {
  id: number;
  title: string | null;
  reference_no: string | null;
  main_image: string | null;
  floor_plan_image: string | null;
  brochure_pdf: string | null;
};

export async function getProperty(id: number): Promise<PropertyRow | null> {
  return queryOne<PropertyRow>("SELECT * FROM properties WHERE id = ? LIMIT 1", [id]);
}

export async function propertyGallery(id: number) {
  return query<{ id: number; image_path: string | null }>(
    "SELECT id, image_path FROM property_images WHERE property_id = ? ORDER BY sort_order",
    [id],
  );
}

export async function propertyAmenityIds(id: number): Promise<string[]> {
  const rows = await query<{ amenity_id: number }>("SELECT amenity_id FROM property_amenities WHERE property_id = ?", [id]);
  return rows.map((r) => String(r.amenity_id));
}
