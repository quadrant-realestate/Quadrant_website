import type { Metadata, Viewport } from "next";
import Script from "next/script";
import { AdminShell } from "@/components/admin/AdminShell";
import { requireAdmin } from "@/lib/auth";
import { queryOne } from "@/lib/db";

// Root layout for every admin page behind the login (admin/include/header + sidebar + footer).
export const dynamic = "force-dynamic";

export const metadata: Metadata = {
  title: "Quadrant - Dashboard",
  icons: { icon: "/public/favicon.png" },
  robots: { index: false, follow: false },
};

export const viewport: Viewport = { width: "device-width", initialScale: 1 };

export default async function AdminPanelLayout({ children }: { children: React.ReactNode }) {
  await requireAdmin();
  const row = await queryOne<{ n: number }>("SELECT COUNT(*) AS n FROM inquiries WHERE status = 'new'");

  return (
    <html lang="en">
      <head>
        <link rel="stylesheet" href="/public/adminassets/css/styles.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
      </head>
      <body>
        <AdminShell newInquiries={Number(row?.n ?? 0)}>{children}</AdminShell>
        {/* Bootstrap 5 JS: dropdowns, modals, alerts, tabs (event delegation, works with React markup) */}
        <Script src="/public/adminassets/libs/bootstrap/dist/js/bootstrap.bundle.min.js" strategy="afterInteractive" />
        {/* Solar icons (<iconify-icon>) */}
        <Script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js" strategy="afterInteractive" />
      </body>
    </html>
  );
}
