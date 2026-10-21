import Link from "next/link";
import { getAdmin } from "@/lib/auth";
import { getDashboardData } from "@/lib/admin/dashboard";
import { formatDate, numberFormat, strLimit, ucfirst } from "@/lib/format";
import { asset } from "@/lib/url";

// AdminController@dashboard → admin/index.blade.php
// (the theme's dashboard.js charts target #revenue-forecast / #total-income, which this view never had.)

const DAYS_LONG = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

const LISTING_COLORS: Record<string, string> = {
  sale: "bg-success-subtle text-success",
  rent: "bg-info-subtle text-info",
  off_plan: "bg-warning-subtle text-warning",
  international: "bg-primary-subtle text-primary",
  private: "bg-danger-subtle text-danger",
};
const LISTING_LABELS: Record<string, string> = {
  sale: "Sale",
  rent: "Rent",
  off_plan: "Off Plan",
  international: "Intl",
  private: "Private",
};
const PROPERTY_STATUS_COLORS: Record<string, string> = {
  active: "bg-success-subtle text-success",
  sold: "bg-danger-subtle text-danger",
  rented: "bg-info-subtle text-info",
  reserved: "bg-warning-subtle text-warning",
  inactive: "bg-secondary-subtle text-secondary",
};
const INQUIRY_TYPE_COLORS: Record<string, string> = {
  general: "bg-secondary-subtle text-secondary",
  buy: "bg-success-subtle text-success",
  rent: "bg-info-subtle text-info",
  sell: "bg-warning-subtle text-warning",
  valuation: "bg-primary-subtle text-primary",
  callback: "bg-danger-subtle text-danger",
};
const INQUIRY_STATUS_COLORS: Record<string, string> = {
  new: "bg-danger-subtle text-danger",
  contacted: "bg-warning-subtle text-warning",
  qualified: "bg-success-subtle text-success",
  closed: "bg-secondary-subtle text-secondary",
  lost: "bg-dark-subtle text-dark",
};

const pct = (n: number, total: number) => `${total > 0 ? (n / total) * 100 : 0}%`;

