import { GOOGLE_REVIEW_URL } from "@/components/site/Footer";
import Link from "@/components/site/Link";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { formTimestamp } from "@/lib/spam";
import { ContactForm } from "./_components/ContactForm";
import "./page.css";

// Cached for a minute, then rebuilt with the latest settings and content.
export const revalidate = 60;

export async function generateMetadata() {
  const settings = await getSettings();
  return pageMetadata({
    title: `Speak with an Advisor — ${pick(settings, "site_name", "Quadrant Properties")}`,
    description:
      "No pitch, no pressure — just a conversation about where you want to stand. Tell us what you're looking for, and we'll be in touch.",
    path: "/contact",
  });
}

const MAP_QUERY = encodeURIComponent("Concord Tower, Dubai Media City, Dubai");

const social: React.CSSProperties = { color: "#C8A965", fontSize: 20 };

export default async function ContactPage() {
  const settings = await getSettings();
  const s = (key: string, fallback: string | null = null) => pick(settings, key, fallback);

  const officeAddress = s("office_address");
  const contactPhone = s("contact_phone");
  const contactEmail = s("contact_email");
  const whatsapp = s("whatsapp_number");
  const officeHours = s("office_hours");
  const linkedin = s("linkedin_url");
  const facebook = s("facebook_url");
  const mapsEmbed = s("google_maps_embed");

  return (
    <>
      {/* PAGE BANNER */}
      <section className="qp-contact-banner">
        <div className="container">
          <h1>Speak with an Advisor</h1>
          <p>
            No pitch, no pressure - just a conversation about where you want to stand. Tell us what you&apos;re looking
            for, and we&apos;ll be in touch.
          </p>
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
                    <span>Contact</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CONTACT GRID */}
      <section>
        <div className="qp-contact-wrap">
          {/* LEFT — Direct Contact Details */}
          <div className="qp-contact-left">
            <h3>Direct Contact</h3>

            {/* Office Address */}
            <div className="qp-cont-item">
              <span className="qp-cont-label">Office</span>
              {officeAddress ? (
                <p>{officeAddress}</p>
              ) : (
                <p style={{ color: "#8a93a3" }}>Office 805, Concord Tower, Dubai Media City, Dubai</p>
              )}
            </div>

            {/* Phone */}
            <div className="qp-cont-item">
              <span className="qp-cont-label">Phone</span>
              {contactPhone ? (
                <a href={`tel:${contactPhone}`}>{contactPhone}</a>
              ) : (
                <a href="tel:+971524233010">+971524233010</a>
              )}
            </div>

            {/* Email */}
            <div className="qp-cont-item">
              <span className="qp-cont-label">Email</span>
              {contactEmail ? (
                <a href={`mailto:${contactEmail}`}>{contactEmail}</a>
              ) : (
                <a href="mailto:info@quadrant.ae">info@quadrant.ae</a>
              )}
            </div>

            {/* WhatsApp */}
            <div className="qp-cont-item">
              <span className="qp-cont-label">WhatsApp</span>
              {whatsapp ? (
                <a href={`https://wa.me/${whatsapp.replace(/[^0-9]/g, "")}`} target="_blank">
                  {whatsapp}
                </a>
              ) : (
                <a href="https://wa.me/971524233010" target="_blank">
                  +971524233010
                </a>
              )}
            </div>

            <div className="qp-cont-divider"></div>

            {/* Office Hours */}
            <div className="qp-cont-item">
              <span className="qp-cont-label">Office Hours</span>
              {officeHours ? (
                <p>{officeHours}</p>
              ) : (
                <p style={{ color: "#8a93a3" }}>Monday – Friday, 9:00am – 6:00pm GST</p>
              )}
            </div>

            <div className="qp-cont-divider"></div>

            {/* Google Review */}
            <div className="qp-cont-item">
              <span className="qp-cont-label">Worked With Us?</span>
              <a href={GOOGLE_REVIEW_URL} target="_blank">
                Leave us a Google review
              </a>
              <img
                src="/google-review-qr.svg"
                alt="QR code for our Google review page"
                width={110}
                height={110}
                style={{ display: "block", marginTop: 10 }}
              />
            </div>

            <div className="qp-cont-divider"></div>

            {/* Social Links */}
            <div className="qp-cont-item">
              <span className="qp-cont-label">Follow Us</span>
              <div style={{ display: "flex", alignItems: "center", gap: 14, marginTop: 8 }}>
                <a
                  href={s("instagram_url", "https://www.instagram.com/quadrantproperties/") ?? undefined}
                  target="_blank"
                  title="Instagram"
                  style={social}
                >
                  <i className="fa-brands fa-instagram"></i>
                </a>

                {linkedin && (
                  <a href={linkedin} target="_blank" title="LinkedIn" style={social}>
                    <i className="fa-brands fa-linkedin"></i>
                  </a>
                )}

                {facebook && (
                  <a href={facebook} target="_blank" title="Facebook" style={social}>
                    <i className="fa-brands fa-facebook"></i>
                  </a>
                )}
              </div>
            </div>
          </div>

          {/* RIGHT — Enquiry Form */}
          <div className="qp-contact-right">
            <h3>Send an Enquiry</h3>
            <ContactForm ts={formTimestamp()} />
          </div>
        </div>
      </section>

      {/* MAP */}
      {mapsEmbed ? (
        <section dangerouslySetInnerHTML={{ __html: mapsEmbed }} />
      ) : (
        <section className="qp-map-sec">
          <div className="container">
            <h3>Visit Our Office</h3>
            <div className="qp-map-frame">
              <iframe
                src={`https://www.google.com/maps?q=${MAP_QUERY}&output=embed`}
                title="Quadrant Properties office on Google Maps"
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
                allowFullScreen
              />
              <div className="qp-map-card">
                <span className="qp-cont-label">Our Office</span>
                <p>{officeAddress ?? "Office 805, Concord Tower, Dubai Media City, Dubai"}</p>
                <a
                  className="qp-map-btn"
                  href={`https://www.google.com/maps/dir/?api=1&destination=${MAP_QUERY}`}
                  target="_blank"
                >
                  <i className="fa-solid fa-location-arrow" /> Get Directions
                </a>
              </div>
            </div>
          </div>
        </section>
      )}
    </>
  );
}
