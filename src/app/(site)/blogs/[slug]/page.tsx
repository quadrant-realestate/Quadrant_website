import Link from "@/components/site/Link";
import { notFound } from "next/navigation";
import { truthy } from "../../investments/_components/helpers";
import { JsonLd } from "@/components/site/JsonLd";
import { strLimit, stripTags } from "@/lib/format";
import { renderPortableText } from "@/lib/portable-text";
import { getBlogPost, publishedDate } from "@/lib/queries/blogs";
import { sanityImage } from "@/lib/sanity";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { siteUrl } from "@/lib/url";
import "./page.css";

type Props = { params: Promise<{ slug: string }> };

// No pages are built in advance; each one is cached the first time it is visited.
export function generateStaticParams() {
  return [];
}

export async function generateMetadata({ params }: Props) {
  const { slug } = await params;
  const post = await getBlogPost(slug);
  if (!post) return {};
  const settings = await getSettings();
  const bodyHtml = renderPortableText(post.body ?? []);
  return pageMetadata({
    title: `${post.metaTitle || post.title} — ${pick(settings, "site_name", "Quadrant Properties") ?? ""}`,
    description: post.metaDescription || strLimit(post.excerpt ?? stripTags(bodyHtml), 160),
    path: `/blogs/${slug}`,
    ogImage: sanityImage(post.image, 1200, 630),
  });
}

export default async function BlogPostPage({ params }: Props) {
  const { slug } = await params;
  const post = await getBlogPost(slug);
  if (!post) notFound();

  const settings = await getSettings();
  const bodyHtml = renderPortableText(post.body ?? []);
  const fallbackImage = siteUrl("/public/assets/images/og-image.jpg");
  const heroImage = sanityImage(post.image, 1920, 800) ?? fallbackImage;

  const schema = {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    headline: post.title,
    description: post.excerpt ?? "",
    image: heroImage,
    datePublished: post.publishedAt,
    author: { "@type": "Organization", name: post.author ?? pick(settings, "site_name", "Quadrant Properties") },
    mainEntityOfPage: siteUrl(`/blogs/${slug}`),
  };

  return (
    <>
      <JsonLd data={schema} />

      {/* ARTICLE HERO */}
      <section className="qp-article-hero">
        <img src={heroImage} alt={post.imageAlt ?? post.title} />
        <div className="qp-article-meta">
          <div className="container">
            {truthy(post.category?.title) && <p className="qp-cat">{post.category?.title}</p>}
            <h1>{post.title}</h1>
            <p className="qp-byline">
              {truthy(post.author) && <>{post.author} &middot; </>}
              {publishedDate(post.publishedAt)}
            </p>
          </div>
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
                    <Link href="/blogs">Blogs</Link>
                  </li>
                  <li>
                    <span>{post.title}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ARTICLE BODY */}
      <article className="qp-article-body" dangerouslySetInnerHTML={{ __html: bodyHtml }} />

      {/* ENQUIRY PROMPT */}
      <section style={{ background: "#F4F5F7", padding: "48px 0", borderTop: "2px solid #C8A965", textAlign: "center" }}>
        <div className="container">
          <h2
            style={{
              fontFamily: "'Cormorant Garamond',serif",
              fontSize: 24,
              color: "#11203A",
              margin: "0 0 10px",
            }}
          >
            Considering a move, or an investment?
          </h2>
          <p style={{ color: "#5B6678", fontSize: 14, margin: "0 0 22px" }}>Begin with a conversation — not a pitch.</p>
          <Link
            href="/contact"
            style={{
              display: "inline-block",
              background: "#11203A",
              color: "#fff",
              padding: "12px 26px",
              fontSize: 13,
              letterSpacing: ".5px",
              textTransform: "uppercase",
              fontWeight: 600,
              borderRadius: 2,
            }}
          >
            Speak with an Advisor
          </Link>
        </div>
      </section>

      {/* RELATED ARTICLES */}
      {post.related && post.related.length > 0 && (
        <section style={{ padding: "56px 0" }}>
          <div className="container">
            <h2
              style={{
                fontFamily: "'Cormorant Garamond',serif",
                fontSize: 20,
                color: "#11203A",
                textAlign: "center",
                margin: "0 0 30px",
              }}
            >
              Related Articles
            </h2>
            <div className="qp-related-grid">
              {post.related.map((related) => (
                <Link href={`/blogs/${related.slug}`} className="qp-related-card" key={related.slug}>
                  <div className="qp-related-img">
                    <img
                      src={sanityImage(related.image, 520, 300) ?? fallbackImage}
                      alt={related.imageAlt ?? related.title}
                      loading="lazy"
                    />
                  </div>
                  <div className="qp-related-body">
                    <h4>{related.title}</h4>
                  </div>
                </Link>
              ))}
            </div>
          </div>
        </section>
      )}
    </>
  );
}
