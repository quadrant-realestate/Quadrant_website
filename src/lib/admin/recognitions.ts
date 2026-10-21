import "server-only";
import { query, queryOne } from "@/lib/db";

// Read queries for the admin recognitions pages (RecognitionController).

export type RecognitionRow = {
  id: number;
  media_name: string | null;
  logo: string | null;
  url: string | null;
  is_active: number;
  sort_order: number | null;
};

export async function listRecognitions(): Promise<RecognitionRow[]> {
  return query<RecognitionRow>("SELECT id, media_name, logo, url, is_active, sort_order FROM recognitions ORDER BY sort_order ASC, id DESC");
}

export async function getRecognition(id: number): Promise<RecognitionRow | null> {
  return queryOne<RecognitionRow>("SELECT id, media_name, logo, url, is_active, sort_order FROM recognitions WHERE id = ?", [id]);
}
