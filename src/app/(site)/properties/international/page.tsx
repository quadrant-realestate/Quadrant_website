import { getSettings, pick } from "@/lib/settings";
import type { SearchParams } from "@/lib/queries/properties";
import { ListingPage, type ListingConfig } from "../_components/ListingPage";
import { RENT_ALTS, RENT_CTA_TEXT, RENT_DESCRIPTION, listingMetadata, loadListing } from "../_components/listing-data";

// website/properties/international.blade.php — PropertyFrontController@international

type Props = { searchParams: Promise<SearchParams> };

const PATH = "/properties/international";

export async function generateMetadata({ searchParams }: Props) {
  return listingMetadata("international", await searchParams, PATH, ({ withDefault, raw }) => ({
    title: `International Properties — ${raw}`,
    description: RENT_DESCRIPTION(withDefault),
  }));
}

export default async function InternationalPropertiesPage({ searchParams }: Props) {
  const sp = await searchParams;
  const [data, settings] = await Promise.all([loadListing("international", sp), getSettings()]);
  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai");

  const config: ListingConfig = {
    path: PATH,
    heading: "International Properties",
    bannerImage: "/public/assets/img/rent.png",
    bannerListingType: "international",
    searchPlaceholder: "Search country and city...",
    breadcrumb: { href: PATH, label: "International" },
    cta: {
      image: "/public/assets/img/rent.png",
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
