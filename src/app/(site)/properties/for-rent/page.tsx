import { getSettings, pick } from "@/lib/settings";
import type { SearchParams } from "@/lib/queries/properties";
import { ListingPage, type ListingConfig } from "../_components/ListingPage";
import { RENT_ALTS, RENT_CTA_TEXT, RENT_DESCRIPTION, listingMetadata, loadListing } from "../_components/listing-data";

// website/properties/rent.blade.php — PropertyFrontController@rent

type Props = { searchParams: Promise<SearchParams> };

const PATH = "/properties/for-rent";

export async function generateMetadata({ searchParams }: Props) {
  return listingMetadata("rent", await searchParams, PATH, ({ withDefault }) => ({
    title: `Rent Luxury Properties in Dubai — ${withDefault}`,
    description: RENT_DESCRIPTION(withDefault),
  }));
}

export default async function PropertiesForRentPage({ searchParams }: Props) {
  const sp = await searchParams;
  const [data, settings] = await Promise.all([loadListing("rent", sp), getSettings()]);
  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai");

  const config: ListingConfig = {
    path: PATH,
    heading: "Rent Properties in Dubai",
    bannerImage: "/public/assets/img/buy.png",
    bannerListingType: "rent",
    searchPlaceholder: "Search country and city...",
    breadcrumb: { href: PATH, label: "Rent" },
    cta: {
      image: "/public/assets/img/buy.png",
      heading: `Luxury Homes for Rent by ${siteName ?? ""}`,
      text: RENT_CTA_TEXT,
    },
    emptyText: "No rental properties found matching your search.",
    clearHref: PATH,
    priceMax: 1000000,
    priceStep: 5000,
    alts: RENT_ALTS,
    modal: { active: "rent", buyListingType: "sale" },
  };

  return <ListingPage config={config} searchParams={sp} {...data} />;
}
