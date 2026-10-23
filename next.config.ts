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
      {
        // Browsers keep images, video and fonts for a week instead of re-checking them on every visit.
        source: "/public/:file(.*\\.(?:png|jpe?g|jfif|webp|avif|gif|svg|ico|mp4|webm|woff2?|ttf|otf))",
        headers: [{ key: "Cache-Control", value: "public, max-age=604800, stale-while-revalidate=86400" }],
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
