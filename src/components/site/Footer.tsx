import { FloatingContact } from "@/components/site/FloatingContact";
import Link from "@/components/site/Link";
import { pick, type Settings } from "@/lib/settings";

export const GOOGLE_REVIEW_URL = "https://g.page/r/CbmTkqwRHGJPEAE/review";

const FOOTER_COMMUNITIES: [slug: string, name: string][] = [
  ["downtown-dubai", "Downtown Dubai"],
  ["dubai-marina", "Dubai Marina"],
  ["palm-jebel-ali", "Palm Jebel Ali"],
  ["dubai-creek-harbour", "Dubai Creek Harbour"],
  ["dubai-hills-estate", "Dubai Hills Estate"],
  ["jvc", "JVC"],
  ["dubai-south", "Dubai South"],
];

const EXPLORE: [href: string, label: string][] = [
  ["/buy", "Buy"],
  ["/sell", "Sell"],
  ["/communities", "Communities"],
  ["/services", "Services"],
  ["/about", "About"],
  ["/blogs", "Blogs"],
  ["/contact", "Contact"],
];

const heading: React.CSSProperties = {
  fontSize: 11,
  letterSpacing: "1.5px",
  textTransform: "uppercase",
  color: "#C8A965",
  marginBottom: 16,
  fontFamily: "'Inter',sans-serif",
};
const list: React.CSSProperties = { listStyle: "none", padding: 0, margin: 0 };
const item: React.CSSProperties = { marginBottom: 10 };
const link: React.CSSProperties = { color: "#8a9ab5", fontSize: 13, textDecoration: "none" };
const icon: React.CSSProperties = { fontSize: 16, color: "#C8A965", width: 16 };

const digits = (v: string | null) => (v ?? "").replace(/[^0-9]/g, "");

export function Footer({ settings }: { settings: Settings }) {
  const s = (key: string, fallback: string | null = null) => pick(settings, key, fallback);
  const siteName = s("site_name", "Quadrant Properties") ?? undefined;
  const address = s("office_address");
  const phone = s("contact_phone");
  const email = s("contact_email");
  const whatsapp = s("whatsapp_number");
  const linkedin = s("linkedin_url");
  const facebook = s("facebook_url");

  return (
    <>
      <footer style={{ background: "#11203A", padding: "60px 0 0", color: "#fff" }}>
        <div className="container">
          <div className="row">
            {/* Col 1: Brand */}
            <div className="col-lg-3 col-md-6 col-12 mb-4">
              <img
                src="/public/logo-light-quadrant.png"
                alt={siteName}
                style={{ maxHeight: 44, width: "auto", marginBottom: 16, display: "block", marginLeft: -5 }}
              />
              <p
                style={{
                  fontFamily: "'Cormorant Garamond',serif",
                  fontSize: 15,
                  color: "#C8A965",
                  margin: "0 0 8px",
                  fontStyle: "italic",
                }}
              >
                Know where you stand.
              </p>
              <p style={{ fontSize: 12, color: "#8a9ab5", lineHeight: 1.5, margin: 0 }}>
                Luxury &amp; off-plan real estate advisory, Dubai.
              </p>
            </div>

            {/* Col 2: Explore */}
            <div className="col-lg-2 col-md-3 col-6 mb-4">
              <h6 style={heading}>Explore</h6>
              <ul style={list}>
                {EXPLORE.map(([href, label]) => (
                  <li style={item} key={href}>
                    <Link href={href} style={link}>
                      {label}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>

            {/* Col 3: Communities */}
            <div className="col-lg-3 col-md-3 col-6 mb-4">
              <h6 style={heading}>Communities</h6>
              <ul style={list}>
                {FOOTER_COMMUNITIES.map(([slug, name]) => (
                  <li style={item} key={slug}>
                    <Link href={`/communities/${slug}`} style={link}>
                      {name}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>

            {/* Col 4: Contact */}
            <div className="col-lg-4 col-md-6 col-12 mb-4">
              <h6 style={heading}>Contact</h6>
              <ul style={list}>
                {address && (
                  <li style={{ ...item, lineHeight: 1.5 }}>
                    <a
                      href={`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`}
                      target="_blank"
                      style={link}
                    >
                      <i className="fa-solid fa-location-dot" style={icon} /> {address}
                    </a>
                  </li>
                )}

                {phone && (
                  <li style={item}>
                    <a href={`tel:${phone}`} style={link}>
                      <i className="fa-solid fa-phone" style={icon} /> {phone}
                    </a>
                  </li>
                )}

                {email && (
                  <li style={item}>
                    <a href={`mailto:${email}`} style={link}>
                      <i className="fa-solid fa-envelope" style={icon} /> {email}
                    </a>
                  </li>
                )}

                <li style={item}>
                  <a href={GOOGLE_REVIEW_URL} target="_blank" style={link}>
                    <i className="fa-brands fa-google" style={icon} /> Leave us a Google review
                  </a>
                </li>

                {/* Social Icons Row */}
                <li style={{ marginTop: 8 }}>
                  <div className="qp-footer-social" style={{ display: "flex", alignItems: "center", gap: 14 }}>
                    {whatsapp && (
                      <a href={`https://wa.me/${digits(whatsapp)}`} target="_blank" title="WhatsApp">
                        <i className="fa-brands fa-whatsapp" />
                      </a>
                    )}
                    <a
                      href={s("instagram_url", "https://www.instagram.com/quadrantproperties/") ?? undefined}
                      target="_blank"
                      title="Instagram"
                    >
                      <i className="fa-brands fa-instagram" />
                    </a>
                    {linkedin && (
                      <a href={linkedin} target="_blank" title="LinkedIn">
                        <i className="fa-brands fa-linkedin" />
                      </a>
                    )}
                    {facebook && (
                      <a href={facebook} target="_blank" title="Facebook">
                        <i className="fa-brands fa-facebook" />
                      </a>
                    )}
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>

        {/* Brass Rule + Legal Line */}
        <div style={{ borderTop: "1px solid #C8A965", marginTop: 30, padding: "20px 0" }}>
          <div className="container">
            <p style={{ fontSize: 11, color: "#5B6678", margin: 0, textAlign: "center" }}>
              © {new Date().getFullYear()} Quadrant Properties. All rights reserved. | ORN:{" "}
              {s("rera_number", "[Office Registration Number]")} | RERA Permit displayed per listing. |{" "}
              <Link href="/privacy-policy" style={{ fontSize: 11, color: "#5B6678" }}>
                Privacy Policy
              </Link>
              &nbsp;|&nbsp;
              <Link href="/terms" style={{ fontSize: 11, color: "#5B6678" }}>
                Terms of Use
              </Link>
            </p>
          </div>
        </div>
      </footer>

      <FloatingContact
        whatsapp={digits(s("whatsapp_number", "971524233010"))}
        phone={phone ?? "+971524233010"}
      />
    </>
  );
}
