import { pageMetadata } from "@/lib/seo";

// Cached for a minute, then rebuilt with the latest settings and content.
export const revalidate = 60;

export async function generateMetadata() {
  // noindex until the real policy replaces "Coming Soon"
  return pageMetadata({
    title: "Privacy Policy — Quadrant Properties",
    description: "How Quadrant Properties collects, uses and protects the personal information you share through our website and enquiry forms.",
    path: "/privacy-policy",
    noindex: true,
  });
}

export default function PrivacyPolicyPage() {
  return (
    <div style={{ padding: "120px 0 60px", textAlign: "center" }}>
      <h1 style={{ fontFamily: "'Cormorant Garamond',serif", color: "#11203A" }}>Coming Soon</h1>
    </div>
  );
}
