import Link from "@/components/site/Link";
import { BodyClass } from "@/components/site/BodyClass";
import { numberFormat } from "@/lib/format";
import {
  getFeaturedDevelopments,
  getPriorityCommunityRecords,
  PRIORITY_COMMUNITIES,
} from "@/lib/queries/home";
import { pageMetadata } from "@/lib/seo";
import { setting } from "@/lib/settings";
import { asset } from "@/lib/url";
import { FallbackImg } from "./_home/FallbackImg";
import { HeroVideo } from "./_home/HeroVideo";
import "./_home/home.css";

// Rendered on every request, like the Laravel page (settings and database content are always current).
export const dynamic = "force-dynamic";

export async function generateMetadata() {
  return pageMetadata({
    title: `${await setting("site_name", "Quadrant Properties")} — Luxury & Off-Plan Real Estate in Dubai`,
    description:
      "Dubai's finest off-plan and luxury residences — villas, penthouses and apartments in Palm Jumeirah, Downtown and Dubai Hills. Advised, never sold.",
    path: "/",
  });
}

const STATUS_LABELS: Record<string, string> = {
  upcoming: "New Launch",
  launched: "New Launch",
  under_construction: "Under Construction",
  completed: "Handover Ready",
};

/** Carbon::parse($date)->format('Y') */
function year(value: string): string {
  const m = /^\s*(\d{4})-\d{1,2}-\d{1,2}/.exec(value);
  if (m) return m[1];
  const d = new Date(value);
  return Number.isNaN(d.getTime()) ? value : String(d.getFullYear());
}

const why: React.CSSProperties = {
  fontFamily: "'Cormorant Garamond',serif",
  fontSize: 22,
  fontWeight: 600,
  color: "#11203A",
};

