// notFound() inside a site page (e.g. unknown development slug) — same plain 404 as Laravel's abort(404).
export default function NotFound() {
  return (
    <div style={{ padding: "160px 0 100px", textAlign: "center", color: "#6b7280" }}>
      <span style={{ paddingRight: 16, borderRight: "1px solid #9ca3af", fontSize: 18 }}>404</span>
      <span style={{ marginLeft: 16, textTransform: "uppercase", fontSize: 18 }}>Not Found</span>
    </div>
  );
}
