import "server-only";
import { query, queryOne } from "@/lib/db";

export type PropertyTypeRow = {
  id: number;
  name: string | null;
  slug: string | null;
  sort_order: number | null;
  is_active: number;
  properties_count: number;
};

/** PropertyTypeController@index (+ the per-row property count the Blade view queried). */
export async function listPropertyTypes(): Promise<PropertyTypeRow[]> {
  return query<PropertyTypeRow>(
    `SELECT property_types.*, (SELECT COUNT(*) FROM properties WHERE properties.property_type_id = property_types.id) AS properties_count
       FROM property_types
      ORDER BY sort_order ASC`,
  );
}

export async function getPropertyType(id: number) {
  return queryOne<{ id: number; name: string | null; slug: string | null; sort_order: number | null; is_active: number }>(
    "SELECT id, name, slug, sort_order, is_active FROM property_types WHERE id = ? LIMIT 1",
    [id],
  );
}
