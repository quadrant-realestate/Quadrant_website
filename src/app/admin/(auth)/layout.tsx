import type { Metadata, Viewport } from "next";
import Script from "next/script";

export const metadata: Metadata = {
  title: "Quadrant | Dashboard",
  icons: { icon: "/public/favicon.png" },
  robots: { index: false, follow: false },
};

export const viewport: Viewport = { width: "device-width", initialScale: 1 };

// Root layout for the admin sign-in page (admin/signin.blade.php used its own stylesheet, style.css).
// Kept separate from the panel layout so its stylesheet never stays loaded inside the panel.
export default function AdminAuthLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
      <head>
        <link rel="stylesheet" href="/public/adminassets/css/style.css" />
      </head>
      <body>
        {children}
        <Script src="/public/adminassets/libs/bootstrap/dist/js/bootstrap.bundle.min.js" strategy="afterInteractive" />
        <Script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js" strategy="afterInteractive" />
      </body>
    </html>
  );
}
