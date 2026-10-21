import { getSettings, pick } from "@/lib/settings";
import type { SearchParams } from "@/lib/queries/properties";
import { ListingPage, type ListingConfig } from "../_components/ListingPage";
import { RENT_ALTS, RENT_CTA_TEXT, RENT_DESCRIPTION, listingMetadata, loadListing } from "../_components/listing-data";

// website/properties/private.blade.php — PropertyFrontController@private

type Props = { searchParams: Promise<SearchParams> };

const PATH = "/properties/private-office";

export async function generateMetadata({ searchParams }: Props) {
  return listingMetadata("private", await searchParams, PATH, ({ withDefault, raw }) => ({
    title: `Private Office — ${raw}`,
    description: RENT_DESCRIPTION(withDefault),
  }));
}

export default async function PrivateOfficePage({ searchParams }: Props) {
  const sp = await searchParams;
  const [data, settings] = await Promise.all([loadListing("private", sp), getSettings()]);
  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai");

  const config: ListingConfig = {
    path: PATH,
    heading: "Private Office Listings in Dubai",
    bannerImage: "/public/assets/img/rent.png",
    bannerListingType: "private",
    searchPlaceholder: "Search country and city...",
    breadcrumb: { href: PATH, label: "Private Office" },
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
