"use server";

import { headers } from "next/headers";
import { after } from "next/server";
import { z } from "zod";
import { insert } from "@/lib/db";
import { notifyInquiry } from "@/lib/mail";
import { spamReason } from "@/lib/spam";

/**
 * State returned to the forms (used with React's useActionState).
 * `success` holds the thank-you message; `errors` maps field name → first error message.
 */
export type FormState = {
  success?: string;
  errors?: Record<string, string>;
  values?: Record<string, string>;
};

const CONTACT_SUCCESS = "Thank you for contacting us. One of our advisors will get in touch with you shortly.";
const INQUIRY_SUCCESS = "Thank you! We will be in touch shortly.";

async function clientIp(): Promise<string | null> {
  const h = await headers();
  return h.get("x-forwarded-for")?.split(",")[0]?.trim() ?? h.get("x-real-ip") ?? null;
}

function values(form: FormData): Record<string, string> {
  const out: Record<string, string> = {};
  form.forEach((v, k) => {
    if (typeof v === "string" && k !== "form_ts" && k !== "website") out[k] = v;
  });
  return out;
}

function fieldErrors(error: z.ZodError): Record<string, string> {
  const out: Record<string, string> = {};
  for (const issue of error.issues) {
    const key = String(issue.path[0]);
    if (!out[key]) out[key] = issue.message;
  }
  return out;
}

const required = (label: string, max: number) =>
  z.string({ error: `The ${label} field is required.` })
    .trim()
    .min(1, `The ${label} field is required.`)
    .max(max, `The ${label} field must not be greater than ${max} characters.`);

const email = z
  .string({ error: "The email field is required." })
  .trim()
  .min(1, "The email field is required.")
  .email("The email field must be a valid email address.")
  .max(255);

// ============================================================
// CONTACT FORM (contact page)
// ============================================================
const contactSchema = z.object({
  name: required("name", 150),
  email,
  phone: required("phone", 30),
  interest: z.enum(["off-plan", "luxury", "investment", "selling", "general"], {
    error: "The selected interest is invalid.",
  }),
  message: required("message", 5000),
});

export async function submitContact(_prev: FormState, form: FormData): Promise<FormState> {
  const reason = spamReason(form, form.get("message") as string | null);
  if (reason) {
    console.info(`Blocked spam inquiry: ${reason}`, { email: form.get("email") });
    return { success: CONTACT_SUCCESS };
  }

  const parsed = contactSchema.safeParse(Object.fromEntries(form));
  if (!parsed.success) return { errors: fieldErrors(parsed.error), values: values(form) };

  const d = parsed.data;
  const id = await insert("inquiries", {
    name: d.name,
    email: d.email,
    phone: d.phone,
    message: d.message,
    inquiry_type: d.interest,
    status: "new",
    source: "contact_page",
    ip_address: await clientIp(),
  });

  // Send the email after the response, so a slow mail server never delays the visitor.
  after(() => notifyInquiry(id));
  return { success: CONTACT_SUCCESS };
}

// ============================================================
// REGISTER-INTEREST FORMS (property / development / investment / residence pages)
// ============================================================
const inquirySchema = z.object({
  name: required("name", 150),
  email,
  phone: required("phone", 30),
});

const optionalId = (v: FormDataEntryValue | null) => {
  const n = Number(v);
  return v !== null && v !== "" && Number.isInteger(n) && n > 0 ? n : null;
};
const optionalText = (v: FormDataEntryValue | null, max: number) => {
  const s = typeof v === "string" ? v.trim() : "";
  return s ? s.slice(0, max) : null;
};

export async function submitInquiry(_prev: FormState, form: FormData): Promise<FormState> {
  const reason = spamReason(form, form.get("notes") as string | null);
  if (reason) {
    console.info(`Blocked spam inquiry: ${reason}`, { email: form.get("email") });
    return { success: INQUIRY_SUCCESS };
  }

  const parsed = inquirySchema.safeParse(Object.fromEntries(form));
  if (!parsed.success) return { errors: fieldErrors(parsed.error), values: values(form) };

  const d = parsed.data;
  const id = await insert("inquiries", {
    property_id: optionalId(form.get("property_id")),
    development_id: optionalId(form.get("development_id")),
    name: d.name,
    email: d.email,
    phone: d.phone,
    message: optionalText(form.get("notes"), 5000),
    inquiry_type: optionalText(form.get("inquiry_type"), 100) ?? "general",
    status: "new",
    source: optionalText(form.get("source"), 100) ?? "website",
    ip_address: await clientIp(),
  });

  // Send the email after the response, so a slow mail server never delays the visitor.
  after(() => notifyInquiry(id));
  return { success: INQUIRY_SUCCESS };
}
