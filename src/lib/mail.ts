import "server-only";
import nodemailer from "nodemailer";
import { queryOne } from "@/lib/db";
import { escapeHtml, formatDate, nl2brEscaped, ucfirst } from "@/lib/format";
import { siteUrl } from "@/lib/url";

type InquiryForEmail = {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  inquiry_type: string | null;
  source: string | null;
  message: string | null;
  created_at: Date;
  subject_title: string | null;
};

function recipients(): string[] {
  return (process.env.INQUIRY_NOTIFY_EMAILS ?? "quadrantpropertiesai@gmail.com")
    .split(",")
    .map((s) => s.trim())
    .filter(Boolean);
}

function transport() {
  const port = Number(process.env.MAIL_PORT ?? 465);
  return nodemailer.createTransport({
    host: process.env.MAIL_HOST,
    port,
    secure: (process.env.MAIL_ENCRYPTION ?? "ssl").toLowerCase() === "ssl" || port === 465,
    auth: process.env.MAIL_USERNAME ? { user: process.env.MAIL_USERNAME, pass: process.env.MAIL_PASSWORD } : undefined,
  });
}

/**
 * Email the team about a new inquiry. A mail failure must never block the
 * visitor's submission, so errors are only logged.
 */
export async function notifyInquiry(id: number): Promise<void> {
  const to = recipients();
  if (to.length === 0 || !process.env.MAIL_HOST) return;

  try {
    const inquiry = await queryOne<InquiryForEmail>(
      `SELECT inquiries.*, COALESCE(developments.title, properties.title) AS subject_title
         FROM inquiries
         LEFT JOIN properties ON inquiries.property_id = properties.id
         LEFT JOIN developments ON inquiries.development_id = developments.id
        WHERE inquiries.id = ?`,
      [id],
    );
    if (!inquiry) return;

    await transport().sendMail({
      from: { name: process.env.MAIL_FROM_NAME ?? "Quadrant Properties", address: process.env.MAIL_FROM_ADDRESS ?? "" },
      to,
      replyTo: { name: inquiry.name, address: inquiry.email },
      subject: `New website inquiry: ${inquiry.name}`,
      html: inquiryEmailHtml(inquiry),
    });
  } catch (error) {
    console.error(`Inquiry notification email failed (inquiry ${id}):`, error);
  }
}

function inquiryEmailHtml(i: InquiryForEmail): string {
  const e = (v: string | null | undefined) => escapeHtml(v ?? "");
  const regarding = i.subject_title
    ? `<tr><td style="color:#777;">Regarding</td><td>${e(i.subject_title)}</td></tr>`
    : "";
  const message = i.message
    ? `<p style="margin:20px 0 6px;color:#777;font-size:13px;">Message</p>
                    <div style="padding:14px;background:#f8f8f8;border-radius:4px;font-size:14px;line-height:1.5;">${nl2brEscaped(i.message)}</div>`
    : "";

  return `<!DOCTYPE html>
<html>
<body style="margin:0;padding:24px;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;color:#222;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#fff;border-radius:6px;">
        <tr>
            <td style="padding:24px 28px;border-bottom:1px solid #eee;">
                <h2 style="margin:0;font-size:20px;">New inquiry from the website</h2>
                <p style="margin:6px 0 0;color:#777;font-size:13px;">${formatDate(i.created_at, "d M Y, H:i")}</p>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 28px;">
                <table width="100%" cellpadding="6" cellspacing="0" style="font-size:14px;">
                    <tr><td style="color:#777;width:140px;">Name</td><td><strong>${e(i.name)}</strong></td></tr>
                    <tr><td style="color:#777;">Email</td><td><a href="mailto:${e(i.email)}">${e(i.email)}</a></td></tr>
                    <tr><td style="color:#777;">Phone</td><td><a href="tel:${e(i.phone)}">${e(i.phone)}</a></td></tr>
                    <tr><td style="color:#777;">Interest</td><td>${e(ucfirst((i.inquiry_type ?? "").replace(/-/g, " ")))}</td></tr>
                    <tr><td style="color:#777;">Source</td><td>${e((i.source ?? "").replace(/_/g, " "))}</td></tr>
                    ${regarding}
                </table>
                ${message}
                <p style="margin:24px 0 0;">
                    <a href="${siteUrl(`/admin/inquiries/${i.id}`)}" style="display:inline-block;padding:10px 18px;background:#111;color:#fff;text-decoration:none;border-radius:4px;font-size:14px;">View in admin panel</a>
                </p>
                <p style="margin:16px 0 0;color:#999;font-size:12px;">Reply to this email to answer ${e(i.name)} directly.</p>
            </td>
        </tr>
    </table>
</body>
</html>`;
}
