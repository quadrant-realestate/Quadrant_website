import { SignJWT, jwtVerify } from "jose";

// Shared by middleware (edge runtime) and server code, so no Node-only imports here.

export const SESSION_COOKIE = "qp_admin";
const MAX_AGE_SECONDS = 60 * 60 * 2; // Laravel SESSION_LIFETIME was 120 minutes

export type AdminSession = { id: number; name: string | null; email: string | null };

function secretKey(): Uint8Array {
  const secret = process.env.SESSION_SECRET;
  if (!secret || secret.length < 32) {
    throw new Error("SESSION_SECRET must be set to a random string of at least 32 characters.");
  }
  return new TextEncoder().encode(secret);
}

export async function signSession(session: AdminSession): Promise<string> {
  return new SignJWT({ ...session })
    .setProtectedHeader({ alg: "HS256" })
    .setIssuedAt()
    .setExpirationTime(`${MAX_AGE_SECONDS}s`)
    .sign(secretKey());
}

export async function verifySession(token: string | undefined): Promise<AdminSession | null> {
  if (!token) return null;
  try {
    const { payload } = await jwtVerify(token, secretKey());
    if (typeof payload.id !== "number") return null;
    return { id: payload.id, name: (payload.name as string) ?? null, email: (payload.email as string) ?? null };
  } catch {
    return null;
  }
}

export const sessionCookieOptions = {
  httpOnly: true,
  sameSite: "lax" as const,
  secure: process.env.NODE_ENV === "production",
  path: "/",
  maxAge: MAX_AGE_SECONDS,
};