export default async function HomePage() {
  const [featuredDevelopments, priorityCommunityRecords] = await Promise.all([
    getFeaturedDevelopments(),
    getPriorityCommunityRecords(),
  ]);

  // Build a lookup of slug => image from any matching DB communities.
  const communityImages = new Map(priorityCommunityRecords.map((r) => [r.slug, r]));

  // The Laravel LazyLoadImages middleware left the first 3 images of the page eager
  // (the hero fallback image counts as the first) and lazy-loaded the rest.
  let imgCount = 1;
  const lazy = () =>
    ++imgCount > 3 ? ({ loading: "lazy", decoding: "async" } as const) : ({} as Record<string, never>);

  return (
    <>
      <BodyClass name="qp-home-page" />
      <div className="qp-home">
        {/* SECTION 1 — HERO (no search widget — brief: headline, sub-line, two buttons only) */}
        <section className="qp-hero">
          {/* Hero video background — autoplay, loop, muted (required for autoplay), no controls */}
          <HeroVideo />
          <div className="qp-container qp-hero-inner">
            <div className="qp-hero-text">
              <h1>Know where you stand.</h1>
              <div className="qp-eyebrow-rule"></div>
              <p>Dubai&apos;s finest off-plan and luxury residences — advised, never sold.</p>
              <div className="qp-hero-btns">
                <Link href="/buy" className="qp-btn qp-btn-navy">
                  Explore Off-Plan Projects
                </Link>
                <Link href="/contact" className="qp-btn qp-btn-outline-light">
                  Speak with an Advisor
                </Link>
              </div>
            </div>
          </div>
        </section>

        {/* SECTION 2 — POSITIONING STATEMENT */}
        <section className="qp-section" style={{ padding: "48px 0", borderBottom: "1px solid #eef1f5" }}>
          <div className="qp-container" style={{ maxWidth: 760, textAlign: "center" }}>
            <p
              style={{
                fontFamily: "'Cormorant Garamond',serif",
                fontSize: 20,
                lineHeight: 1.7,
                color: "#1B2538",
                fontWeight: 400,
                margin: 0,
              }}
            >
              Quadrant Properties is a luxury real estate advisory built on two ideas: that the right home is the fixed
              point of a life, and that you deserve counsel you can trust to find it. We specialise in Dubai off-plan —
              the landmark developments shaping the city&apos;s future — and we advise rather than sell.
            </p>
          </div>
        </section>

        {/* SECTION 3 — FEATURED OFF-PLAN PROJECTS (centrepiece, pulls from developments) */}
        <section className="qp-section">
          <div className="qp-container">
            <div className="qp-listings-head">
              <h2>Featured Off-Plan Projects</h2>
              <Link href="/buy">View all projects →</Link>
            </div>
            <p className="qp-listings-sub">Hand-selected launches and landmark developments across Dubai.</p>

            <div className="qp-listings-grid">
              {featuredDevelopments.length > 0 ? (
                featuredDevelopments.map((development) => (
                  <Link
                    href={`/buy/${encodeURIComponent(development.slug ?? "")}`}
                    className="qp-listing-card"
                    key={development.id}
                  >
                    <div className="qp-listing-img">
                      {development.status && (
                        <span className="qp-listing-badge">{STATUS_LABELS[development.status] ?? "New Launch"}</span>
                      )}
                      {development.main_image ? (
                        <FallbackImg
                          {...lazy()}
                          src={asset(development.main_image)}
                          alt={development.title ?? ""}
                          fallbackSrc="/public/assets/img/placeholder.jpg"
                        />
                      ) : (
                        <img {...lazy()} src="/public/assets/img/placeholder.jpg" alt={development.title ?? ""} />
                      )}
                    </div>
                    <div className="qp-listing-body">
                      <h4>{development.title}</h4>
                      {development.community_name && <p className="qp-loc">{development.community_name}</p>}
                      {development.developer_name && <p className="qp-developer">{development.developer_name}</p>}
                      {/* PHP read DECIMALs as strings, so "0.0000" was truthy: only NULL hid the price */}
                      {development.price_from !== null ? (
                        <p className="qp-price">
                          From {development.price_currency ?? "AED"} {numberFormat(development.price_from)}
                        </p>
                      ) : null}
                      <div className="qp-stat-row">
                        {development.bedroom_range && <span>{development.bedroom_range}</span>}
                        {development.handover_date && <span>{year(development.handover_date)}</span>}
                      </div>
                    </div>
                  </Link>
                ))
              ) : (
                <p style={{ gridColumn: "1/-1", textAlign: "center", color: "var(--qp-text-muted)" }}>
                  No featured projects at the moment.
                </p>
              )}
            </div>
          </div>
        </section>

        {/* SECTION 4 — WHY QUADRANT (The Four Bearings) */}
        <section className="qp-section" style={{ paddingTop: 10 }}>
          <div className="qp-container">
            <div className="qp-heading">
              <div className="qp-eyebrow-rule"></div>
              <h2>Why Quadrant</h2>
            </div>

            <div className="qp-why">
              <div>
                <div className="qp-why-icon" style={{ borderColor: "#C8A965" }}>
                  <span style={why}>N</span>
                </div>
                <h4>Counsel</h4>
                <p>We advise, we do not sell. Honest guidance, even when it costs us the deal.</p>
              </div>

              <div>
                <div className="qp-why-icon">
                  <span style={why}>E</span>
                </div>
                <h4>Craft</h4>
                <p>We represent only property worthy of our name. Quality is the entry requirement.</p>
              </div>

              <div>
                <div className="qp-why-icon">
                  <span style={why}>S</span>
                </div>
                <h4>Custody</h4>
                <p>Your interest is held above ours — long-term, confidential, protected.</p>
              </div>

              <div>
                <div className="qp-why-icon" style={{ borderColor: "#C8A965", background: "#fffdf5" }}>
                  <span style={{ ...why, color: "#C8A965" }}>W</span>
                </div>
                <h4 style={{ color: "#C8A965" }}>Consequence</h4>
                <p>
                  We pledge a portion of our profit, every year, to building permanent homes for underprivileged
                  families around the world.
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* SECTION 5 — FEATURED COMMUNITIES (7 priority, fixed order) */}
        <section className="qp-section qp-market" style={{ background: "#fff" }}>
          <div className="qp-container">
            <div className="qp-heading">
              <div className="qp-eyebrow-rule"></div>
              <h2>Featured Communities</h2>
            </div>

            <div className="qp-communities-grid">
              {PRIORITY_COMMUNITIES.map(([slug, name]) => {
                const record = communityImages.get(slug);
                return (
                  <Link href={`/communities/${slug}`} className="qp-community-tile" key={slug}>
                    {record && record.image ? (
                      <img {...lazy()} src={asset(record.image)} alt={name} />
                    ) : (
                      <img {...lazy()} src="/public/assets/img/placeholder.jpg" alt={name} />
                    )}
                    <span>{name}</span>
                  </Link>
                );
              })}
            </div>
          </div>
        </section>

        {/* SECTION 6 — SERVICES SNAPSHOT (titles only — full detail on Services page) */}
        <section className="qp-section qp-market">
          <div className="qp-container">
            <div className="qp-heading">
              <div className="qp-eyebrow-rule"></div>
              <h2>How We Work</h2>
            </div>

            <div className="qp-services-grid">
              <div className="qp-service-card">
                <h4>Off-Plan Advisory</h4>
              </div>
              <div className="qp-service-card">
                <h4>Luxury Acquisition</h4>
              </div>
              <div className="qp-service-card">
                <h4>Investment Guidance</h4>
              </div>
              <div className="qp-service-card">
                <h4>Portfolio Stewardship</h4>
              </div>
            </div>
          </div>
        </section>

        {/* SECTION 8 — OUR STORY TEASER */}
        <section style={{ background: "#11203A", padding: "60px 0", textAlign: "center" }}>
          <div className="qp-container">
            <p
              style={{
                fontFamily: "'Cormorant Garamond',serif",
                fontSize: 22,
                color: "#fff",
                fontStyle: "italic",
                margin: "0 0 24px",
                maxWidth: 680,
                marginLeft: "auto",
                marginRight: "auto",
                lineHeight: 1.6,
              }}
            >
              A quadrant is the instrument explorers used to find where they stood. We help you do the same.
            </p>
            <Link href="/about" style={{ fontSize: 13, letterSpacing: ".5px", color: "#C8A965", fontWeight: 600 }}>
              → Our Story
            </Link>
          </div>
        </section>

        {/* Sections 9 (Insights), Market Intelligence, Developer Partners and Founder were commented out in the Blade view. */}

        {/* SECTION 10 — ENQUIRY / LEAD BAND (before footer) */}
        <section style={{ background: "#F4F5F7", padding: "64px 0", borderTop: "2px solid #C8A965" }}>
          <div className="qp-container" style={{ textAlign: "center" }}>
            <h2 style={{ fontFamily: "'Cormorant Garamond',serif", fontSize: 28, color: "#11203A", margin: "0 0 12px" }}>
              Considering a move, or an investment?
            </h2>
            <p style={{ color: "#5B6678", fontSize: 15, margin: "0 0 28px" }}>Begin with a conversation — not a pitch.</p>
            <Link href="/contact" className="qp-btn qp-btn-navy">
              Speak with an Advisor
            </Link>
          </div>
        </section>
      </div>
    </>
  );
}
