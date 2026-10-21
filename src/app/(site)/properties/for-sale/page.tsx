import { getSettings, pick } from "@/lib/settings";
import type { SearchParams } from "@/lib/queries/properties";
import { ListingPage, type ListingConfig } from "../_components/ListingPage";
import { listingMetadata, loadListing } from "../_components/listing-data";

// website/properties/sale.blade.php — PropertyFrontController@sale

type Props = { searchParams: Promise<SearchParams> };

const PATH = "/properties/for-sale";

export async function generateMetadata({ searchParams }: Props) {
  return listingMetadata("sale", await searchParams, PATH, ({ withDefault }) => ({
    title: `Buy Luxury Properties in Dubai — ${withDefault}`,
    description: `Explore luxury properties for sale in Dubai with ${withDefault}. Discover exclusive villas, penthouses, and high-end homes in prime locations.`,
  }));
}

export default async function PropertiesForSalePage({ searchParams }: Props) {
  const sp = await searchParams;
  const [data, settings] = await Promise.all([loadListing("sale", sp), getSettings()]);
  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai");

  const config: ListingConfig = {
    path: PATH,
    heading: "Buy Luxury Properties in Dubai",
    bannerImage: "/public/assets/img/buy.png",
    bannerListingType: "sale",
    searchPlaceholder: "Search community, area or property...",
    breadcrumb: { href: PATH, label: "Buy" },
    cta: {
      image: "/public/assets/img/buy.png",
      heading: `Luxury Homes for Sale by ${siteName ?? ""}`,
      text: "Discover the very best apartments, penthouses, townhouses, and villas for sale across Dubai. Located in some of the city's most sought-after communities, these homes are designed to suit every lifestyle. From sleek, modern penthouses with panoramic skyline views to grand villas in exclusive, gated enclaves, our curated selection of luxury properties promises both elegance and comfort.",
    },
    emptyText: "No properties found matching your search.",
    clearHref: PATH,
    priceMax: 100000000,
    priceStep: 100000,
    alts: { map: "Map Icon", area: "Area icon", bed: "Bed icon", bath: "Bath icon" },
    modal: { active: "buy", buyListingType: "sale" },
  };

  return <ListingPage config={config} searchParams={sp} {...data} />;
}
