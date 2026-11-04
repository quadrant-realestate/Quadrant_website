import Link from "@/components/site/Link";
import { pageMetadata } from "@/lib/seo";
import { setting } from "@/lib/settings";
import { formTimestamp } from "@/lib/spam";
import { FallbackImg } from "../_home/FallbackImg";
import { PartnerForm } from "./_components/PartnerForm";
import "../sell/page.css";
import "./page.css";

// Cached for a minute, then rebuilt with the latest settings and content.
export const revalidate = 60;

export async function generateMetadata() {
  return pageMetadata({
    title: `Become a Partner — ${await setting("site_name", "Quadrant Properties")}`,
    description:
      "Join the Quadrant Properties referral programme. Introduce property buyers and investors to our advisors and earn a competitive commission on every completed deal.",
    path: "/become-a-partner",
  });
}

const BENEFITS: [icon: string, title: string, text: string][] = [
  [
    "fa-coins",
    "Competitive Commission",
    "Earn a competitive commission on every completed deal, on terms agreed with you before you refer anyone.",
  ],
  ["fa-infinity", "No Limit on Referrals", "Introduce as many clients as you like. There is no cap on what you can earn."],
  [
    "fa-handshake",
    "Clients in Good Hands",
    "Every client you introduce is looked after by a Quadrant advisor - advised, never pressured.",
  ],
];

const REFERRALS: [icon: string, title: string, text: string][] = [
  ["fa-house", "Buyers", "Anyone looking to buy a home in the UAE, whether off-plan or ready to move into."],
  ["fa-chart-line", "Investors", "Anyone looking to invest in UAE property, from a first unit to a wider portfolio."],
];

const STEPS: [number: string, title: string, text: string][] = [
  ["01", "Apply", "Fill in the form below with a few details about you and your network."],
  ["02", "Get Confirmed", "Our team reviews your application and contacts you to agree the partner terms."],
  ["03", "Refer and Earn", "Introduce your clients to us and earn commission on every completed deal."],
];

const FAQS: [question: string, answer: string][] = [
  [
    "Who can become a partner?",
    "Individuals, licensed agents and agencies with genuine connections to property buyers or investors can apply. Every application is reviewed, and acceptance depends on the rules that apply to your situation.",
  ],
  [
    "How much commission will I earn?",
    "Our commission is competitive and depends on the project and the deal. We agree the terms with you before you make a referral, so you know where you stand from the start.",
  ],
  [
    "When does a referral count?",
    "A referral counts when you introduce a client who is new to Quadrant and that client completes a purchase through us. The full terms are set out in your partner agreement.",
  ],
  [
    "When do I get paid?",
    "You get paid once the deal is complete and Quadrant Properties has received its commission. Our live tracker will notify you as soon as your commission is ready.",
  ],
  [
    "Do I need to manage the sale myself?",
    "No. You make the introduction, and a Quadrant advisor takes care of everything from there.",
  ],
];

export default function BecomeAPartnerPage() {
  return (
    <>
      {/* SECTION 1 — HERO */}
      <section className="qp-sell-hero">
        <FallbackImg
          className="qp-sell-hero-bg"
          src="/public/assets/images/quadrant-skyline-night.jpg"
          alt="Dubai skyline at night"
          fallbackSrc="/public/assets/img/placeholder.jpg"
        />
        <div className="container">
          <div className="qp-sell-hero-inner">
            <div className="qp-rule"></div>
            <h1>Become a Partner</h1>
            <p>
              Know someone looking to buy or invest in UAE property? Introduce them to Quadrant and earn a competitive
              commission on every completed deal.
            </p>
            <a href="#apply">Apply Now</a>
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
                    <span>Become a Partner</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* SECTION 2 — WHY PARTNER */}
      <section className="qp-sell-why">
        <div className="container">
          <div className="qp-sell-steps-heading">
            <div className="qp-rule"></div>
            <h2>Why Partner With Us</h2>
          </div>

          <div className="qp-sell-why-grid qp-partner-grid">
            {BENEFITS.map(([icon, title, text]) => (
              <div className="qp-sell-why-card" key={title}>
                <div className="qp-icon">
                  <i className={`fa-solid ${icon}`}></i>
                </div>
                <h3>{title}</h3>
                <p>{text}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* SECTION 3 — WHO YOU CAN REFER */}
      <section className="qp-sell-steps">
        <div className="container">
          <div className="qp-sell-steps-heading">
            <div className="qp-rule"></div>
            <h2>Who You Can Refer</h2>
          </div>

          <div className="qp-sell-why-grid qp-partner-grid qp-partner-grid-two">
            {REFERRALS.map(([icon, title, text]) => (
              <div className="qp-sell-why-card" key={title}>
                <div className="qp-icon">
                  <i className={`fa-solid ${icon}`}></i>
                </div>
                <h3>{title}</h3>
                <p>{text}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* SECTION 4 — HOW IT WORKS */}
      <section className="qp-sell-steps qp-partner-steps">
        <div className="container">
          <div className="qp-sell-steps-heading">
            <div className="qp-rule"></div>
            <h2>How It Works</h2>
          </div>

          <div className="qp-steps-grid">
            {STEPS.map(([number, title, text]) => (
              <div className="qp-step-card" key={number}>
                <span className="qp-step-number">{number}</span>
                <h3>{title}</h3>
                <p>{text}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* SECTION 5 — QUESTIONS */}
      <section className="qp-sell-steps">
        <div className="container">
          <div className="qp-sell-steps-heading">
            <div className="qp-rule"></div>
            <h2>Common Questions</h2>
          </div>

          <div className="qp-partner-faq">
            {FAQS.map(([question, answer]) => (
              <details key={question}>
                <summary>{question}</summary>
                <p>{answer}</p>
              </details>
            ))}
          </div>
        </div>
      </section>

      {/* SECTION 6 — APPLICATION FORM */}
      <section className="qp-sell-form" id="apply">
        <div className="container">
          <div className="qp-sell-form-inner">
            <h2>Register as a Partner</h2>
            <p>Tell us a little about yourself, and our team will be in touch.</p>
            <PartnerForm ts={formTimestamp()} />
          </div>
        </div>
      </section>
    </>
  );
}
