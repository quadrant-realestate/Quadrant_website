import Link from "@/components/site/Link";
import { pageMetadata } from "@/lib/seo";
import { setting } from "@/lib/settings";
import "./page.css";

// Cached for a minute, then rebuilt with the latest settings and content.
export const revalidate = 60;

export async function generateMetadata() {
  return pageMetadata({
    title: `Building Homes Worldwide — ${await setting("site_name", "Quadrant Properties")}`,
    description:
      "Quadrant gives 10% of its profit, every quarter, to building permanent homes for underprivileged families around the world — today in India, Sudan and Yemen.",
    path: "/giving",
  });
}

export default function GivingPage() {
  return (
    <>
      {/* HERO */}
      <section className="qp-giving-hero">
        <img src="/public/assets/images/quadrant-hero-dubai.jpg" alt="Building homes worldwide" />
        <div className="qp-giving-hero-inner">
          <h1>Building Homes Worldwide</h1>
          <p>Find your place. Help build theirs.</p>
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
                    <span>Giving</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* THE COMMITMENT (LEAD BLOCK) */}
      <section className="qp-commitment-block">
        <div className="qp-commitment-inner">
          <p>
            Quadrant gives 10% of its profit, every quarter, to building permanent homes for underprivileged families
            around the world. Not a one-off donation, and not a marketing campaign — a standing commitment, built into
            how the business runs.
          </p>
        </div>
      </section>

      {/* HOW IT WORKS */}
      <section>
        <div className="qp-giving-section">
          <h2>How It Works</h2>

          <ul className="qp-how-it-works">
            <li>
              <span className="qp-dash">—</span>
              <span>
                <strong>10% of profit, quarterly.</strong> A fixed share, set aside every quarter — not left to
                year-end.
              </span>
            </li>
            <li>
              <span className="qp-dash">—</span>
              <span>
                <strong>Delivered through partners.</strong> Funds are channelled through established humanitarian
                builders already working on the ground (partners to be named).
              </span>
            </li>
            <li>
              <span className="qp-dash">—</span>
              <span>
                <strong>Permanent homes, not temporary relief.</strong> We fund homes built to last, owned with dignity.
              </span>
            </li>
            <li>
              <span className="qp-dash">—</span>
              <span>
                <strong>Transparent.</strong> We will report what was given and where, as the programme grows.
              </span>
            </li>
          </ul>
        </div>
      </section>

      {/* CLOSING PROMPT */}
      <section className="qp-giving-closing">
        <p>
          When you build your future with Quadrant, you help build someone else&apos;s. That is the standard we hold
          ourselves to.
        </p>
        <Link href="/contact">Speak with an Advisor</Link>
      </section>
    </>
  );
}