export default async function DashboardPage() {
  const [admin, data] = await Promise.all([getAdmin(), getDashboardData()]);
  const { counts: c, recentInquiries, recentProperties, devByStatus } = data;
  const now = new Date();

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
                    <div>
                      <h4 className="mb-1 card-title">Welcome back, {admin?.name}</h4>
                      <p className="text-muted mb-0 fs-2">
                        <iconify-icon icon="solar:calendar-line-duotone" className="me-1" />
                        {DAYS_LONG[now.getDay()]}, {formatDate(now, "d F Y")}
                      </p>
                    </div>
                    <nav aria-label="breadcrumb" className="ms-auto">
                      <ol className="breadcrumb">
                        <li className="breadcrumb-item d-flex align-items-center">
                          <a className="text-muted text-decoration-none d-flex">
                            <iconify-icon icon="solar:home-2-line-duotone" className="fs-6" />
                          </a>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Dashboard</span>
                        </li>
                      </ol>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ROW 1: Main Stat Cards */}
        <div className="row mt-2">
          {/* Total Properties */}
          <div className="col-lg-3 col-md-6">
            <div className="card">
              <div className="card-body">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <div className="rounded p-2" style={{ background: "#e8f0fa" }}>
                    <iconify-icon icon="solar:buildings-line-duotone" className="fs-6" style={{ color: "#214A8B" }} />
                  </div>
                  <Link href="/admin/properties" className="text-muted fs-2">
                    View All
                  </Link>
                </div>
                <h3 className="fw-bold mb-1">{c.totalProperties}</h3>
                <p className="text-muted mb-2 fs-2">Total Properties</p>
                <div className="d-flex gap-3">
                  <small className="badge bg-success-subtle text-success">{c.activeProperties} Active</small>
                  <small className="badge bg-danger-subtle text-danger">{c.soldProperties} Sold</small>
                </div>
              </div>
            </div>
          </div>

          {/* New Inquiries */}
          <div className="col-lg-3 col-md-6">
            <div className="card">
              <div className="card-body">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <div className="rounded p-2" style={{ background: "#fde8e8" }}>
                    <iconify-icon icon="solar:inbox-line-line-duotone" className="fs-6" style={{ color: "#e53e3e" }} />
                  </div>
                  <Link href="/admin/inquiries" className="text-muted fs-2">
                    View All
                  </Link>
                </div>
                <h3 className="fw-bold mb-1">{c.newInquiries}</h3>
                <p className="text-muted mb-2 fs-2">New Inquiries</p>
                <div className="d-flex gap-3">
                  <small className="badge bg-warning-subtle text-warning">{c.contactedInquiries} Contacted</small>
                  <small className="badge bg-success-subtle text-success">{c.qualifiedInquiries} Qualified</small>
                </div>
              </div>
            </div>
          </div>

          {/* Developments */}
          <div className="col-lg-3 col-md-6">
            <div className="card">
              <div className="card-body">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <div className="rounded p-2" style={{ background: "#e8faf0" }}>
                    <iconify-icon icon="solar:city-line-duotone" className="fs-6" style={{ color: "#38a169" }} />
                  </div>
                  <Link href="/admin/developments" className="text-muted fs-2">
                    View All
                  </Link>
                </div>
                <h3 className="fw-bold mb-1">{c.totalDevelopments}</h3>
                <p className="text-muted mb-2 fs-2">Developments</p>
                <div className="d-flex gap-2 flex-wrap">
                  {devByStatus.map((dev, i) => (
                    <small className="badge bg-primary-subtle text-primary" key={i}>
                      {ucfirst((dev.status ?? "").replace(/_/g, " "))}: {dev.total}
                    </small>
                  ))}
                </div>
              </div>
            </div>
          </div>

          {/* Communities */}
          <div className="col-lg-3 col-md-6">
            <div className="card">
              <div className="card-body">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <div className="rounded p-2" style={{ background: "#faf3e8" }}>
                    <iconify-icon icon="solar:map-point-wave-line-duotone" className="fs-6" style={{ color: "#D4AF37" }} />
                  </div>
                  <Link href="/admin/communities" className="text-muted fs-2">
                    View All
                  </Link>
                </div>
                <h3 className="fw-bold mb-1">{c.totalCommunities}</h3>
                <p className="text-muted mb-2 fs-2">Communities</p>
                <div className="d-flex gap-3">
                  <small className="badge bg-info-subtle text-info">{c.totalInvestments} Investments</small>
                  <small className="badge bg-warning-subtle text-warning">{c.totalBrandedRes} Branded</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ROW 2: Listing Breakdown + Inquiry Pipeline */}
        <div className="row">
          {/* Listing Type Breakdown */}
          <div className="col-lg-4">
            <div className="card h-100">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:pie-chart-line-duotone" className="me-2" />
                  Listings by Type
                </h5>

                <div className="mb-3">
                  <div className="d-flex justify-content-between mb-1">
                    <span className="fw-semibold">For Sale</span>
                    <span className="badge bg-success-subtle text-success">{c.forSale}</span>
                  </div>
                  <div className="progress" style={{ height: 8 }}>
                    <div className="progress-bar bg-success" style={{ width: pct(c.forSale, c.totalProperties) }} />
                  </div>
                </div>

                <div className="mb-3">
                  <div className="d-flex justify-content-between mb-1">
                    <span className="fw-semibold">For Rent</span>
                    <span className="badge bg-info-subtle text-info">{c.forRent}</span>
                  </div>
                  <div className="progress" style={{ height: 8 }}>
                    <div className="progress-bar bg-info" style={{ width: pct(c.forRent, c.totalProperties) }} />
                  </div>
                </div>

                <div className="mb-3">
                  <div className="d-flex justify-content-between mb-1">
                    <span className="fw-semibold">Off Plan</span>
                    <span className="badge bg-warning-subtle text-warning">{c.offPlan}</span>
                  </div>
                  <div className="progress" style={{ height: 8 }}>
                    <div className="progress-bar bg-warning" style={{ width: pct(c.offPlan, c.totalProperties) }} />
                  </div>
                </div>

                <hr />

                <div className="row text-center mt-3">
                  <div className="col-4">
                    <h5 className="fw-bold text-primary mb-0">{c.featuredProperties}</h5>
                    <small className="text-muted">Featured</small>
                  </div>
                  <div className="col-4">
                    <h5 className="fw-bold text-warning mb-0">{c.rentedProperties}</h5>
                    <small className="text-muted">Rented</small>
                  </div>
                  <div className="col-4">
                    <h5 className="fw-bold text-danger mb-0">{c.soldProperties}</h5>
                    <small className="text-muted">Sold</small>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Inquiry Pipeline */}
          <div className="col-lg-4">
            <div className="card h-100">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:chart-line-duotone" className="me-2" />
                  Inquiry Pipeline
                </h5>

                <div className="d-flex flex-column gap-3">
                  <div className="d-flex align-items-center justify-content-between p-3 rounded" style={{ background: "#fde8e8" }}>
                    <div className="d-flex align-items-center gap-2">
                      <iconify-icon icon="solar:letter-unopened-line-duotone" className="fs-5 text-danger" />
                      <span className="fw-semibold">New</span>
                    </div>
                    <h4 className="fw-bold text-danger mb-0">{c.newInquiries}</h4>
                  </div>

                  <div className="d-flex align-items-center justify-content-between p-3 rounded" style={{ background: "#fef9e8" }}>
                    <div className="d-flex align-items-center gap-2">
                      <iconify-icon icon="solar:phone-calling-line-duotone" className="fs-5 text-warning" />
                      <span className="fw-semibold">Contacted</span>
                    </div>
                    <h4 className="fw-bold text-warning mb-0">{c.contactedInquiries}</h4>
                  </div>

                  <div className="d-flex align-items-center justify-content-between p-3 rounded" style={{ background: "#e8faf0" }}>
                    <div className="d-flex align-items-center gap-2">
                      <iconify-icon icon="solar:verified-check-line-duotone" className="fs-5 text-success" />
                      <span className="fw-semibold">Qualified</span>
                    </div>
                    <h4 className="fw-bold text-success mb-0">{c.qualifiedInquiries}</h4>
                  </div>

                  <div className="d-flex align-items-center justify-content-between p-3 rounded" style={{ background: "#f0f0f0" }}>
                    <div className="d-flex align-items-center gap-2">
                      <iconify-icon icon="solar:archive-line-duotone" className="fs-5 text-secondary" />
                      <span className="fw-semibold">Total</span>
                    </div>
                    <h4 className="fw-bold text-secondary mb-0">{c.totalInquiries}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Quick Actions */}
          <div className="col-lg-4">
            <div className="card h-100">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:bolt-line-duotone" className="me-2" />
                  Quick Actions
                </h5>

                <div className="d-grid gap-2">
                  <Link href="/admin/properties/create" className="btn btn-primary hstack gap-3">
                    <iconify-icon icon="solar:add-circle-line-duotone" className="fs-5" />
                    Add New Property
                  </Link>

                  <Link href="/admin/developments/create" className="btn btn-outline-primary hstack gap-3">
                    <iconify-icon icon="solar:city-line-duotone" className="fs-5" />
                    Add Development
                  </Link>

                  <Link href="/admin/communities/create" className="btn btn-outline-secondary hstack gap-3">
                    <iconify-icon icon="solar:map-point-wave-line-duotone" className="fs-5" />
                    Add Community
                  </Link>

                  <Link href="/admin/inquiries" className="btn btn-outline-danger hstack gap-3 justify-content-between">
                    <div className="hstack gap-3">
                      <iconify-icon icon="solar:inbox-line-line-duotone" className="fs-5" />
                      View All Inquiries
                    </div>
                    {c.newInquiries > 0 && <span className="badge bg-danger rounded-pill">{c.newInquiries} New</span>}
                  </Link>

                  <Link href="/admin/investments/create" className="btn btn-outline-success hstack gap-3">
                    <iconify-icon icon="solar:chart-line-duotone" className="fs-5" />
                    Add Investment
                  </Link>

                  <Link href="/admin/settings" className="btn btn-outline-secondary hstack gap-3">
                    <iconify-icon icon="solar:settings-line-duotone" className="fs-5" />
                    Site Settings
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ROW 3: Recent Properties */}
        <div className="row">
          <div className="col-12">
            <div className="card">
              <div className="card-body">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <h5 className="card-title mb-0">
                    <iconify-icon icon="solar:buildings-line-duotone" className="me-2" />
                    Recent Properties
                  </h5>
                  <Link href="/admin/properties" className="btn btn-sm btn-outline-primary">
                    View All
                  </Link>
                </div>

                <div className="table-responsive">
                  <table className="table table-hover align-middle mb-0">
                    <thead className="table-light">
                      <tr>
                        <th>Property</th>
                        <th>Reference</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Beds</th>
                        <th>Community</th>
                        <th>Status</th>
                        <th>Added</th>
                      </tr>
                    </thead>
                    <tbody>
                      {recentProperties.length === 0 ? (
                        <tr>
                          <td colSpan={8} className="text-center py-4 text-muted">
                            No properties yet.
                          </td>
                        </tr>
                      ) : (
                        recentProperties.map((property) => (
                          <tr key={property.id}>
                            <td>
                              <div className="d-flex align-items-center gap-2">
                                {property.main_image ? (
                                  <img src={asset(property.main_image)} className="rounded" width={45} height={40} style={{ objectFit: "cover" }} alt="" />
                                ) : (
                                  <div className="rounded bg-light d-flex align-items-center justify-content-center" style={{ width: 45, height: 40 }}>
                                    <iconify-icon icon="solar:buildings-line-duotone" className="text-muted" />
                                  </div>
                                )}
                                <span className="fw-semibold">{strLimit(property.title, 28)}</span>
                              </div>
                            </td>
                            <td>
                              <small className="text-muted">{property.reference_no}</small>
                            </td>
                            <td>
                              <span className={`badge ${LISTING_COLORS[property.listing_type ?? ""] ?? "bg-secondary-subtle text-secondary"}`}>
                                {LISTING_LABELS[property.listing_type ?? ""] ?? property.listing_type}
                              </span>
                            </td>
                            <td>
                              {property.price_on_request ? (
                                <small className="text-muted">On Request</small>
                              ) : (
                                <small className="fw-semibold">
                                  {property.price_currency} {numberFormat(property.price)}
                                </small>
                              )}
                            </td>
                            <td>{property.bedrooms !== null ? `${property.bedrooms} BR` : <span className="text-muted">—</span>}</td>
                            <td>
                              <small>{property.community_name ?? "—"}</small>
                            </td>
                            <td>
                              <span className={`badge ${PROPERTY_STATUS_COLORS[property.status ?? ""] ?? "bg-secondary-subtle text-secondary"}`}>
                                {ucfirst(property.status)}
                              </span>
                            </td>
                            <td>
                              <small className="text-muted">{formatDate(property.created_at ?? new Date(), "d M Y")}</small>
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

        {/* ROW 4: Recent Inquiries */}
        <div className="row">
          <div className="col-12">
            <div className="card">
              <div className="card-body">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <h5 className="card-title mb-0">
                    <iconify-icon icon="solar:inbox-line-line-duotone" className="me-2" />
                    Recent Inquiries
                    {c.newInquiries > 0 && <span className="badge bg-danger ms-2">{c.newInquiries} New</span>}
                  </h5>
                  <Link href="/admin/inquiries" className="btn btn-sm btn-outline-primary">
                    View All
                  </Link>
                </div>

                <div className="table-responsive">
                  <table className="table table-hover align-middle mb-0">
                    <thead className="table-light">
                      <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Type</th>
                        <th>Property</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th className="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      {recentInquiries.length === 0 ? (
                        <tr>
                          <td colSpan={8} className="text-center py-4 text-muted">
                            No inquiries yet.
                          </td>
                        </tr>
                      ) : (
                        recentInquiries.map((inquiry) => (
                          <tr key={inquiry.id} className={inquiry.status === "new" ? "table-warning" : ""}>
                            <td>
                              <span className="fw-semibold d-block">{inquiry.name}</span>
                              {inquiry.status === "new" && (
                                <span className="badge bg-danger-subtle text-danger" style={{ fontSize: 10 }}>
                                  NEW
                                </span>
                              )}
                            </td>
                            <td>
                              <small className="d-block">{inquiry.email}</small>
                              {inquiry.phone && <small className="text-muted">{inquiry.phone}</small>}
                            </td>
                            <td>
                              <span className={`badge ${INQUIRY_TYPE_COLORS[inquiry.inquiry_type ?? ""] ?? "bg-secondary-subtle text-secondary"}`}>
                                {ucfirst(inquiry.inquiry_type)}
                              </span>
                            </td>
                            <td>
                              {inquiry.property_title ? <small>{strLimit(inquiry.property_title, 20)}</small> : <span className="text-muted">—</span>}
                            </td>
                            <td>
                              <small className="text-muted">{strLimit(inquiry.message, 40)}</small>
                            </td>
                            <td>
                              <small>{formatDate(inquiry.created_at ?? new Date(), "d M")}</small>
                              <small className="d-block text-muted">{formatDate(inquiry.created_at ?? new Date(), "h:i A")}</small>
                            </td>
                            <td>
                              <span className={`badge ${INQUIRY_STATUS_COLORS[inquiry.status ?? ""] ?? "bg-secondary-subtle text-secondary"}`}>
                                {ucfirst(inquiry.status)}
                              </span>
                            </td>
                            <td className="text-center">
                              <Link href={`/admin/inquiries/${inquiry.id}`} className="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:eye-line-duotone" />
                              </Link>
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
