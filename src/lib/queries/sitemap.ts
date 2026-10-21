import "server-only";
import { query, queryOne } from "@/lib/db";

// SitemapController

type ListingTable = "properties" | "investments" | "branded_residences" | "developments" | "communities";

/** Same "active" rules as the front-end listing pages (SitemapController::hasItems). */
export async function hasItems(table: ListingTable, column: string | null, value: string | null): Promise<boolean> {
  try {
    const [activeColumn, activeValue] = table === "properties" ? ["status", "active"] : ["is_active", 1];
    let sql = `SELECT 1 AS found FROM \`${table}\` WHERE \`${activeColumn}\` = ?`;
    const params: (string | number)[] = [activeValue];
    if (column) {
      sql += ` AND \`${column}\` = ?`;
      params.push(value ?? "");
    }
    return (await queryOne(`${sql} LIMIT 1`, params)) !== null;
  } catch {
    return true; // if unsure, keep the page listed
  }
}

/**
 * Active rows with a slug, newest first. `lastmod` is updated_at formatted like Carbon's
 * toAtomString() with the app timezone (UTC): the stored wall-clock time + "+00:00".
 */
export async function activeSlugs(
  table: ListingTable,
  column: string,
  value: string | number,
): Promise<{ slug: string; lastmod: string | null }[] | null> {
  try {
    return await query<{ slug: string; lastmod: string | null }>(
      `SELECT slug, DATE_FORMAT(updated_at, '%Y-%m-%dT%H:%i:%s+00:00') AS lastmod
         FROM \`${table}\`
        WHERE \`${column}\` = ? AND slug IS NOT NULL AND slug != ''
        ORDER BY updated_at DESC`,
      [value],
    );
  } catch (error) {
    console.warn(`Sitemap: could not read ${table}: ${error instanceof Error ? error.message : error}`);
    return null;
  }
}
