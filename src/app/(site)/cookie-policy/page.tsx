import { pageMetadata } from "@/lib/seo";

// Cached for a minute, then rebuilt with the latest settings and content.
export const revalidate = 60;

export async function generateMetadata() {
  // noindex until the real policy replaces "Coming Soon"
  return pageMetadata({
    title: "Cookies Policy — Quadrant Properties",
    description: "How the Quadrant Properties website uses cookies and similar tools, including Google Analytics and the Meta Pixel, and how to control them.",
    path: "/cookie-policy",
    noindex: true,
  });
}

export default function CookiePolicyPage() {
  return (
    <div style={{ padding: "120px 0 60px", textAlign: "center" }}>
      <h1 style={{ fontFamily: "'Cormorant Garamond',serif", color: "#11203A" }}>Coming Soon</h1>
    </div>
  );
}
