import { readFile, stat } from "node:fs/promises";
import path from "node:path";
import { diskPath } from "@/lib/uploads";

// Serves uploaded files at their old URLs: /public/uploads/<folder>/<file>

const TYPES: Record<string, string> = {
  ".jpg": "image/jpeg",
  ".jpeg": "image/jpeg",
  ".jfif": "image/jpeg",
  ".png": "image/png",
  ".webp": "image/webp",
  ".avif": "image/avif",
  ".gif": "image/gif",
  ".svg": "image/svg+xml",
  ".pdf": "application/pdf",
  ".mp4": "video/mp4",
  ".webm": "video/webm",
};

export async function GET(_request: Request, { params }: { params: Promise<{ path: string[] }> }) {
  const parts = (await params).path.map((p) => decodeURIComponent(p));
  const full = diskPath(`uploads/${parts.join("/")}`);
  if (!full) return new Response("Not found", { status: 404 });

  try {
    const info = await stat(full);
    if (!info.isFile()) return new Response("Not found", { status: 404 });
    const body = await readFile(full);
    return new Response(new Uint8Array(body), {
      headers: {
        "Content-Type": TYPES[path.extname(full).toLowerCase()] ?? "application/octet-stream",
        "Content-Length": String(info.size),
        "Cache-Control": "public, max-age=2592000, stale-while-revalidate=86400",
      },
    });
  } catch {
    return new Response("Not found", { status: 404 });
  }
}
