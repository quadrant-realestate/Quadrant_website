import Link from "@/components/site/Link";
import { pageMetadata } from "@/lib/seo";
import { setting } from "@/lib/settings";
import { FallbackImg } from "../_home/FallbackImg";
import "./page.css";

// Rendered on every request, like the Laravel page (settings and database content are always current).
export const dynamic = "force-dynamic";

export async function generateMetadata() {
  return pageMetadata({
    title: `About — ${await setting("site_name", "Quadrant Properties")}`,
    description:
      "Every life needs a fixed point. Discover the story behind Quadrant Properties and the Four Bearings that guide how we work.",
    path: "/about",
  });
}

const REGIONS: [img: string, name: string, text: string][] = [
  ["india.webp", "India", "Permanent homes for families without secure shelter."],
  ["sudan.webp", "Sudan", "Rebuilding homes for families displaced by conflict."],
  ["yemen.webp", "Yemen", "Safe, lasting shelter for families in need."],
];

export default function AboutPage() {
  return (
    <>
      {/* PAGE BANNER */}
      <section className="qp-about-banner">
        <div className="container">
          <h1>About Quadrant</h1>
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
                    <span>About</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* FULL BRAND STORY */}
      <section>
        <div className="qp-about-story">
          <div className="qp-bearings-heading">
            <div className="qp-rule"></div>
            <h2>Every life needs a fixed point.</h2>
          </div>

          <p>
            Long before maps had borders, explorers carried a quadrant — an instrument that read the angle of the stars
            and told them one thing: where they stood. With it, a person alone on open water could hold a course, and
            find their way home.
          </p>

          <p>
            We took the name because it is the most honest description of what property should be. A home is not a
            transaction. It is the fixed point of a life — the coordinate from which everything else is measured. Where
            wealth is anchored. Where a family decides who it intends to be.
          </p>

          <p>
            Quadrant Properties exists to help discerning people find that point with precision. We work at the top of
            Dubai&apos;s market, with particular focus on off-plan — the residences and landmark developments still
            taking shape, where the city&apos;s future, and its best value, is being built today.
          </p>

          <p>
            And we work in a particular way: we advise, we do not sell. Our loyalty sits with your long-term interest,
            not with this quarter&apos;s commission. If a project is not right, we say so. If the timing is wrong, we
            say that too. The relationship is the asset; the transaction is one moment within it.
          </p>

          <p>
            We also believe that those who deal in homes carry a responsibility to those who have none. So every
            quarter, Quadrant aims to give minimum ten percent of its profit to building permanent homes for
            underprivileged families around the world — today in India, Sudan and Yemen. It is not a campaign or an
            afterthought. It is part of how the business is built.
          </p>

          <p>
            In a market crowded with noise, Quadrant offers something quieter and rarer: counsel you can trust,
            properties worthy of our name, and a clear sense of where you stand.
          </p>

          <p className="qp-about-sign-off">Know where you stand.</p>
        </div>
      </section>

      {/* THE FOUR BEARINGS (FULL) */}
      <section className="qp-bearings-section">
        <div className="qp-bearings-heading">
          <div className="qp-rule"></div>
          <h2>The Four Bearings</h2>
        </div>

        <div className="qp-bearings-grid">
          <div className="qp-bearing-card">
            <div className="qp-bearing-mark">N</div>
            <h3>Counsel</h3>
            <p>We advise; we do not sell. Our guidance stays honest even when honesty costs us the deal.</p>
          </div>

          <div className="qp-bearing-card">
            <div className="qp-bearing-mark">E</div>
            <h3>Craft</h3>
            <p>
              We represent only property of genuine quality. If it would not carry our name, it does not carry our
              name.
            </p>
          </div>

          <div className="qp-bearing-card">
            <div className="qp-bearing-mark">S</div>
            <h3>Custody</h3>
            <p>Your interest is held above ours, as a long-term, confidential trust.</p>
          </div>

          <div className="qp-bearing-card qp-consequence">
            <div className="qp-bearing-mark">W</div>
            <h3>Consequence</h3>
            <p>
              Every year, we give a portion of our profit to building permanent homes for underprivileged families
              worldwide. Giving is built into how Quadrant operates, not added as an afterthought.
            </p>
          </div>
        </div>
      </section>

      {/* WHY WE DO IT */}
      <section>
        <div className="qp-giving-section">
          <h2>Why We Do It</h2>
          <p>
            We believe that those who deal in homes carry a responsibility to those who have none. A home is the fixed
            point of a life. For millions of families, that point has been lost to conflict, poverty or displacement.
            Helping rebuild it is, to us, simply part of doing this work properly.
          </p>
        </div>
      </section>

      {/* SECTION 7 — GIVING (key feature band — given real visual weight, not a footnote) */}
      <section className="qp-offmarket" style={{ minHeight: 380 }}>
        <img
          src="/public/assets/images/quadrant-giving.png"
          alt="Building homes worldwide"
          style={{ filter: "brightness(0.4)" }}
        />
        <div className="qp-container">
          <div className="qp-offmarket-inner" style={{ maxWidth: 600 }}>
            <div className="qp-eyebrow">Our Giving Commitment</div>
            <h2>
              Find your place.
              <br />
              Help build theirs.
            </h2>
            <p>
              Every year, Quadrant aims to give a portion of its profit to building permanent homes for underprivileged
              families around the world — today in India, Sudan and Yemen. It is part of how we are built, not an
              afterthought.
            </p>
          </div>
        </div>
      </section>

      {/* WHERE WE BUILD */}
      <section className="qp-regions-section">
        <div className="qp-regions-heading">
          <div className="qp-rule"></div>
          <h2>Where We Build</h2>
        </div>

        <div className="qp-regions-grid">
          {REGIONS.map(([img, name, text], i) => (
            <div className="qp-region-card" key={name}>
              <div className="qp-region-img">
                {/* First 3 page images stayed eager (LazyLoadImages middleware): giving banner, India, Sudan */}
                <FallbackImg
                  {...(i >= 2 ? { loading: "lazy", decoding: "async" } : {})}
                  src={`/public/assets/images/${img}`}
                  alt={name}
                  hideOnError
                />
              </div>
              <div className="qp-region-body">
                <h3>{name}</h3>
                <p>{text}</p>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* FOUNDER NOTE */}
      <section className="qp-founder-section">
        <div className="qp-founders-grid">
          {/* LEFT: Founder Card */}
          <div className="qp-founder-card">
            <div className="qp-founder-photo">
              <img
                loading="lazy"
                decoding="async"
                src="/public/assets/images/Faisal_dp.png"
                alt="Faisal Farooqui - Founder & CEO of Quadrant Properties"
              />
            </div>
            <div className="qp-founder-text">
              <h3>Faisal Farooqui</h3>
              <p className="qp-founder-title">Founder &amp; CEO, Quadrant Properties</p>
              <p className="qp-founder-bio">
                Behind every property is a decision that shapes a family&apos;s future, a portfolio&apos;s trajectory,
                the next chapter of someone&apos;s life. That conviction is why I founded Quadrant. We built this firm
                on a single, uncompromising idea, that clients deserve an advisor, not a salesperson. Counsel you can
                trust. Guidance shaped around your goals, not ours. And a partner who stays personally invested long
                after the deal is signed. Whether you&apos;re deploying significant capital or searching for the place
                you&apos;ll call home, our commitment never changes to earn your trust, protect your interests, and
                turn ambition into outcome. That isn&apos;t simply how we work. It&apos;s why we exist.
              </p>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
