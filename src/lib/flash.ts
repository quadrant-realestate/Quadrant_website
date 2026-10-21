import "server-only";
import { cookies } from "next/headers";

// One-time admin messages (Laravel's ->with('success', ...)). Set in a server action
// right before redirect(); shown once by <AdminFlash />.

const COOKIE = "qp_flash";
export type FlashMessage = { type: "success" | "error"; message: string };

export async function flash(type: FlashMessage["type"], message: string): Promise<void> {
  const store = await cookies();
  store.set(COOKIE, JSON.stringify({ type, message }), { path: "/admin", maxAge: 30, httpOnly: true, sameSite: "lax" });
}

export async function readFlash(): Promise<FlashMessage | null> {
  const store = await cookies();
  const raw = store.get(COOKIE)?.value;
  if (!raw) return null;
  try {
    return JSON.parse(raw) as FlashMessage;
  } catch {
    return null;
  }
}
