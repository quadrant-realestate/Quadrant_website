"use server";

import { cookies } from "next/headers";

/** Called once a flash message has been shown, so it doesn't appear again. */
export async function clearFlash(): Promise<void> {
  const store = await cookies();
  store.set("qp_flash", "", { path: "/admin", maxAge: 0 });
}
