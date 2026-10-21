import "server-only";
import { execute, query, queryOne, type Params } from "@/lib/db";

export type InquiryRow = {
  id: number;
  name: string | null;
  email: string | null;
  phone: string | null;
  inquiry_type: string | null;
  message: string | null;
  status: string | null;
  notes: string | null;
  budget?: number | string | null;
  property_id: number | null;
  created_at: Date | null;
  property_title: string | null;
  property_ref: string | null;
};

export type InquiryFilters = { status?: string; inquiry_type?: string; search?: string };

/** InquiryController@index */
export async function listInquiries(f: InquiryFilters) {
  const where: string[] = [];
  const params: Params = [];
  if (f.status) {
    where.push("inquiries.status = ?");
    params.push(f.status);
  }
  if (f.inquiry_type) {
    where.push("inquiries.inquiry_type = ?");
    params.push(f.inquiry_type);
  }
  if (f.search) {
    where.push("(inquiries.name LIKE ? OR inquiries.email LIKE ? OR inquiries.phone LIKE ?)");
    params.push(`%${f.search}%`, `%${f.search}%`, `%${f.search}%`);
  }

  const [inquiries, [counts]] = await Promise.all([
    query<InquiryRow>(
      `SELECT inquiries.*, properties.title AS property_title, properties.reference_no AS property_ref
         FROM inquiries
         LEFT JOIN properties ON inquiries.property_id = properties.id
        ${where.length ? `WHERE ${where.join(" AND ")}` : ""}
        ORDER BY inquiries.created_at DESC`,
      params,
    ),
    query<Record<"all" | "new" | "contacted" | "qualified" | "closed", number>>(
      `SELECT COUNT(*) AS \`all\`,
              SUM(status = 'new') AS \`new\`,
              SUM(status = 'contacted') AS contacted,
              SUM(status = 'qualified') AS qualified,
              SUM(status = 'closed') AS closed
         FROM inquiries`,
    ),
  ]);

  return {
    inquiries,
    counts: {
      all: Number(counts?.all ?? 0),
      new: Number(counts?.new ?? 0),
      contacted: Number(counts?.contacted ?? 0),
      qualified: Number(counts?.qualified ?? 0),
      closed: Number(counts?.closed ?? 0),
    },
  };
}

/**
 * InquiryController@show — loads the inquiry and, like the controller, marks a "new" inquiry as contacted.
 */
export async function showInquiry(id: number) {
  const inquiry = await queryOne<InquiryRow & { property_image: string | null }>(
    `SELECT inquiries.*, properties.title AS property_title, properties.reference_no AS property_ref,
            properties.main_image AS property_image
       FROM inquiries
       LEFT JOIN properties ON inquiries.property_id = properties.id
      WHERE inquiries.id = ?
      LIMIT 1`,
    [id],
  );
  if (!inquiry) return null;

  // Mark as contacted if still new
  if (inquiry.status === "new") {
    await execute("UPDATE inquiries SET status = 'contacted', updated_at = ? WHERE id = ?", [new Date(), id]);
    inquiry.status = "contacted";
  }
  return inquiry;
}
