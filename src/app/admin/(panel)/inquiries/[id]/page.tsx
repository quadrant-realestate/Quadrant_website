import Link from "next/link";
import { redirect } from "next/navigation";
import { deleteInquiry, updateInquiryNotes, updateInquiryStatus } from "@/actions/admin/inquiries";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { toId } from "@/lib/form";
import { formatDate, numberFormat, ucfirst } from "@/lib/format";
import { showInquiry } from "@/lib/admin/inquiries";
import { asset } from "@/lib/url";

const TYPE_COLORS: Record<string, string> = {
  property: "bg-primary-subtle text-primary",
  general: "bg-secondary-subtle text-secondary",
  valuation: "bg-info-subtle text-info",
};
const STATUS_COLORS: Record<string, string> = {
  new: "bg-danger-subtle text-danger",
  contacted: "bg-warning-subtle text-warning",
  qualified: "bg-success-subtle text-success",
  closed: "bg-secondary-subtle text-secondary",
};

// InquiryController@show → admin/inquiries/show.blade.php (viewing a "new" inquiry marks it contacted)
export default async function InquiryPage({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const inquiry = id ? await showInquiry(id) : null;
  if (!id || !inquiry) redirect("/admin/record-missing?m=inquiry");

  const budget = inquiry.budget;

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        {/* Page Header */}
        <div className="row">
          <div className="col-lg-12 d-flex align-items-strech">
            <div className="card card-body py-3">
              <div className="row align-items-center">
                <div className="col-12">
                  <div className="d-sm-flex align-items-center justify-space-between">
                    <h4 className="mb-4 mb-sm-0 card-title">Inquiry Details</h4>
                    <nav aria-label="breadcrumb" className="ms-auto">
                      <ol className="breadcrumb">
                        <li className="breadcrumb-item d-flex align-items-center">
                          <a className="text-muted text-decoration-none d-flex">
                            <iconify-icon icon="solar:home-2-line-duotone" className="fs-6" />
                          </a>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Inquiries</span>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">View</span>
                        </li>
                      </ol>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Alerts */}
        <AdminFlash />

        <div className="row">
          {/* LEFT: Inquiry Details */}
          <div className="col-lg-8">
            {/* Contact Info */}
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:user-circle-line-duotone" className="me-2" />
                  Contact Information
                </h5>
                <div className="row g-3">
                  <div className="col-md-6">
                    <label className="text-muted small fw-semibold">Full Name</label>
                    <p className="fw-semibold mb-0 fs-5">{inquiry.name}</p>
                  </div>

                  <div className="col-md-6">
                    <label className="text-muted small fw-semibold">Email Address</label>
                    <p className="mb-0">
                      <a href={`mailto:${inquiry.email ?? ""}`} className="text-primary">
                        {inquiry.email}
                      </a>
                    </p>
                  </div>

                  <div className="col-md-6">
                    <label className="text-muted small fw-semibold">Phone Number</label>
                    <p className="mb-0">
                      {inquiry.phone ? (
                        <a href={`tel:${inquiry.phone}`} className="text-primary">
                          {inquiry.phone}
                        </a>
                      ) : (
                        <span className="text-muted">Not provided</span>
                      )}
                    </p>
                  </div>

                  <div className="col-md-6">
                    <label className="text-muted small fw-semibold">Inquiry Type</label>
                    <p className="mb-0">
                      <span className={`badge ${TYPE_COLORS[inquiry.inquiry_type ?? ""] ?? "bg-secondary-subtle text-secondary"} fs-2 py-1 px-2`}>
                        {ucfirst(inquiry.inquiry_type ?? "general")}
                      </span>
                    </p>
                  </div>

                  <div className="col-md-6">
                    <label className="text-muted small fw-semibold">Received On</label>
                    <p className="mb-0">{formatDate(inquiry.created_at ?? new Date(), "d M Y, h:i A")}</p>
                  </div>

                  <div className="col-md-6">
                    <label className="text-muted small fw-semibold">Budget</label>
                    <p className="mb-0">
                      {budget ? <span className="fw-semibold">AED {numberFormat(budget)}</span> : <span className="text-muted">Not specified</span>}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            {/* Message */}
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-3 pb-2 border-bottom">
                  <iconify-icon icon="solar:chat-line-line-duotone" className="me-2" />
                  Message
                </h5>
                <p className="mb-0" style={{ lineHeight: 1.8 }}>
                  {inquiry.message ?? "No message provided."}
                </p>
              </div>
            </div>

            {/* Notes */}
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-3 pb-2 border-bottom">
                  <iconify-icon icon="solar:notes-line-duotone" className="me-2" />
                  Admin Notes
                </h5>
                <form action={updateInquiryNotes.bind(null, inquiry.id)}>
                  <div className="mb-3">
                    <textarea
                      className="form-control"
                      name="notes"
                      rows={5}
                      placeholder="Add your notes about this lead..."
                      defaultValue={inquiry.notes ?? ""}
                    />
                  </div>
                  <div className="d-flex justify-content-end">
                    <button type="submit" className="btn btn-primary hstack gap-2">
                      <iconify-icon icon="solar:diskette-line-duotone" className="fs-4" />
                      Save Notes
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          {/* RIGHT: Status & Property */}
          <div className="col-lg-4">
            {/* Status Update */}
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-3 pb-2 border-bottom">
                  <iconify-icon icon="solar:flag-line-duotone" className="me-2" />
                  Lead Status
                </h5>

                {/* Current Status */}
                <div className="mb-4 text-center">
                  <span className={`badge ${STATUS_COLORS[inquiry.status ?? ""] ?? "bg-secondary-subtle text-secondary"} fs-4 px-4 py-2`}>
                    {ucfirst(inquiry.status)}
                  </span>
                </div>

                {/* Change Status */}
                <form action={updateInquiryStatus.bind(null, inquiry.id)} key={inquiry.status ?? ""}>
                  <div className="form-floating mb-3">
                    <select className="form-select" name="status" defaultValue={inquiry.status ?? ""}>
                      <option value="new">New</option>
                      <option value="contacted">Contacted</option>
                      <option value="qualified">Qualified</option>
                      <option value="closed">Closed</option>
                    </select>
                    <label>Update Status</label>
                  </div>
                  <button type="submit" className="btn btn-primary w-100 hstack gap-2 justify-content-center">
                    <iconify-icon icon="solar:refresh-line-duotone" className="fs-5" />
                    Update Status
                  </button>
                </form>
              </div>
            </div>

            {/* Quick Actions */}
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-3 pb-2 border-bottom">
                  <iconify-icon icon="solar:bolt-line-duotone" className="me-2" />
                  Quick Actions
                </h5>

                <div className="d-grid gap-2">
                  <a href={`mailto:${inquiry.email ?? ""}`} className="btn btn-outline-primary hstack gap-2 justify-content-center">
                    <iconify-icon icon="solar:letter-line-duotone" className="fs-5" />
                    Send Email
                  </a>

                  {inquiry.phone && (
                    <>
                      <a href={`tel:${inquiry.phone}`} className="btn btn-outline-success hstack gap-2 justify-content-center">
                        <iconify-icon icon="solar:phone-line-duotone" className="fs-5" />
                        Call Now
                      </a>

                      <a
                        href={`https://wa.me/${inquiry.phone.replace(/[^0-9]/g, "")}`}
                        target="_blank"
                        className="btn btn-outline-success hstack gap-2 justify-content-center"
                      >
                        <iconify-icon icon="solar:chat-round-like-line-duotone" className="fs-5" />
                        WhatsApp
                      </a>
                    </>
                  )}

                  <form action={deleteInquiry.bind(null, inquiry.id)} className="d-grid">
                    <ConfirmButton className="btn btn-outline-danger hstack gap-2 justify-content-center" message="Delete this inquiry?">
                      <iconify-icon icon="solar:trash-bin-trash-line-duotone" className="fs-5" />
                      Delete Inquiry
                    </ConfirmButton>
                  </form>
                </div>
              </div>
            </div>

            {/* Linked Property */}
            {inquiry.property_title && (
              <div className="card mb-4">
                <div className="card-body">
                  <h5 className="card-title mb-3 pb-2 border-bottom">
                    <iconify-icon icon="solar:buildings-line-duotone" className="me-2" />
                    Linked Property
                  </h5>

                  {inquiry.property_image && (
                    <img
                      src={asset(inquiry.property_image)}
                      alt={inquiry.property_title}
                      className="img-fluid rounded mb-3"
                      style={{ objectFit: "cover", height: 120, width: "100%" }}
                    />
                  )}

                  <p className="fw-semibold mb-1">{inquiry.property_title}</p>
                  <small className="text-muted">Ref: {inquiry.property_ref}</small>

                  <div className="mt-3">
                    <Link href={`/admin/properties/${inquiry.property_id}/edit`} className="btn btn-sm btn-outline-primary w-100">
                      <iconify-icon icon="solar:pen-line-duotone" className="me-1" />
                      View Property
                    </Link>
                  </div>
                </div>
              </div>
            )}

            {/* Back Button */}
            <Link href="/admin/inquiries" className="btn btn-outline-secondary w-100 hstack gap-2 justify-content-center">
              <iconify-icon icon="solar:arrow-left-line-duotone" className="fs-5" />
              Back to All Inquiries
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}
