import Link from "@/components/site/Link";
import { notFound } from "next/navigation";
import { JsonLd } from "@/components/site/JsonLd";
import { escapeHtml, numberFormat, strLimit, stripTags } from "@/lib/format";
import {
  getDevelopmentAmenities,
  getDevelopmentBySlug,
  getDevelopmentFloorPlans,
  getDevelopmentGallery,
  getRelatedDevelopments,
} from "@/lib/queries/developments";
import { fitDescription, pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { formTimestamp } from "@/lib/spam";
import { asset, siteUrl } from "@/lib/url";
import { lines, publicPath, truthy } from "../_components/php";
import { FloorPlans } from "./_components/FloorPlans";
import { HeroGallery } from "./_components/HeroGallery";
import { InterestForm } from "./_components/InterestForm";
import BrandedResidencePage, { generateMetadata as residenceMetadata } from "../../branded-residences/[slug]/page";
import { getResidence } from "@/lib/queries/branded-residences";
import "./page.css";

// website/developments/show.blade.php — DevelopmentFrontController@show
//
// When no development matches the slug, Laravel fell back to an active branded residence with
// that slug (rendering website.branded-residences.show at this URL), otherwise 404.

export const revalidate = 60;

// No pages are built in advance; each one is cached the first time it is visited.
export function generateStaticParams() {
  return [];
}

type Props = { params: Promise<{ slug: string }> };

const STATUS_LABELS: Record<string, string> = {
  upcoming: "New Launch",
  launched: "New Launch",
  under_construction: "Under Construction",
  completed: "Nearing Handover",
};

const PLACEHOLDER = "/public/assets/img/placeholder.jpg";

export async function generateMetadata({ params }: Props) {
  const { slug } = await params;
  const [development, settings] = await Promise.all([getDevelopmentBySlug(slug), getSettings()]);
  if (!development) {
    if (await getResidence(slug)) {
      const meta = await residenceMetadata({ params });
      return { ...meta, alternates: { canonical: siteUrl(`/buy/${slug}`) } };
    }
    return pageMetadata({ path: `/buy/${slug}` });
  }

  return pageMetadata({
    title: `${development.title} — ${pick(settings, "site_name", "Quadrant Properties")}`,
    description: fitDescription(
      development.short_description,
      `Discover ${development.title} in ${development.community_name ?? "Dubai"}. ${pick(settings, "site_name") ?? ""} — Off-Plan Real Estate Advisory.`,
    ),
    path: `/buy/${slug}`,
    ogImage: siteUrl(publicPath(development.main_image)),
  });
}

/** "2028-09-30" → "Q3 2028" (the Laravel page printed a literal "QQ 2028"). */
function handoverQuarter(date: string): string {
  const m = /^(\d{4})-(\d{2})/.exec(date);
  if (m) return `Q${Math.ceil(Number(m[2]) / 3)} ${m[1]}`;
  const d = new Date(date);
  return `Q${Math.floor(d.getMonth() / 3) + 1} ${d.getFullYear()}`;
}

/** preg_match('/(\d+%)/') + str_replace — "20% On Booking" → ["On Booking", "20%"] */
function paymentRow(line: string): { label: string; pct: string } {
  const m = /(\d+%)/.exec(line);
  const pct = m ? m[1] : "—";
  const label = line.split(pct).join("").replace(/^[ \t\n\r\0\x0B]+|[ \t\n\r\0\x0B]+$/g, "");
  return { label: truthy(label) ? label : line, pct };
}

export default async function DevelopmentShow({ params }: Props) {
  const { slug } = await params;
  const development = await getDevelopmentBySlug(slug);
  if (!development) {
    if (await getResidence(slug)) return <BrandedResidencePage params={params} />;
    notFound();
  }

  const [gallery, floorPlans, amenities, relatedDevelopments, settings] = await Promise.all([
    getDevelopmentGallery(development.id),
    getDevelopmentFloorPlans(development.id),
    getDevelopmentAmenities(development.id),
    getRelatedDevelopments(development),
    getSettings(),
  ]);
  const s = (key: string) => pick(settings, key);

  // 1. Hero images
  const heroImages: string[] = [];
  if (truthy(development.main_image)) heroImages.push(development.main_image);
  for (const img of gallery) heroImages.push(img.image_path);
  if (heroImages.length === 0) heroImages.push("assets/img/placeholder.jpg");

  // Payment plan highlight — first line only if it isn't blank (array_filter keeps keys)
  const ppFirst = development.payment_plan ? lines(development.payment_plan)[0] : undefined;
  const ppHighlight = ppFirst && ppFirst.index === 0 ? ppFirst.line : "—";

  const landmarkLines = development.nearby_landmarks ? lines(development.nearby_landmarks).map((l) => l.line) : [];

  // Images after the first 3 of the page get loading="lazy" (LazyLoadImages middleware)
  const eagerLeft = Math.max(0, 3 - heroImages.length);
  const relatedOffset = heroImages.length + floorPlans.length;

  const whatsapp = s("whatsapp_number");
  const phone = s("contact_phone");

  const schema = {
    "@context": "https://schema.org",
    "@type": "Residence",
    name: development.title,
    description: strLimit(stripTags(development.short_description ?? development.description), 200),
    url: siteUrl(`/buy/${slug}`),
    image: siteUrl(publicPath(development.main_image)),
    address: {
      "@type": "PostalAddress",
      addressLocality: development.community_name ?? "Dubai",
      addressCountry: "AE",
    },
    offers: {
      "@type": "Offer",
      price: development.price_from ?? "",
      priceCurrency: development.price_currency ?? "AED",
    },
  };

  // Overview: admin HTML printed straight into the section, so the whole section body is HTML.
  const overviewHtml =
    "<h2>Overview</h2>" +
    (truthy(development.description)
      ? development.description
      : `<p>${escapeHtml(development.short_description ?? "")}</p>`) +
    (truthy(development.community_name)
      ? `<p style="color:#5B6678; font-size:13px;"><strong style="color:#1B2538;">Community:</strong> ${escapeHtml(development.community_name)}</p>`
      : "");

  return (
    <>
      <JsonLd data={schema} />

      {/* 1. HERO GALLERY */}
      <HeroGallery images={heroImages.map((img) => asset(img))} alt={development.title}>
        <div className="qp-pd-hero-overlay">
          <div className="qp-pd-main">
            {truthy(development.status) && (
              <span className="qp-pd-status-tag">{STATUS_LABELS[development.status] ?? "New Launch"}</span>
            )}
            <h1>{development.title}</h1>
          </div>
        </div>
      </HeroGallery>

      {/* BREADCRUMB */}
      <div className="qp-pd-breadcrumb">
        <div className="qp-pd-main">
          <Link href="/">Home</Link> / <Link href="/buy">Buy</Link> / <a style={{ color: "#11203A" }}>{development.title}</a>
        </div>
      </div>

      {/* 2. KEY FACTS STRIP */}
      <section className="qp-pd-facts">
        <div className="qp-pd-fact">
          <p className="qp-fact-label">Starting Price</p>
          <p className="qp-fact-value">
            {truthy(development.price_from)
              ? `From ${development.price_currency ?? "AED"} ${numberFormat(development.price_from)}`
              : "On Request"}
          </p>
        </div>
        <div className="qp-pd-fact">
          <p className="qp-fact-label">Handover</p>
          <p className="qp-fact-value">
            {truthy(development.handover_date) ? handoverQuarter(development.handover_date) : "—"}
          </p>
        </div>
        <div className="qp-pd-fact">
          <p className="qp-fact-label">Payment Plan</p>
          <p className="qp-fact-value">{ppHighlight}</p>
        </div>
        <div className="qp-pd-fact">
          <p className="qp-fact-label">Unit Types</p>
          <p className="qp-fact-value">{development.property_types ?? "—"}</p>
        </div>
      </section>

      <div className="qp-pd-main">
        <div className="qp-pd-grid">
          {/* LEFT COLUMN */}
          <div>
            {/* 3. OVERVIEW */}
            {(truthy(development.description) || truthy(development.short_description)) && (
              <div className="qp-pd-section" dangerouslySetInnerHTML={{ __html: overviewHtml }} />
            )}

            {/* 4. HIGHLIGHTS / AMENITIES */}
            {amenities.length > 0 && (
              <div className="qp-pd-section">
                <h2>Highlights &amp; Amenities</h2>
                <div className="qp-amenity-grid">
                  {amenities.map((amenity) => (
                    <div className="qp-amenity-item" key={amenity.id}>
                      <svg fill="none" viewBox="0 0 24 24" strokeWidth="1.5">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                      </svg>
                      {amenity.name}
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* 5. FLOOR PLANS (tabbed) */}
            {floorPlans.length > 0 && <FloorPlans plans={floorPlans} eager={eagerLeft} />}

            {/* 6. PAYMENT PLAN TABLE */}
            {truthy(development.payment_plan) && (
              <div className="qp-pd-section">
                <h2>Payment Plan</h2>
                <table className="qp-pp-table">
                  <thead>
                    <tr>
                      <th>Milestone</th>
                      <th>%</th>
                    </tr>
                  </thead>
                  <tbody>
                    {lines(development.payment_plan).map(({ index, line }) => {
                      const { label, pct } = paymentRow(line);
                      return (
                        <tr key={index}>
                          <td>{label}</td>
                          <td>{pct}</td>
                        </tr>
                      );
                    })}
                  </tbody>
                </table>
              </div>
            )}
          </div>

          {/* RIGHT COLUMN — SIDEBAR */}
          <div className="qp-pd-sidebar">
            {/* 9. REGISTER INTEREST FORM */}
            <InterestForm developmentId={development.id} title={development.title} ts={formTimestamp()} />

            <div className="qp-pd-contact-card">
              {truthy(whatsapp) && (
                <a href={`https://wa.me/${whatsapp.replace(/[^0-9]/g, "")}`} target="_blank">
                  WhatsApp an Advisor
                </a>
              )}
              {truthy(phone) && <a href={`tel:${phone}`}>{phone}</a>}
              {truthy(development.brochure_pdf) && (
                <a href={asset(development.brochure_pdf)} target="_blank">
                  Download the Brochure
                </a>
              )}
            </div>
          </div>
        </div>

        {/* 7. LOCATION */}
        <div className="qp-pd-section">
          <h2>Location</h2>
          <div
            style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 28, alignItems: "start" }}
            className="qp-location-grid"
          >
            {/* Left: Map */}
            <div>
              {truthy(development.community_latitude) && truthy(development.community_longitude) ? (
                <iframe
                  width="100%"
                  height="340"
                  frameBorder="0"
                  style={{ border: 0, borderRadius: 4, display: "block" }}
                  src={`https://www.google.com/maps?q=${development.community_latitude},${development.community_longitude}&hl=en&z=14&output=embed`}
                  title={`${development.title} location map`}
                  loading="lazy"
                  allowFullScreen
                />
              ) : (
                <div
                  style={{
                    background: "#F4F5F7",
                    height: 340,
                    borderRadius: 4,
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                  }}
                >
                  <p style={{ color: "#8a93a3", fontSize: 13, margin: 0 }}>Map coming soon</p>
                </div>
              )}
            </div>

            {/* Right: Community + Landmarks */}
            <div>
              {truthy(development.community_name) && (
                <p
                  style={{
                    fontFamily: "'Cormorant Garamond',serif",
                    fontSize: 18,
                    color: "#11203A",
                    fontWeight: 600,
                    margin: "0 0 16px",
                  }}
                >
                  {development.community_name}, Dubai
                </p>
              )}

              {truthy(development.nearby_landmarks) ? (
                landmarkLines.length > 1 ? (
                  <ul className="qp-landmarks" style={{ margin: 0 }}>
                    {landmarkLines.map((landmark, i) => {
                      const parts = landmark.split(" — ");
                      return (
                        <li key={i}>
                          <span>{parts[0]}</span>
                          <span style={{ color: "#5B6678" }}>{parts[1] ?? ""}</span>
                        </li>
                      );
                    })}
                  </ul>
                ) : (
                  <p style={{ fontSize: 14, color: "#5B6678", lineHeight: 1.8, margin: 0 }}>
                    {development.nearby_landmarks}
                  </p>
                )
              ) : (
                <p style={{ fontSize: 13, color: "#8a93a3", margin: 0 }}>Nearby landmarks will be added shortly.</p>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* 10. RELATED PROJECTS */}
      {relatedDevelopments.length > 0 && (
        <section style={{ padding: "0 0 56px" }}>
          <div className="qp-pd-main">
            <h2 style={{ fontFamily: "'Cormorant Garamond',serif", fontSize: 22, color: "#11203A", margin: "0 0 26px" }}>
              Related Projects
            </h2>
            <div className="qp-related-grid">
              {relatedDevelopments.map((related, i) => (
                <Link
                  href={`/buy/${related.slug}`}
                  key={related.id}
                  style={{
                    display: "block",
                    background: "#fff",
                    borderRadius: 4,
                    overflow: "hidden",
                    boxShadow: "0 2px 14px rgba(11,29,58,.07)",
                    textDecoration: "none",
                  }}
                >
                  <div style={{ height: 160, overflow: "hidden" }}>
                    <img
                      {...(relatedOffset + i >= 3 ? ({ loading: "lazy", decoding: "async" } as const) : {})}
                      src={truthy(related.main_image) ? asset(related.main_image) : PLACEHOLDER}
                      alt={related.title}
                      style={{ width: "100%", height: "100%", objectFit: "cover" }}
                    />
                  </div>
                  <div style={{ padding: "16px 18px" }}>
                    <h4 style={{ fontFamily: "'Cormorant Garamond',serif", fontSize: 16, color: "#1B2538", margin: "0 0 6px" }}>
                      {related.title}
                    </h4>
                    {truthy(related.community_name) && (
                      <p style={{ fontSize: 12, color: "#5B6678", margin: 0 }}>{related.community_name}</p>
                    )}
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
