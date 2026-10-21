import "server-only";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { SESSION_COOKIE, sessionCookieOptions, signSession, verifySession, type AdminSession } from "./session";

export type { AdminSession };

/** The logged-in admin, or null. */
export async function getAdmin(): Promise<AdminSession | null> {
  const store = await cookies();
  return verifySession(store.get(SESSION_COOKIE)?.value);
}

/** Use at the top of every admin page and admin server action. Redirects to sign-in when logged out. */
export async function requireAdmin(): Promise<AdminSession> {
  const admin = await getAdmin();
  if (!admin) redirect("/admin/sign-in");
  return admin;
}

export async function startSession(admin: AdminSession): Promise<void> {
  const store = await cookies();
  store.set(SESSION_COOKIE, await signSession(admin), sessionCookieOptions);
}

export async function endSession(): Promise<void> {
  const store = await cookies();
  store.delete(SESSION_COOKIE);
}
