import "server-only";
import { sanityImage, sanityQuery } from "@/lib/sanity";
import { renderPortableText } from "@/lib/portable-text";
import type {
  Amenity,
  DevelopmentCard,
  DevelopmentDetail,
  DevelopmentFilters,
  FloorPlan,
  GalleryImage,
  RelatedDevelopment,
} from "@/lib/queries/developments";

// Off-plan projects the team adds in Sanity. They are shaped like rows of the developments table,
// so the Buy list and the /buy/[slug] page render them the same way.

type SanityOffPlanProject = {
  _id: string;
  _createdAt: string;
  title: string;
  slug: string;
  status: string | null;
  developer: string | null;
  priceFrom: number | null;
  priceCurrency: string | null;
  propertyTypes: string[] | null;
  bedroomRange: string | null;
  handoverDate: string | null;
  paymentPlan: string | null;
  reraPermit: string | null;
  isFeatured: boolean | null;
  community: string | null;
  image: string | null;
};

const CARD_FIELDS = `
  _id,
  _createdAt,
  title,
  "slug": slug.current,
  status,
  developer,
  priceFrom,
  priceCurrency,
  propertyTypes,
  bedroomRange,
  handoverDate,
  paymentPlan,
  reraPermit,
  isFeatured,
  community,
  "image": mainImage.asset->url
`;

const ACTIVE = `_type == "offPlanProject" && defined(title) && defined(slug.current) && isActive != false`;

export type OffPlanCard = DevelopmentCard & { created_at: string };

function toCard(p: SanityOffPlanProject): OffPlanCard {
  return {
    id: 0,
    title: p.title,
    slug: p.slug,
    main_image: sanityImage(p.image, 760, 560),
    developer_name: p.developer,
    developer_logo: null,
    price_from: p.priceFrom ? String(p.priceFrom) : null,
    price_currency: p.priceCurrency,
    status: p.status,
    handover_date: p.handoverDate,
    property_types: p.propertyTypes?.length ? p.propertyTypes.join(", ") : null,
    bedroom_range: p.bedroomRange,
    payment_plan: p.paymentPlan,
    rera_permit: p.reraPermit,
    is_featured: p.isFeatured ? 1 : 0,
    community_name: p.community,
    created_at: p._createdAt,
  };
}

export async function getOffPlanProjects(): Promise<OffPlanCard[]> {
  const rows = await sanityQuery<SanityOffPlanProject[]>(
    `*[${ACTIVE}] | order(_createdAt desc) {${CARD_FIELDS}}`,
    {},
    [],
  );
  return rows.map(toCard);
}

const contains = (haystack: string | null, needle: string) =>
  (haystack ?? "").toLowerCase().includes(needle.toLowerCase());

/**
 * The /buy filters and sort, applied the same way the SQL query applies them.
 * `communityName` is the name of the community picked in the filter (Sanity stores names, not ids).
 */
export function filterOffPlanProjects(
  projects: OffPlanCard[],
  f: DevelopmentFilters,
  communityName: string | null,
): OffPlanCard[] {
  const filled = (v: string | undefined): v is string => v !== undefined && v !== "" && v !== "0";

  const result = projects.filter((p) => {
    if (filled(f.search) && ![p.title, p.developer_name, p.community_name].some((v) => contains(v, f.search!))) return false;
    if (filled(f.community_id) && (p.community_name ?? "").toLowerCase() !== (communityName ?? "").toLowerCase()) return false;
    if (filled(f.developer) && p.developer_name !== f.developer) return false;
    if (filled(f.property_type) && !contains(p.property_types, f.property_type)) return false;
    if (filled(f.bedrooms) && !contains(p.bedroom_range, f.bedrooms)) return false;
    if (filled(f.min_price) && Number(p.price_from ?? 0) < Number(f.min_price)) return false;
    if (filled(f.max_price) && Number(p.price_from ?? 0) > Number(f.max_price)) return false;
    if (filled(f.handover_year) && (p.handover_date ?? "").slice(0, 4) !== f.handover_year) return false;
    if (filled(f.status) && p.status !== f.status) return false;
    return true;
  });

  const price = (p: OffPlanCard) => Number(p.price_from ?? 0);
  switch (f.sort) {
    case "price_asc":
      return result.sort((a, b) => price(a) - price(b));
    case "price_desc":
      return result.sort((a, b) => price(b) - price(a));
    case "handover_soonest":
      return result.sort((a, b) => (a.handover_date ?? "").localeCompare(b.handover_date ?? ""));
    case "featured":
      return result.sort((a, b) => (b.is_featured ?? 0) - (a.is_featured ?? 0) || b.created_at.localeCompare(a.created_at));
    default:
      return result;
  }
}

