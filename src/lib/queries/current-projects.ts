import "server-only";
import { sanityQuery } from "@/lib/sanity";

// Projects the team is working on now (they live in Sanity, not in the developments table).

export type CurrentProject = {
  _id: string;
  title: string;
  status: "pending" | "processing";
  statusNote?: string | null;
  community?: string | null;
  developer?: string | null;
  priceFrom?: number | null;
  bedroomRange?: string | null;
  handover?: string | null;
  image?: string | null;
  imageAlt?: string | null;
};

/** How many current projects the home page shows; the Buy page shows them all. */
export const HOME_CURRENT_PROJECTS = 4;

export function getCurrentProjects(): Promise<CurrentProject[]> {
  return sanityQuery<CurrentProject[]>(
    `*[_type == "currentProject" && defined(title) && status in ["pending", "processing"]]
        | order(coalesce(displayOrder, 9999) asc, _createdAt desc) {
        _id,
        title,
        status,
        statusNote,
        community,
        developer,
        priceFrom,
        bedroomRange,
        handover,
        "image": mainImage.asset->url,
        "imageAlt": mainImage.alt
      }`,
    {},
    [],
  );
}
