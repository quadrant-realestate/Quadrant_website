import Link from "next/link";
import { deleteInquiry } from "@/actions/admin/inquiries";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { listInquiries } from "@/lib/admin/inquiries";
import { formatDate, strLimit, ucfirst } from "@/lib/format";

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

type Search = Record<string, string | string[] | undefined>;
const one = (v: string | string[] | undefined) => (Array.isArray(v) ? v[0] : v) ?? "";

// InquiryController@index → admin/inquiries/index.blade.php
export default async function InquiriesPage({ searchParams }: { searchParams: Promise<Search> }) {
  const sp = await searchParams;
  const filters = { search: one(sp.search).trim(), status: one(sp.status), inquiry_type: one(sp.inquiry_type) };
  const { inquiries, counts } = await listInquiries(filters);

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
                    <h4 className="mb-4 mb-sm-0 card-title">Inquiries</h4>
                    <nav aria-label="breadcrumb" className="ms-auto">
                      <ol className="breadcrumb">
                        <li className="breadcrumb-item d-flex align-items-center">
                          <a className="text-muted text-decoration-none d-flex">
                            <iconify-icon icon="solar:home-2-line-duotone" className="fs-6" />
                          </a>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Leads &amp; CRM</span>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Inquiries</span>
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

        {/* Status Count Cards */}
        <div className="row mb-3">
          <div className="col-md-3 col-6">
            <div className="card text-center border-0 shadow-sm">
              <div className="card-body py-3">
                <h3 className="fw-bold text-primary mb-0">{counts.all}</h3>
                <small className="text-muted">Total</small>
              </div>
            </div>
          </div>
          <div className="col-md-3 col-6">
            <div className="card text-center border-0 shadow-sm">
              <div className="card-body py-3">
                <h3 className="fw-bold text-danger mb-0">{counts.new}</h3>
                <small className="text-muted">New</small>
              </div>
            </div>
          </div>
          <div className="col-md-3 col-6">
            <div className="card text-center border-0 shadow-sm">
              <div className="card-body py-3">
                <h3 className="fw-bold text-warning mb-0">{counts.contacted}</h3>
                <small className="text-muted">Contacted</small>
              </div>
            </div>
          </div>
          <div className="col-md-3 col-6">
            <div className="card text-center border-0 shadow-sm">
              <div className="card-body py-3">
                <h3 className="fw-bold text-success mb-0">{counts.qualified}</h3>
                <small className="text-muted">Qualified</small>
              </div>
            </div>
          </div>
        </div>

        {/* Filters */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-3">
              <div className="card-body py-3">
                {/* key: reset the uncontrolled fields when the query string changes (e.g. sidebar links) */}
                <form method="GET" action="/admin/inquiries" key={JSON.stringify(filters)}>
                  <div className="row g-2 align-items-end">
                    {/* Search */}
                    <div className="col-md-4">
                      <label className="form-label fw-semibold">Search</label>
                      <input type="text" className="form-control" name="search" placeholder="Name, Email or Phone..." defaultValue={one(sp.search)} />
                    </div>

                    {/* Status */}
                    <div className="col-md-3">
                      <label className="form-label fw-semibold">Status</label>
                      <select className="form-select" name="status" defaultValue={filters.status}>
                        <option value="">All Status</option>
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="qualified">Qualified</option>
                        <option value="closed">Closed</option>
                      </select>
                    </div>

                    {/* Inquiry Type */}
                    <div className="col-md-3">
                      <label className="form-label fw-semibold">Inquiry Type</label>
                      <select className="form-select" name="inquiry_type" defaultValue={filters.inquiry_type}>
                        <option value="">All Types</option>
                        <option value="property">Property</option>
                        <option value="general">General</option>
                        <option value="valuation">Valuation</option>
                      </select>
                    </div>

                    {/* Buttons */}
                    <div className="col-md-2 d-flex gap-2">
                      <button type="submit" className="btn btn-primary w-100">
                        <iconify-icon icon="solar:filter-line-duotone" className="me-1" />
                        Filter
                      </button>
                      <Link href="/admin/inquiries" className="btn btn-outline-secondary w-100">
                        Reset
                      </Link>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        {/* Table */}
        <div className="row">
          <div className="col-12">
            <div className="card">
              <div className="card-body">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <h4 className="card-title mb-0">
                    All Inquiries
                    <span className="badge bg-primary-subtle text-primary ms-2">{inquiries.length}</span>
                  </h4>
                </div>

                <div className="table-responsive">
                  <table className="table table-hover align-middle mb-0">
                    <thead className="table-light">
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Type</th>
                        <th>Property</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th className="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {inquiries.length === 0 ? (
                        <tr>
                          <td colSpan={9} className="text-center py-5 text-muted">
                            <iconify-icon icon="solar:inbox-line-duotone" className="fs-1 d-block mb-2" />
                            No inquiries found.
                          </td>
                        </tr>
                      ) : (
                        inquiries.map((inquiry, i) => (
                          <tr key={inquiry.id} className={inquiry.status === "new" ? "table-warning" : ""}>
                            <td>{i + 1}</td>
                            <td>
                              <span className="fw-semibold d-block">{inquiry.name}</span>
                              {inquiry.status === "new" && (
                                <span className="badge bg-danger-subtle text-danger" style={{ fontSize: 10 }}>
                                  NEW
                                </span>
                              )}
                            </td>
                            <td>
                              <small className="d-block">
                                <iconify-icon icon="solar:letter-line-duotone" className="me-1" />
                                {inquiry.email}
                              </small>
                              {inquiry.phone && (
                                <small className="d-block text-muted">
                                  <iconify-icon icon="solar:phone-line-duotone" className="me-1" />
                                  {inquiry.phone}
                                </small>
                              )}
                            </td>
                            <td>
                              <span className={`badge ${TYPE_COLORS[inquiry.inquiry_type ?? ""] ?? "bg-secondary-subtle text-secondary"}`}>
                                {ucfirst(inquiry.inquiry_type ?? "general")}
                              </span>
                            </td>
                            <td>
                              {inquiry.property_title ? (
                                <>
                                  <small className="d-block fw-semibold">{strLimit(inquiry.property_title, 25)}</small>
                                  <small className="text-muted">{inquiry.property_ref}</small>
                                </>
                              ) : (
                                <span className="text-muted">—</span>
                              )}
                            </td>
                            <td>
                              <small className="text-muted">{strLimit(inquiry.message, 50)}</small>
                            </td>
                            <td>
                              <small>{formatDate(inquiry.created_at ?? new Date(), "d M Y")}</small>
                              <small className="d-block text-muted">{formatDate(inquiry.created_at ?? new Date(), "h:i A")}</small>
                            </td>
                            <td>
                              <span className={`badge ${STATUS_COLORS[inquiry.status ?? ""] ?? "bg-secondary-subtle text-secondary"}`}>
                                {ucfirst(inquiry.status)}
                              </span>
                            </td>
                            <td className="text-center">
                              <div className="d-flex align-items-center justify-content-center gap-2">
                                <Link href={`/admin/inquiries/${inquiry.id}`} className="btn btn-sm btn-primary" title="View">
                                  <iconify-icon icon="solar:eye-line-duotone" />
                                </Link>
                                <form action={deleteInquiry.bind(null, inquiry.id)} className="d-inline">
                                  <ConfirmButton className="btn btn-sm btn-danger" message={`Delete this inquiry from ${inquiry.name ?? ""}?`}>
                                    <iconify-icon icon="solar:trash-bin-trash-line-duotone" />
                                  </ConfirmButton>
                                </form>
                              </div>
                            </td>
                          </tr>
                        ))
                      )}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
