import "server-only";
import { query, queryOne } from "@/lib/db";

export type AmenityRow = {
  id: number;
  name: string | null;
  category: string | null;
  icon: string | null;
  sort_order: number | null;
  properties_count: number;
};

/** AmenityController@index (+ the per-row property count the Blade view queried). */
export async function listAmenities(): Promise<AmenityRow[]> {
  return query<AmenityRow>(
    `SELECT amenities.*, (SELECT COUNT(*) FROM property_amenities WHERE property_amenities.amenity_id = amenities.id) AS properties_count
       FROM amenities
      ORDER BY category ASC, sort_order ASC`,
  );
}

export async function getAmenity(id: number) {
  return queryOne<{ id: number; name: string | null; category: string | null; icon: string | null; sort_order: number | null }>(
    "SELECT id, name, category, icon, sort_order FROM amenities WHERE id = ? LIMIT 1",
    [id],
  );
}
