import "server-only";
import { cache } from "react";
import { sanityQuery } from "@/lib/sanity";

// Port of BlogController (posts live in Sanity).

export const BLOG_PER_PAGE = 9;

// Fields shown on a blog card (list page and "related" section).
const CARD_FIELDS = `
        title,
        "slug": slug.current,
        excerpt,
        author,
        publishedAt,
        "category": category->{title, "slug": slug.current},
        "image": mainImage.asset->url,
        "imageAlt": mainImage.alt`;

const CARD = `{${CARD_FIELDS}}`;

export type BlogCard = {
  title: string;
  slug: string;
  excerpt?: string | null;
  author?: string | null;
  publishedAt: string;
  category?: { title?: string | null; slug?: string | null } | null;
  image?: string | null;
  imageAlt?: string | null;
};

export type BlogCategory = { title: string; slug: string };

export type BlogPost = BlogCard & {
  metaTitle?: string | null;
  metaDescription?: string | null;
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  body?: any[] | null;
  related?: BlogCard[] | null;
};

export const getBlogIndex = cache(async (page: number, category: string | null, search: string | null) => {
  const params: Record<string, unknown> = { start: (page - 1) * BLOG_PER_PAGE, end: page * BLOG_PER_PAGE };

  let filter = '_type == "post" && defined(slug.current) && publishedAt <= now()';
  if (category) {
    filter += " && category->slug.current == $category";
    params.category = category;
  }
  if (search) {
    filter += " && (title match $search || excerpt match $search)";
    params.search = `${search.trim()}*`;
  }

  const result = await sanityQuery<{ posts?: BlogCard[]; total?: number; categories?: BlogCategory[] }>(
    `{
                "posts": *[${filter}] | order(publishedAt desc) [$start...$end] ${CARD},
                "total": count(*[${filter}]),
                "categories": *[_type == "category" && defined(slug.current)] | order(order asc, title asc) {title, "slug": slug.current}
            }`,
    params,
    { posts: [], total: 0, categories: [] },
  );

  const total = result.total ?? 0;
  return {
    posts: result.posts ?? [],
    total,
    currentPage: page,
    lastPage: Math.max(1, Math.ceil(total / BLOG_PER_PAGE)),
    categories: result.categories ?? [],
  };
});

export const getBlogPost = cache((slug: string) =>
  sanityQuery<BlogPost | null>(
    `*[_type == "post" && slug.current == $slug && publishedAt <= now()][0]{
                ${CARD_FIELDS},
                metaTitle,
                metaDescription,
                body[]{..., _type == "image" => {..., "url": asset->url}},
                "related": *[_type == "post" && category._ref == ^.category._ref && _id != ^._id
                             && defined(slug.current) && publishedAt <= now()]
                           | order(publishedAt desc) [0...3] ${CARD}
            }`,
    { slug },
    null,
  ),
);

/** Carbon::parse($publishedAt)->format('d M Y') — the date as written in the timestamp (Carbon keeps its offset). */
export function publishedDate(value: string | null | undefined): string {
  const m = /^(\d{4})-(\d{2})-(\d{2})/.exec(value ?? "");
  if (!m) return "";
  const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  return `${m[3]} ${months[Number(m[2]) - 1]} ${m[1]}`;
}
