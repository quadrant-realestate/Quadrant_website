import "server-only";

// Minimal read-only client for the Sanity Content Lake (GROQ over HTTP).
// Content is public, so no token is needed.

const config = {
  projectId: process.env.SANITY_PROJECT_ID ?? "60y5olnp",
  dataset: process.env.SANITY_DATASET ?? "production",
  apiVersion: process.env.SANITY_API_VERSION ?? "2025-02-19",
  useCdn: process.env.SANITY_USE_CDN !== "false",
};

/** Run a GROQ query and return its result, or `fallback` if Sanity can't be reached. */
export async function sanityQuery<T>(groq: string, params: Record<string, unknown>, fallback: T): Promise<T> {
  const host = config.useCdn ? "apicdn.sanity.io" : "api.sanity.io";
  const url = new URL(`https://${config.projectId}.${host}/v${config.apiVersion}/data/query/${config.dataset}`);
  url.searchParams.set("query", groq);
  for (const [key, value] of Object.entries(params)) url.searchParams.set(`$${key}`, JSON.stringify(value));

  try {
    const res = await fetch(url, {
      headers: { Accept: "application/json" },
      signal: AbortSignal.timeout(8000),
      next: { revalidate: 60 },
    });
    if (!res.ok) {
      console.warn("Sanity query failed", res.status, await res.text());
      return fallback;
    }
    const json = (await res.json()) as { result?: T | null };
    return json.result ?? fallback;
  } catch (error) {
    console.warn("Sanity unreachable:", error);
    return fallback;
  }
}

/** Resize/crop a Sanity CDN image URL. */
export function sanityImage(url: string | null | undefined, width: number, height?: number): string | null {
  if (!url) return null;
  const params = new URLSearchParams({ w: String(width), auto: "format", q: "80" });
  if (height) {
    params.set("h", String(height));
    params.set("fit", "crop");
  }
  return `${url}?${params.toString()}`;
}