// ---------------------------------------------------------------------------
// Detail page
// ---------------------------------------------------------------------------

export type OffPlanProjectPage = {
  development: DevelopmentDetail;
  gallery: GalleryImage[];
  floorPlans: FloorPlan[];
  amenities: Amenity[];
  related: RelatedDevelopment[];
};

type SanityOffPlanDetail = SanityOffPlanProject & {
  shortDescription: string | null;
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  description: any[] | null;
  nearbyLandmarks: string | null;
  brochure: string | null;
  latitude: number | null;
  longitude: number | null;
  gallery: string[] | null;
  floorPlans: { unitType: string | null; sizeSqft: number | null; image: string | null; pdf: string | null }[] | null;
  amenities: string[] | null;
  related: SanityOffPlanProject[] | null;
};

export async function getOffPlanProjectBySlug(slug: string): Promise<OffPlanProjectPage | null> {
  const p = await sanityQuery<SanityOffPlanDetail | null>(
    `*[${ACTIVE} && slug.current == $slug][0] {
        ${CARD_FIELDS},
        shortDescription,
        description,
        nearbyLandmarks,
        "brochure": brochure.asset->url,
        "latitude": location.lat,
        "longitude": location.lng,
        "gallery": gallery[].asset->url,
        "floorPlans": floorPlans[]{unitType, sizeSqft, "image": image.asset->url, "pdf": pdf.asset->url},
        amenities,
        "related": *[${ACTIVE} && _id != ^._id
            && ((defined(^.community) && community == ^.community) || (defined(^.developer) && developer == ^.developer))
          ] | order(_createdAt desc) [0...3] {${CARD_FIELDS}}
      }`,
    { slug },
    null,
  );
  if (!p) return null;

  const description = renderPortableText(p.description);

  return {
    development: {
      id: 0,
      title: p.title,
      slug: p.slug,
      community_id: null,
      developer_name: p.developer,
      main_image: sanityImage(p.image, 1600),
      price_from: p.priceFrom ? String(p.priceFrom) : null,
      price_currency: p.priceCurrency,
      status: p.status,
      handover_date: p.handoverDate,
      property_types: p.propertyTypes?.length ? p.propertyTypes.join(", ") : null,
      payment_plan: p.paymentPlan,
      description: description || null,
      short_description: p.shortDescription,
      nearby_landmarks: p.nearbyLandmarks,
      brochure_pdf: p.brochure,
      community_name: p.community,
      community_latitude: p.latitude != null ? String(p.latitude) : null,
      community_longitude: p.longitude != null ? String(p.longitude) : null,
    },
    gallery: (p.gallery ?? []).filter(Boolean).map((url, i) => ({ id: i + 1, image_path: sanityImage(url, 1600)! })),
    floorPlans: (p.floorPlans ?? []).map((plan, i) => ({
      id: i + 1,
      unit_type: plan.unitType,
      image: sanityImage(plan.image, 1200),
      pdf_file: plan.pdf,
      size_sqft: plan.sizeSqft != null ? String(plan.sizeSqft) : null,
    })),
    amenities: (p.amenities ?? []).filter(Boolean).map((name, i) => ({ id: i + 1, name, category: null, icon: null })),
    related: (p.related ?? []).map((r, i) => ({
      id: i + 1,
      title: r.title,
      slug: r.slug,
      main_image: sanityImage(r.image, 480, 320),
      price_from: r.priceFrom ? String(r.priceFrom) : null,
      price_currency: r.priceCurrency,
      community_name: r.community,
    })),
  };
}

/** Slugs for the sitemap. */
export function getOffPlanProjectSlugs(): Promise<{ slug: string; lastmod: string }[]> {
  return sanityQuery(`*[${ACTIVE}] | order(_updatedAt desc) {"slug": slug.current, "lastmod": _updatedAt}`, {}, []);
}
