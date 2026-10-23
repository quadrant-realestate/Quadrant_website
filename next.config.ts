import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // The Laravel site served every static file under /public/... — keep those URLs working.
  // Uploaded files live in public/uploads too, so they are served as static files (Vercel can't read UPLOAD_DIR at runtime).
  async rewrites() {
    return [{ source: "/public/:path*", destination: "/:path*" }];
  },
  async headers() {
    return [
      {
        source: "/:path*",
        headers: [
          { key: "X-Content-Type-Options", value: "nosniff" },
          { key: "X-Frame-Options", value: "SAMEORIGIN" },
          { key: "Referrer-Policy", value: "strict-origin-when-cross-origin" },
          { key: "Permissions-Policy", value: "camera=(), microphone=(), geolocation=()" },
        ],
      },
    ];
  },
  async redirects() {
    return [
      { source: "/insights", destination: "/blogs", permanent: true },
      { source: "/insights/:slug", destination: "/blogs/:slug", permanent: true },
    ];
  },
  // Bundled copy of the site content, read on Vercel (see src/lib/db.ts).
  outputFileTracingIncludes: {
    "/**": ["./database/quadrant.sqlite"],
  },
  experimental: {
    // Plain 404 page for unknown URLs (the site and admin panel have separate root layouts).
    globalNotFound: true,
    // Admin forms upload images, floor plans and brochure PDFs.
    serverActions: { bodySizeLimit: "50mb" },
  },
};

export default nextConfig;
