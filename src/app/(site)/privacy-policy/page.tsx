import { pageMetadata } from "@/lib/seo";

// Cached for a minute, then rebuilt with the latest settings and content.
export const revalidate = 60;

export async function generateMetadata() {
  return pageMetadata({ title: "Privacy Policy — Quadrant Properties", path: "/privacy-policy" });
}

export default function PrivacyPolicyPage() {
  return (
    <div style={{ padding: "120px 0 60px", textAlign: "center" }}>
      <h1 style={{ fontFamily: "'Cormorant Garamond',serif", color: "#11203A" }}>Coming Soon</h1>
    </div>
  );
}
