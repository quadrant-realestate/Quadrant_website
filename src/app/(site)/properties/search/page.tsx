import { getSettings, pick } from "@/lib/settings";
import { param, type SearchParams } from "@/lib/queries/properties";
import { ListingPage, type ListingConfig } from "../_components/ListingPage";
import { RENT_ALTS, RENT_CTA_TEXT, RENT_DESCRIPTION, listingMetadata, loadListing } from "../_components/listing-data";
import { strFilled } from "../_components/php";

// website/properties/search.blade.php — PropertyFrontController@search
// (copied from the rent page in the old project: banner + "Clear Filters" point at international)

type Props = { searchParams: Promise<SearchParams> };

const PATH = "/properties/search";

/** Filter by listing type only when one is given ($request->listing_type). */
function listingTypeOf(sp: SearchParams): string | null {
  const type = param(sp, "listing_type");
  return strFilled(type) ? type : null;
}

export async function generateMetadata({ searchParams }: Props) {
  const sp = await searchParams;
  return listingMetadata(listingTypeOf(sp), sp, PATH, ({ withDefault, raw }) => ({
    title: `Search Results — ${raw}`,
    description: RENT_DESCRIPTION(withDefault),
  }));
}

export default async function PropertySearchPage({ searchParams }: Props) {
  const sp = await searchParams;
  const listingType = listingTypeOf(sp);
  const [data, settings] = await Promise.all([loadListing(listingType, sp), getSettings()]);
  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai");

  const config: ListingConfig = {
    path: PATH,
    heading: "Search Results",
    bannerImage: "/public/assets/img/buy.png",
    bannerListingType: "international",
    searchPlaceholder: "Search country and city...",
    breadcrumb: { href: PATH, label: "Search Results" },
    cta: {
      image: "/public/assets/img/buy.png",
      heading: `Luxury Homes for Rent by ${siteName ?? ""}`,
      text: RENT_CTA_TEXT,
    },
    emptyText: "No rental properties found matching your search.",
    clearHref: "/properties/international",
    priceMax: 1000000,
    priceStep: 5000,
    alts: RENT_ALTS,
    modal: { active: "rent", buyListingType: listingType },
  };

  return <ListingPage config={config} searchParams={sp} {...data} />;
}
