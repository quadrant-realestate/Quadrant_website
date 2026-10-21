import "server-only";
import { mkdir, unlink, writeFile } from "node:fs/promises";
import path from "node:path";

// Uploaded files live outside the build (UPLOAD_DIR) and are stored in the database as
// "uploads/<folder>/<file>", exactly like the Laravel site, so existing rows keep working.

export function uploadRoot(): string {
  return path.resolve(process.env.UPLOAD_DIR ?? "./storage/uploads");
}

/** Absolute disk path for a stored "uploads/..." value, or null if it points outside the upload folder. */
export function diskPath(stored: string): string | null {
  const relative = stored.replace(/^\/?(public\/)?uploads\//, "");
  const full = path.resolve(uploadRoot(), relative);
  return full.startsWith(uploadRoot() + path.sep) ? full : null;
}

export function isFile(value: FormDataEntryValue | null | undefined): value is File {
  return typeof value === "object" && value !== null && "arrayBuffer" in value && value.size > 0;
}

/**
 * Save an uploaded file to uploads/<folder>/ and return the value to store in the database.
 * Names follow the Laravel convention: <unix time>_<original name>.
 */
export async function saveUpload(file: File, folder: string): Promise<string> {
  const safeFolder = folder.replace(/[^a-z0-9/_-]/gi, "").replace(/^\/+|\/+$/g, "");
  const original = path.basename(file.name).replace(/[^\w.\- ]+/g, "_");
  const name = `${Math.floor(Date.now() / 1000)}_${original}`;
  const dir = path.join(uploadRoot(), safeFolder);
  await mkdir(dir, { recursive: true });
  await writeFile(path.join(dir, name), Buffer.from(await file.arrayBuffer()));
  return `uploads/${safeFolder}/${name}`;
}

/** Save several files (e.g. a gallery) and return their stored paths in order. */
export async function saveUploads(files: FormDataEntryValue[], folder: string): Promise<string[]> {
  const saved: string[] = [];
  for (const f of files) if (isFile(f)) saved.push(await saveUpload(f, folder));
  return saved;
}

/** Delete a previously stored upload. Missing files are ignored. */
export async function deleteUpload(stored: string | null | undefined): Promise<void> {
  if (!stored) return;
  const full = diskPath(stored);
  if (!full) return;
  await unlink(full).catch(() => undefined);
}
