import "server-only";
import { createHmac, timingSafeEqual } from "node:crypto";

// Bot filter for the public forms — same rules as HomeController::looksLikeSpam.

function sign(value: string): string {
  return createHmac("sha256", process.env.SESSION_SECRET ?? "").update(value).digest("hex");
}

/** Signed "form rendered at" timestamp for the hidden form_ts field. */
export function formTimestamp(): string {
  const ts = String(Math.floor(Date.now() / 1000));
  return `${ts}.${sign(ts)}`;
}

function readTimestamp(token: string): number | null {
  const [ts, mac] = token.split(".");
  if (!ts || !mac) return null;
  const expected = Buffer.from(sign(ts));
  const given = Buffer.from(mac);
  if (expected.length !== given.length || !timingSafeEqual(expected, given)) return null;
  return Number(ts);
}

/** Returns a reason string when the submission looks like spam, otherwise null. */
export function spamReason(form: FormData, message: string | null | undefined): string | null {
  // Hidden honeypot field — humans leave it empty
  if (String(form.get("website") ?? "").trim() !== "") return "honeypot";

  // Submitted faster than a human could fill the form
  const token = String(form.get("form_ts") ?? "");
  if (token) {
    const ts = readTimestamp(token);
    if (ts === null) return "bad timestamp";
    if (Math.floor(Date.now() / 1000) - ts < 3) return "too fast";
  }

  // Real clients don't paste HTML links or URLs into an inquiry
  const text = `${message ?? ""} ${form.get("name") ?? ""}`;
  if (/<a\s|href\s*=|\[url|https?:\/\/|www\./i.test(text)) return "links in message";

  return null;
}
