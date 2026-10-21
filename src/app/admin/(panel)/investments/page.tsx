import Link from "next/link";
import { deleteInvestment, toggleInvestmentFeatured, toggleInvestmentStatus } from "@/actions/admin/investments";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { activeCommunities } from "@/lib/admin/developments";
import { listInvestments } from "@/lib/admin/investments";
import { dec } from "@/lib/admin/projects-format";
import { numberFormat, strLimit } from "@/lib/format";
import { asset } from "@/lib/url";
import { linkButton, PageHeader } from "../_projects/PageHeader";

/** PHP truthiness of a varchar value ("" and "0" are false). */
const phpTruthy = (v: string | null) => v !== null && v !== "" && v !== "0";

export default async function InvestmentsIndex({ searchParams }: { searchParams: Promise<Record<string, string | string[] | undefined>> }) {
  const sp = await searchParams;
  const search = typeof sp.search === "string" ? sp.search : "";
  const communityId = typeof sp.community_id === "string" ? sp.community_id : "";
  const [investments, communities] = await Promise.all([listInvestments({ search: search.trim(), communityId }), activeCommunities()]);

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Investments" crumbs={["Investments", "All"]} />

        <AdminFlash />

        {/* Filters */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-3">
              <div className="card-body py-3">
                <form method="GET" action="/admin/investments">
                  <div className="row g-2 align-items-end">
                    <div className="col-md-4">
                      <label className="form-label fw-semibold">Search</label>
                      <input type="text" className="form-control" name="search" placeholder="Title or Investment Type..." defaultValue={search} />
                    </div>

                    <div className="col-md-4">
                      <label className="form-label fw-semibold">Community</label>
                      <select className="form-select" name="community_id" defaultValue={communityId}>
                        <option value="">All Communities</option>
                        {communities.map((c) => (
                          <option key={c.id} value={c.id}>
                            {c.name}
                          </option>
                        ))}
                      </select>
                    </div>

                    <div className="col-md-4 d-flex gap-2">
                      <button type="submit" className="btn btn-primary w-100">
                        <iconify-icon icon="solar:filter-line-duotone" class="me-1" />
                        Filter
                      </button>
                      <Link href="/admin/investments" className="btn btn-outline-secondary w-100">
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
                    All Investments
                    <span className="badge bg-primary-subtle text-primary ms-2">{investments.length}</span>
                  </h4>
                  <Link href="/admin/investments/create" className="btn btn-primary hstack gap-2">
                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-4" />
                    Add Investment
                  </Link>
                </div>

                <div className="table-responsive">
                  <table className="table table-hover align-middle mb-0">
                    <thead className="table-light">
                      <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>ROI %</th>
                        <th>Gross Yield</th>
                        <th>Annual Rental</th>
                        <th>Community</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th className="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {investments.length === 0 ? (
                        <tr>
                          <td colSpan={12} className="text-center py-5 text-muted">
                            <iconify-icon icon="solar:chart-line-duotone" class="fs-1 d-block mb-2" />
                            No investments found. <Link href="/admin/investments/create">Add one now</Link>
                          </td>
                        </tr>
                      ) : (
                        investments.map((investment, i) => (
                          <tr key={investment.id}>
                            <td>{i + 1}</td>
                            <td>
                              {investment.main_image ? (
                                <img
                                  src={asset(investment.main_image)}
                                  alt={investment.title ?? ""}
                                  className="rounded"
                                  width={60}
                                  height={50}
                                  style={{ objectFit: "cover" }}
                                />
                              ) : (
                                <div className="rounded bg-light d-flex align-items-center justify-content-center" style={{ width: 60, height: 50 }}>
                                  <iconify-icon icon="solar:chart-line-duotone" class="text-muted fs-5" />
                                </div>
                              )}
                            </td>
                            <td>
                              <span className="fw-semibold d-block">{strLimit(investment.title, 30)}</span>
                              {investment.property_title && <small className="text-muted">{strLimit(investment.property_title, 25)}</small>}
                            </td>
                            <td>
                              {investment.investment_type ? (
                                <span className="badge bg-info-subtle text-info">{investment.investment_type}</span>
                              ) : (
                                <span className="text-muted">—</span>
                              )}
                            </td>
                            <td>
                              {investment.price !== null ? (
                                <span className="fw-semibold">
                                  {investment.price_currency} {numberFormat(investment.price)}
                                </span>
                              ) : (
                                <span className="text-muted">—</span>
                              )}
                            </td>
                            <td>
                              {investment.roi_percentage !== null ? (
                                <span className="badge bg-success-subtle text-success fw-semibold">{dec(investment.roi_percentage)}%</span>
                              ) : (
                                <span className="text-muted">—</span>
                              )}
                            </td>
                            <td>{investment.gross_yield !== null ? `${dec(investment.gross_yield)}%` : <span className="text-muted">—</span>}</td>
                            <td>
                              {phpTruthy(investment.annual_rental) ? (
                                `${investment.price_currency ?? ""} ${numberFormat(investment.annual_rental)}`
                              ) : (
                                <span className="text-muted">—</span>
                              )}
                            </td>
                            <td>
                              <small>{investment.community_name ?? "—"}</small>
                            </td>
                            <td>
                              <form action={toggleInvestmentFeatured.bind(null, investment.id)}>
                                <button type="submit" style={linkButton}>
                                  {investment.is_featured ? (
                                    <iconify-icon icon="solar:star-bold" class="fs-5 text-warning" />
                                  ) : (
                                    <iconify-icon icon="solar:star-line-duotone" class="fs-5 text-muted" />
                                  )}
                                </button>
                              </form>
                            </td>
                            <td>
                              <form action={toggleInvestmentStatus.bind(null, investment.id)}>
                                <button type="submit" style={linkButton}>
                                  {investment.is_active ? (
                                    <span className="badge bg-success-subtle text-success">Active</span>
                                  ) : (
                                    <span className="badge bg-danger-subtle text-danger">Inactive</span>
                                  )}
                                </button>
                              </form>
                            </td>
                            <td className="text-center">
                              <div className="d-flex align-items-center justify-content-center gap-2">
                                <Link href={`/admin/investments/${investment.id}/edit`} className="btn btn-sm btn-info" title="Edit">
                                  <iconify-icon icon="solar:pen-line-duotone" />
                                </Link>
                                <form action={deleteInvestment.bind(null, investment.id)}>
                                  <ConfirmButton className="btn btn-sm btn-danger" message={`Delete ${investment.title ?? ""}?`}>
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
