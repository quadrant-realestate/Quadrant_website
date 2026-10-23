import type { Metadata, Viewport } from "next";
import { Footer } from "@/components/site/Footer";
import { Header } from "@/components/site/Header";
import { JsonLd } from "@/components/site/JsonLd";
import { getSettings, pick } from "@/lib/settings";
import { asset, siteUrl } from "@/lib/url";
import "./site.css";

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl()),
  icons: { icon: "/public/favicon.png" },
};

export const viewport: Viewport = { width: "device-width", initialScale: 1 };

// Pages are cached and rebuilt at most once a minute, so edits show within about 60 seconds.
export const revalidate = 60;

const GA_ID = "G-7Q2LFB01BK";

export default async function SiteLayout({ children }: { children: React.ReactNode }) {
  const settings = await getSettings();
  const s = (key: string, fallback: string | null = null) => pick(settings, key, fallback);

  // Organisation schema — on every page
  const organisation = {
    "@context": "https://schema.org",
    "@type": "RealEstateAgent",
    name: s("site_name", "Quadrant Properties"),
    url: siteUrl(),
    logo: siteUrl(asset(s("site_logo") ?? "")),
    telephone: s("contact_phone"),
    email: s("contact_email"),
    address: {
      "@type": "PostalAddress",
      streetAddress: s("office_address"),
      addressLocality: "Dubai",
      addressCountry: "AE",
    },
    sameAs: [s("instagram_url"), s("facebook_url"), s("linkedin_url")],
  };

  return (
    <html lang="en">
      <head>
        {/* Google tag (gtag.js) */}
        <script async src={`https://www.googletagmanager.com/gtag/js?id=${GA_ID}`} />
        <script
          dangerouslySetInnerHTML={{
            __html: `window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '${GA_ID}');`,
          }}
        />

        <link href="/public/assets/css/bootstrap.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
        <link href="/public/assets/css/owl.carousel.min.css" rel="stylesheet" />
        <link href="/public/assets/css/owl.theme.default.css" rel="stylesheet" />
        <link href="/public/assets/css/lightgallery.css" rel="stylesheet" />
        <link href="/public/assets/css/common1931.css?v=1" rel="stylesheet" />
        <link href="/public/assets/css/responsivec8ac.css?ver2.1" rel="stylesheet" />

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link
          href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@400;500;600&display=swap"
          rel="stylesheet"
        />

        <JsonLd data={organisation} />
      </head>
      <body>
        <Header siteName={s("site_name", "Quadrant Properties")} />
        {children}
        <Footer settings={settings} />
      </body>
    </html>
  );
}
