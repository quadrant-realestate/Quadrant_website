import Link from "@/components/site/Link";
import { pageMetadata } from "@/lib/seo";
import { setting } from "@/lib/settings";
import "./page.css";

// Rendered on every request, like the Laravel page (settings and database content are always current).
export const dynamic = "force-dynamic";

export async function generateMetadata() {
  return pageMetadata({
    title: `How We Work — ${await setting("site_name", "Quadrant Properties")}`,
    description:
      "One standard of care, across everything we do: advice first, always. Off-Plan Advisory, Luxury Acquisition, Investment Guidance, Portfolio Stewardship.",
    path: "/services",
  });
}

const SERVICES: [number: string, title: string, text: string][] = [
  [
    "01",
    "Off-Plan Advisory",
    "Independent guidance on Dubai's off-plan market — from launch access and developer assessment to payment plans and handover timelines. We help you buy into the right project, at the right time, for the right reasons.",
  ],
  [
    "02",
    "Luxury Acquisition",
    "Sourcing and securing high-end residences with discretion. We represent your interest through negotiation, due diligence, and closing — calmly and without pressure.",
  ],
  [
    "03",
    "Investment Guidance",
    "Data-led advice for buyers thinking in years, not months. We assess fundamentals, location and timing so your capital is placed with clarity.",
  ],
  [
    "04",
    "Portfolio Stewardship",
    "Our relationship continues after the keys change hands — ongoing advice on holding, leasing, and growing a property portfolio over time.",
  ],
];

export default function ServicesPage() {
  return (
    <>
      {/* PAGE HERO */}
      <section className="qp-services-banner">
        <div className="container">
          <h1>How We Work</h1>
          <p>One standard of care, across everything we do: advice first, always.</p>
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
                    <span>Services</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* FOUR SERVICE BLOCKS */}
      <section>
        <div className="qp-services-list">
          {SERVICES.map(([number, title, text]) => (
            <div className="qp-service-block" key={number}>
              <div className="qp-service-number">{number}</div>
              <div>
                <h2>{title}</h2>
                <p>{text}</p>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* CLOSING ENQUIRY CTA */}
      <section className="qp-services-cta">
        <div className="container">
          <h2>Considering a move, or an investment?</h2>
          <p>Begin with a conversation — not a pitch.</p>
          <Link href="/contact">Speak with an Advisor</Link>
        </div>
      </section>
    </>
  );
}
