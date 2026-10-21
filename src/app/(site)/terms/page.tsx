import { pageMetadata } from "@/lib/seo";

// Rendered on every request, like the Laravel page (settings and database content are always current).
export const dynamic = "force-dynamic";

export async function generateMetadata() {
  return pageMetadata({ title: "Terms — Quadrant Properties", path: "/terms" });
}

export default function TermsPage() {
  return (
    <div style={{ padding: "120px 0 60px", textAlign: "center" }}>
      <h1 style={{ fontFamily: "'Cormorant Garamond',serif", color: "#11203A" }}>Coming Soon</h1>
    </div>
  );
}
