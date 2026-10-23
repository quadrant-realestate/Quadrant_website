import Link from "@/components/site/Link";
import { pageMetadata } from "@/lib/seo";
import { setting } from "@/lib/settings";
import { FallbackImg } from "../_home/FallbackImg";
import "./page.css";

// Cached for a minute, then rebuilt with the latest settings and content.
export const revalidate = 60;

export async function generateMetadata() {
  return pageMetadata({
    title: `Sell Your Property — ${await setting("site_name", "Quadrant Properties")}`,
    description:
      "Sell your property in Dubai with Quadrant Properties. Honest valuation, discreet marketing, and end-to-end support — advised, never pressured.",
    path: "/sell",
  });
}

const STEPS: [number: string, title: string, text: string][] = [
  [
    "01",
    "Property Valuation",
    "Your advisor visits the property and provides a thorough market analysis based on recent comparable transactions and current demand.",
  ],
  [
    "02",
    "Marketing",
    "Professional photography, targeted digital marketing, and placement across our private buyer network and key property portals.",
  ],
  [
    "03",
    "Offer Agreed",
    "Your advisor manages viewings with qualified buyers, negotiates on your behalf, and keeps you informed at every stage until the offer is agreed.",
  ],
  [
    "04",
    "Transfer of Ownership",
    "Your advisor coordinates all documentation and manages the final transfer of ownership at Dubai Land Department — calmly and without pressure.",
  ],
];

export default function SellPage() {
  return (
    <>
      {/* SECTION 1 — HERO */}
      <section className="qp-sell-hero">
        <FallbackImg
          className="qp-sell-hero-bg"
          src="/public/assets/images/sell-banner.jpg"
          alt="Sell your property with Quadrant Properties"
          fallbackSrc="/public/assets/img/placeholder.jpg"
        />
        <div className="container">
          <div className="qp-sell-hero-inner">
            <div className="qp-rule"></div>
            <h1>
              Sell your property
              <br />
              with us.
            </h1>
            <p>
              We combine local market knowledge with a discreet, advice-led approach - so your property reaches the
              right buyers, at the right time, without pressure or compromise.
            </p>
            <Link href="/contact">Request a Valuation</Link>
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
                    <span>Sell Your Property</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* SECTION 2 — STEP BY STEP */}
      <section className="qp-sell-steps">
        <div className="container">
          <div className="qp-sell-steps-heading">
            <div className="qp-rule"></div>
            <h2>Step by Step</h2>
          </div>

          <div className="qp-steps-grid">
            {STEPS.map(([number, title, text], i) => (
              <div className="qp-step-card" key={number}>
                <span className="qp-step-number">{number}</span>
                <div className="qp-step-icon">
                  {/* First 3 page images stayed eager (LazyLoadImages middleware): hero, icons 1 and 2 */}
                  <img
                    alt=""
                    {...(i >= 2 ? { loading: "lazy", decoding: "async" } : {})}
                    src={`/public/assets/img/${i + 1}-icon.png`}
                    width="35"
                    height="auto"
                  />
                </div>
                <h3>{title}</h3>
                <p>{text}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CLOSING ENQUIRY CTA */}
      <section className="qp-services-cta">
        <div className="container">
          <h2>Ready to understand your property&apos;s value?</h2>
          <p>
            Begin with an honest conversation — no obligation, no pressure. We&apos;ll tell you what your property is
            worth and what selling with us looks like.
          </p>
          <Link href="/contact">Speak with an Advisor</Link>
        </div>
      </section>
    </>
  );
}
