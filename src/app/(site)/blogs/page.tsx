import Link from "@/components/site/Link";
import { param, queryString, truthy, urlencode, type SearchParams } from "../investments/_components/helpers";
import { Pagination } from "@/components/site/Pagination";
import { strLimit } from "@/lib/format";
import { getBlogIndex, publishedDate } from "@/lib/queries/blogs";
import { sanityImage } from "@/lib/sanity";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { siteUrl } from "@/lib/url";
import "./page.css";

type Props = { searchParams: Promise<SearchParams> };

const DESCRIPTION =
  "Clear thinking on Dubai property — the off-plan market, the communities, and how to stand on solid ground.";

export async function generateMetadata() {
  const settings = await getSettings();
  return pageMetadata({
    title: `Blogs — ${pick(settings, "site_name", "Quadrant Properties") ?? ""}`,
    description: DESCRIPTION,
    path: "/blogs",
  });
}

export default async function BlogsPage({ searchParams }: Props) {
  const sp = await searchParams;
  const category = param(sp, "category");
  const search = param(sp, "search");
  // (int) $request->query('page', 1), at least 1
  const page = Math.max(1, parseInt(param(sp, "page") ?? "1", 10) || 0);

  const { posts, currentPage, lastPage, categories } = await getBlogIndex(page, category, search);
  const fallbackImage = siteUrl("/public/assets/images/og-image.jpg");

  return (
    <>
      {/* PAGE BANNER */}
      <section className="qp-blogs-banner">
        <div className="container">
          <h1>Blogs</h1>
          <p>{DESCRIPTION}</p>

          {categories.length > 0 && (
            <div className="qp-blogs-filters">
              <Link href="/blogs" className={!truthy(category) ? "active" : ""}>
                All
              </Link>
              {categories.map((c) => (
                <Link
                  href={`/blogs?category=${urlencode(c.slug)}`}
                  className={category === c.slug ? "active" : ""}
                  key={c.slug}
                >
                  {c.title}
                </Link>
              ))}
            </div>
          )}

          <form className="qp-blogs-search" method="GET" action="/blogs">
            {truthy(category) && <input type="hidden" name="category" value={category ?? ""} />}
            <input
              type="search"
              name="search"
              defaultValue={search ?? ""}
              placeholder="Search articles"
              aria-label="Search articles"
            />
            <button type="submit">Search</button>
          </form>
        </div>
      </section>

      {/* BREADCRUMB */}
      <section className="breadcrumb-sec">
        <div className="container">
          <div className="row">
            <div className="col-12">
              <div className="bread-container">
                <ul>
                  <li>
                    <Link href="/">Home</Link>
                  </li>
                  <li>
                    <span>Blogs</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ARTICLE GRID */}
      <section style={{ padding: "56px 0" }}>
        <div className="container">
          <div className="qp-blogs-grid">
            {posts.length > 0 ? (
              posts.map((post) => (
                <Link href={`/blogs/${post.slug}`} className="qp-blog-card" key={post.slug}>
                  <div className="qp-blog-img">
                    <img
                      src={sanityImage(post.image, 640, 380) ?? fallbackImage}
                      alt={post.imageAlt ?? post.title}
                      loading="lazy"
                    />
                  </div>
                  <div className="qp-blog-body">
                    {truthy(post.category?.title) && <p className="qp-blog-cat">{post.category?.title}</p>}
                    <h3>{post.title}</h3>
                    {truthy(post.excerpt) && <p className="qp-blog-excerpt">{strLimit(post.excerpt, 110)}</p>}
                    <p className="qp-blog-date">{publishedDate(post.publishedAt)}</p>
                  </div>
                </Link>
              ))
            ) : (
              <p style={{ gridColumn: "1/-1", textAlign: "center", color: "#5B6678" }}>
                {truthy(search) || truthy(category) ? "No articles match your search." : "No articles published yet."}
              </p>
            )}
          </div>

          {/* PAGINATION */}
          <Pagination
            currentPage={currentPage}
            lastPage={lastPage}
            path="/blogs"
            query={queryString(sp)}
            style={{ marginTop: 40 }}
          />
        </div>
      </section>
    </>
  );
}
