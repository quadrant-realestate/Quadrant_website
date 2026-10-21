import "server-only";
import { cache } from "react";
import {
  getActiveCommunities,
  getActivePropertyTypes,
  listProperties,
  type SearchParams,
} from "@/lib/queries/properties";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";

// Shared data loading for the five listing pages (sale / rent / private / international / search).

// One query per request even though generateMetadata (noindex) and the page both need it.
const cachedList = cache((listingType: string | null, key: string) => listProperties(listingType, JSON.parse(key)));

export async function loadListing(listingType: string | null, searchParams: SearchParams) {
  const [properties, propertyTypes, communities] = await Promise.all([
    cachedList(listingType, JSON.stringify(searchParams)),
    getActivePropertyTypes(),
    getActiveCommunities(),
  ]);
  return { properties, propertyTypes, communities };
}

/** Title / description per page, as in the Blade @section('title') / @section('meta_description'). */
export async function listingMetadata(
  listingType: string | null,
  searchParams: SearchParams,
  path: string,
  seo: (siteName: { withDefault: string; raw: string }) => { title: string; description: string },
) {
  const settings = await getSettings();
  const siteName = {
    withDefault: pick(settings, "site_name", "Quadrant Properties Dubai") ?? "",
    raw: pick(settings, "site_name") ?? "",
  };
  const properties = await cachedList(listingType, JSON.stringify(searchParams));
  const { title, description } = seo(siteName);
  return pageMetadata({ title, description, path, noindex: properties.total === 0 });
}

/** meta_description used by rent / private / international / search (all copied from rent.blade.php). */
export const RENT_DESCRIPTION = (siteName: string) =>
  `Explore luxury properties for rent in Dubai with ${siteName}. Discover exclusive villas, apartments, and high-end homes available for rent.`;

export const RENT_CTA_TEXT =
  "Discover the very best apartments, penthouses, townhouses, and villas for rent across Dubai. Located in some of the city's most sought-after communities, these homes are designed to suit every lifestyle.";

export const RENT_ALTS = { map: "Map", area: "Area", bed: "Beds", bath: "Baths" };
