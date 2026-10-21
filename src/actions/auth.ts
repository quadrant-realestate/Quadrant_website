"use server";

import { createHash } from "node:crypto";
import { redirect } from "next/navigation";
import { z } from "zod";
import { endSession, startSession } from "@/lib/auth";
import { queryOne } from "@/lib/db";

export type SignInState = { error?: string; errors?: Record<string, string>; email?: string };

const schema = z.object({
  email_address: z.string().email("The email address field must be a valid email address.").max(220),
  password: z
    .string()
    .min(8, "The password field must be at least 8 characters.")
    .max(50, "The password field must not be greater than 50 characters."),
});

// Admin passwords are stored as SHA1 hashes by the Laravel site; kept compatible so existing logins work.
const sha1 = (value: string) => createHash("sha1").update(value).digest("hex");

export async function signIn(_prev: SignInState, form: FormData): Promise<SignInState> {
  const parsed = schema.safeParse({
    email_address: String(form.get("email_address") ?? ""),
    password: String(form.get("password") ?? ""),
  });
  const email = String(form.get("email_address") ?? "");

  if (!parsed.success) {
    const errors: Record<string, string> = {};
    for (const issue of parsed.error.issues) errors[String(issue.path[0])] ??= issue.message;
    return { errors, email };
  }

  const admin = await queryOne<{ id: number; name: string | null; email: string | null; password: string | null }>(
    "SELECT id, name, email, password FROM admins WHERE email = ? LIMIT 1",
    [parsed.data.email_address],
  );

  if (!admin) return { error: "Your account does not exist in our system.", email };
  if (admin.password !== sha1(parsed.data.password)) return { error: "Incorrect password. Please try again.", email };

  await startSession({ id: admin.id, name: admin.name, email: admin.email });
  redirect("/admin");
}

export async function signOut(): Promise<void> {
  await endSession();
  redirect("/admin/sign-in");
}
