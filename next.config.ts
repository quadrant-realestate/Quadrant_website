import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // The Laravel site served every static file under /public/... — keep those URLs working.
  async rewrites() {
    return [{ source: "/public/:path((?!uploads/).*)", destination: "/:path" }];
  },
  async redirects() {
    return [
      { source: "/insights", destination: "/blogs", permanent: true },
      { source: "/insights/:slug", destination: "/blogs/:slug", permanent: true },
    ];
  },
  experimental: {
    // Plain 404 page for unknown URLs (the site and admin panel have separate root layouts).
    globalNotFound: true,
    // Admin forms upload images, floor plans and brochure PDFs.
    serverActions: { bodySizeLimit: "50mb" },
  },
};

export default nextConfig;
