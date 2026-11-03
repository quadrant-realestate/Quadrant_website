import { pageMetadata } from "@/lib/seo";

// Cached for a minute, then rebuilt with the latest settings and content.
export const revalidate = 60;

export async function generateMetadata() {
  // noindex until the real terms replace "Coming Soon"
  return pageMetadata({
    title: "Terms — Quadrant Properties",
    description: "The terms of use for the Quadrant Properties website, including listing information, enquiries and intellectual property.",
    path: "/terms",
    noindex: true,
  });
}

export default function TermsPage() {
  return (
    <div style={{ padding: "120px 0 60px", textAlign: "center" }}>
      <h1 style={{ fontFamily: "'Cormorant Garamond',serif", color: "#11203A" }}>Coming Soon</h1>
    </div>
  );
}
