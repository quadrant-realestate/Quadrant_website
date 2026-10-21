import Link from "next/link";
import { deleteDevelopment, toggleDevelopmentFeatured, toggleDevelopmentStatus } from "@/actions/admin/developments";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { listDevelopments } from "@/lib/admin/developments";
import { monthYear } from "@/lib/admin/projects-format";
import { numberFormat, strLimit } from "@/lib/format";
import { asset } from "@/lib/url";
import { linkButton, PageHeader } from "../_projects/PageHeader";

const STATUS_COLORS: Record<string, string> = {
  upcoming: "bg-info-subtle text-info",
  launched: "bg-success-subtle text-success",
  under_construction: "bg-warning-subtle text-warning",
  completed: "bg-primary-subtle text-primary",
};
const STATUS_LABELS: Record<string, string> = {
  upcoming: "Upcoming",
  launched: "Launched",
  under_construction: "Under Construction",
  completed: "Completed",
};

export default async function DevelopmentsIndex({ searchParams }: { searchParams: Promise<Record<string, string | string[] | undefined>> }) {
  const sp = await searchParams;
  const search = typeof sp.search === "string" ? sp.search : "";
  const status = typeof sp.status === "string" ? sp.status : "";
  const developments = await listDevelopments({ search: search.trim(), status });

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Developments" crumbs={["Developments", "All"]} />

        <AdminFlash />

        {/* Filters */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-3">
              <div className="card-body py-3">
                <form method="GET" action="/admin/developments">
                  <div className="row g-2 align-items-end">
                    <div className="col-md-4">
                      <label className="form-label fw-semibold">Search</label>
                      <input type="text" className="form-control" name="search" placeholder="Title or Developer..." defaultValue={search} />
                    </div>

                    <div className="col-md-3">
                      <label className="form-label fw-semibold">Status</label>
                      <select className="form-select" name="status" defaultValue={status}>
                        <option value="">All Status</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="launched">Launched</option>
                        <option value="under_construction">Under Construction</option>
                        <option value="completed">Completed</option>
                      </select>
                    </div>

                    <div className="col-md-3 d-flex gap-2">
                      <button type="submit" className="btn btn-primary w-100">
                        <iconify-icon icon="solar:filter-line-duotone" class="me-1" />
                        Filter
                      </button>
                      <Link href="/admin/developments" className="btn btn-outline-secondary w-100">
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
                    All Developments
                    <span className="badge bg-primary-subtle text-primary ms-2">{developments.length}</span>
                  </h4>
                  <Link href="/admin/developments/create" className="btn btn-primary hstack gap-2">
                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-4" />
                    Add Development
                  </Link>
                </div>

                <div className="table-responsive">
                  <table className="table table-hover align-middle mb-0">
                    <thead className="table-light">
                      <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Developer</th>
                        <th>Community</th>
                        <th>Price From</th>
                        <th>Handover</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Active</th>
                        <th className="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {developments.length === 0 ? (
                        <tr>
                          <td colSpan={11} className="text-center py-5 text-muted">
                            <iconify-icon icon="solar:city-line-duotone" class="fs-1 d-block mb-2" />
                            No developments found. <Link href="/admin/developments/create">Add one now</Link>
                          </td>
                        </tr>
                      ) : (
                        developments.map((dev, i) => (
                          <tr key={dev.id}>
                            <td>{i + 1}</td>
                            <td>
                              {dev.main_image ? (
                                <img
                                  src={asset(dev.main_image)}
                                  alt={dev.title ?? ""}
                                  className="rounded"
                                  width={60}
                                  height={50}
                                  style={{ objectFit: "cover" }}
                                />
                              ) : (
                                <div className="rounded bg-light d-flex align-items-center justify-content-center" style={{ width: 60, height: 50 }}>
                                  <iconify-icon icon="solar:city-line-duotone" class="text-muted fs-5" />
                                </div>
                              )}
                            </td>
                            <td>
                              <span className="fw-semibold d-block">{strLimit(dev.title, 30)}</span>
                              {!!dev.total_units && <small className="text-muted">{dev.total_units} units</small>}
                            </td>
                            <td>
                              {dev.developer_logo && (
                                <img src={asset(dev.developer_logo)} alt={dev.developer_name ?? ""} height={25} className="me-1" />
                              )}
                              <small>{dev.developer_name ?? "—"}</small>
                            </td>
                            <td>
                              <small>{dev.community_name ?? "—"}</small>
                            </td>
                            <td>
                              {dev.price_from !== null ? (
                                <span className="fw-semibold">
                                  {dev.price_currency} {numberFormat(dev.price_from)}
                                </span>
                              ) : (
                                <span className="text-muted">—</span>
                              )}
                            </td>
                            <td>
                              {dev.handover_date ? <small>{monthYear(dev.handover_date)}</small> : <span className="text-muted">—</span>}
                            </td>
                            <td>
                              <span className={`badge ${STATUS_COLORS[dev.status ?? ""] ?? "bg-secondary-subtle text-secondary"}`}>
                                {STATUS_LABELS[dev.status ?? ""] ?? dev.status}
                              </span>
                            </td>
                            <td>
                              <form action={toggleDevelopmentFeatured.bind(null, dev.id)}>
                                <button type="submit" style={linkButton}>
                                  {dev.is_featured ? (
                                    <iconify-icon icon="solar:star-bold" class="fs-5 text-warning" />
                                  ) : (
                                    <iconify-icon icon="solar:star-line-duotone" class="fs-5 text-muted" />
                                  )}
                                </button>
                              </form>
                            </td>
                            <td>
                              <form action={toggleDevelopmentStatus.bind(null, dev.id)}>
                                <button type="submit" style={linkButton}>
                                  {dev.is_active ? (
                                    <span className="badge bg-success-subtle text-success">Active</span>
                                  ) : (
                                    <span className="badge bg-danger-subtle text-danger">Inactive</span>
                                  )}
                                </button>
                              </form>
                            </td>
                            <td className="text-center">
                              <div className="d-flex align-items-center justify-content-center gap-2">
                                <Link href={`/admin/developments/${dev.id}/edit`} className="btn btn-sm btn-info" title="Edit">
                                  <iconify-icon icon="solar:pen-line-duotone" />
                                </Link>
                                <form action={deleteDevelopment.bind(null, dev.id)}>
                                  <ConfirmButton className="btn btn-sm btn-danger" message={`Delete ${dev.title ?? ""}?`}>
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
