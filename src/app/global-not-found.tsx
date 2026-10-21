import type { Metadata } from "next";

export const metadata: Metadata = { title: "Not Found" };

// Shown for URLs that match no route (same plain page Laravel showed for a 404).
export default function GlobalNotFound() {
  return (
    <html lang="en">
      <body
        style={{
          margin: 0,
          minHeight: "100vh",
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          fontFamily: "ui-sans-serif, system-ui, sans-serif",
          color: "#6b7280",
          background: "#f9fafb",
        }}
      >
        <div style={{ display: "flex", alignItems: "center", fontSize: 18, letterSpacing: 1 }}>
          <span style={{ paddingRight: 16, borderRight: "1px solid #9ca3af" }}>404</span>
          <span style={{ marginLeft: 16, textTransform: "uppercase" }}>Not Found</span>
        </div>
      </body>
    </html>
  );
}
