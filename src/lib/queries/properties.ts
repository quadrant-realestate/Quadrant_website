import "server-only";
import { paginate, query, queryOne, type Paginated, type Params } from "@/lib/db";

// Read queries for the public property pages (PropertyFrontController).

export type SearchParams = Record<string, string | string[] | undefined>;

/** Laravel's $request->key: last value of a repeated key, like PHP's query parsing. */
export function param(sp: SearchParams, key: string): string | undefined {
  const v = sp[key];
  return Array.isArray(v) ? v[v.length - 1] : v;
}

/** PHP truthiness of a request string ("" and "0" are falsy). */
function truthy(v: string | undefined): v is string {
  return v !== undefined && v !== "" && v !== "0";
}

const isNumeric = (v: string) => v.trim() !== "" && Number.isFinite(Number(v));

export type PropertyListItem = {
  id: number;
  title: string | null;
  slug: string | null;
  reference_no: string | null;
  listing_type: string | null;
  status: string | null;
  price: number | null;
  price_currency: string | null;
  price_on_request: number | null;
  bedrooms: number | null;
  bathrooms: number | null;
  area_sqft: number | null;
  main_image: string | null;
  community_name: string | null;
  type_name: string | null;
};

export type PropertyType = { id: number; name: string | null };
export type Community = { id: number; name: string | null };

// baseQuery()
const LIST_FROM = `
  FROM properties
  LEFT JOIN communities ON properties.community_id = communities.id
  LEFT JOIN property_types ON properties.property_type_id = property_types.id
  WHERE properties.status = 'active'`;

const LIST_SELECT = `
  SELECT properties.id, properties.title, properties.slug, properties.reference_no, properties.listing_type,
         properties.status, properties.price, properties.price_currency, properties.price_on_request,
         properties.bedrooms, properties.bathrooms, properties.area_sqft, properties.main_image,
         communities.name AS community_name, property_types.name AS type_name`;

/**
 * Listing query for sale / rent / private / international / search.
 * `listingType` null = no listing_type filter (search page without one).
 * Same filters, sort and 12-per-page pagination as applyFilters().
 */
export async function listProperties(
  listingType: string | null,
  sp: SearchParams,
): Promise<Paginated<PropertyListItem>> {
  const where: string[] = [];
  const params: Params = [];

  if (listingType !== null) {
    where.push("properties.listing_type = ?");
    params.push(listingType);
  }

  const search = param(sp, "search");
  if (truthy(search)) {
    const like = `%${search}%`;
    where.push(
      "(properties.title LIKE ? OR communities.name LIKE ? OR properties.address LIKE ? OR properties.reference_no LIKE ?)",
    );
    params.push(like, like, like, like);
  }

  const typeId = param(sp, "property_type_id");
  if (truthy(typeId)) {
    where.push("properties.property_type_id = ?");
    params.push(typeId);
  }

  const bedrooms = param(sp, "bedrooms");
  if (truthy(bedrooms)) {
    // PHP: $request->bedrooms == '7'
    if (bedrooms === "7" || (isNumeric(bedrooms) && Number(bedrooms) === 7)) {
      where.push("properties.bedrooms >= ?");
      params.push(7);
    } else {
      where.push("properties.bedrooms = ?");
      params.push(bedrooms);
    }
  }

  const communityId = param(sp, "community_id");
  if (truthy(communityId)) {
    where.push("properties.community_id = ?");
    params.push(communityId);
  }

  const minPrice = param(sp, "min_price");
  if (truthy(minPrice)) {
    where.push("properties.price >= ?");
    params.push(minPrice);
  }

  const maxPrice = param(sp, "max_price");
  if (truthy(maxPrice)) {
    where.push("properties.price <= ?");
    params.push(maxPrice);
  }

  const sort = param(sp, "sort");
  const orderBy =
    sort === "price_asc"
      ? "properties.price ASC"
      : sort === "price_desc"
        ? "properties.price DESC"
        : "properties.created_at DESC";

  const filters = where.map((w) => ` AND ${w}`).join("");
  const page = Number(param(sp, "page") ?? 1);

  return paginate<PropertyListItem>(
    `${LIST_SELECT} ${LIST_FROM}${filters} ORDER BY ${orderBy}`,
    `SELECT COUNT(*) AS total ${LIST_FROM}${filters}`,
    params,
    page,
    12,
  );
}

export function getActivePropertyTypes(): Promise<PropertyType[]> {
  return query<PropertyType>("SELECT * FROM property_types WHERE is_active = 1 ORDER BY sort_order");
}

export function getActiveCommunities(): Promise<Community[]> {
  return query<Community>("SELECT * FROM communities WHERE is_active = 1 ORDER BY name");
}

// ---------------------------------------------------------------------------
// Detail page
// ---------------------------------------------------------------------------

export type PropertyDetail = PropertyListItem & {
  address: string | null;
  city: string | null;
  community_id: number | null;
  description: string | null;
  short_description: string | null;
  plot_area_sqft: number | null;
  parking_spaces: string | null;
  furnished: string | null;
  floor_number: string | null;
  total_floors: number | null;
  service_charge: string | null;
  latitude: number | null;
  longitude: number | null;
  google_maps_link: string | null;
  floor_plan_image: string | null;
  brochure_pdf: string | null;
  video_url: string | null;
  virtual_tour_url: string | null;
};

export type PropertyImage = { id: number; property_id: number; image_path: string | null; sort_order: number | null };
export type Amenity = { id: number; name: string | null; category: string | null };

export function getPropertyBySlug(slug: string): Promise<PropertyDetail | null> {
  return queryOne<PropertyDetail>(
    `SELECT properties.*, communities.name AS community_name, property_types.name AS type_name
       FROM properties
       LEFT JOIN communities ON properties.community_id = communities.id
       LEFT JOIN property_types ON properties.property_type_id = property_types.id
      WHERE properties.slug = ? AND properties.status = 'active'
      LIMIT 1`,
    [slug],
  );
}

export function getPropertyGallery(propertyId: number): Promise<PropertyImage[]> {
  return query<PropertyImage>("SELECT * FROM property_images WHERE property_id = ? ORDER BY sort_order", [propertyId]);
}

export function getPropertyAmenities(propertyId: number): Promise<Amenity[]> {
  return query<Amenity>(
    `SELECT amenities.* FROM property_amenities
       INNER JOIN amenities ON property_amenities.amenity_id = amenities.id
      WHERE property_amenities.property_id = ?
      ORDER BY amenities.category`,
    [propertyId],
  );
}

/** Related properties: same community (Laravel turns where('community_id', null) into IS NULL). */
export function getRelatedProperties(property: PropertyDetail): Promise<PropertyListItem[]> {
  return query<PropertyListItem>(
    `SELECT properties.id, properties.title, properties.slug, properties.price, properties.price_currency,
            properties.price_on_request, properties.bedrooms, properties.bathrooms, properties.area_sqft,
            properties.main_image, communities.name AS community_name, property_types.name AS type_name
       FROM properties
       LEFT JOIN communities ON properties.community_id = communities.id
       LEFT JOIN property_types ON properties.property_type_id = property_types.id
      WHERE properties.status = 'active' AND properties.id != ? AND ${
        property.community_id === null ? "properties.community_id IS NULL" : "properties.community_id = ?"
      }
      LIMIT 4`,
    property.community_id === null ? [property.id] : [property.id, property.community_id],
  );
}
