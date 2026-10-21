import Link from "next/link";
import { deleteBrandedResidence, toggleBrandedResidenceFeatured, toggleBrandedResidenceStatus } from "@/actions/admin/branded-residences";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { listBrandedResidences } from "@/lib/admin/branded-residences";
import { activeCommunities } from "@/lib/admin/developments";
import { numberFormat, strLimit } from "@/lib/format";
import { asset } from "@/lib/url";
import { linkButton, PageHeader } from "../_projects/PageHeader";

export default async function BrandedResidencesIndex({
  searchParams,
}: {
  searchParams: Promise<Record<string, string | string[] | undefined>>;
}) {
  const sp = await searchParams;
  const search = typeof sp.search === "string" ? sp.search : "";
  const communityId = typeof sp.community_id === "string" ? sp.community_id : "";
  const [residences, communities] = await Promise.all([
    listBrandedResidences({ search: search.trim(), communityId }),
    activeCommunities(),
  ]);

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Branded Residences" crumbs={["Branded Residences", "All"]} />

        <AdminFlash />

        {/* Filters */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-3">
              <div className="card-body py-3">
                <form method="GET" action="/admin/branded-residences">
                  <div className="row g-2 align-items-end">
                    <div className="col-md-4">
                      <label className="form-label fw-semibold">Search</label>
                      <input type="text" className="form-control" name="search" placeholder="Title or Brand Name..." defaultValue={search} />
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
                      <Link href="/admin/branded-residences" className="btn btn-outline-secondary w-100">
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
                    All Branded Residences
                    <span className="badge bg-primary-subtle text-primary ms-2">{residences.length}</span>
                  </h4>
                  <Link href="/admin/branded-residences/create" className="btn btn-primary hstack gap-2">
                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-4" />
                    Add Residence
                  </Link>
                </div>

                <div className="table-responsive">
                  <table className="table table-hover align-middle mb-0">
                    <thead className="table-light">
                      <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Brand</th>
                        <th>Title</th>
                        <th>Community</th>
                        <th>Price From</th>
                        <th>Linked Property</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th className="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {residences.length === 0 ? (
                        <tr>
                          <td colSpan={10} className="text-center py-5 text-muted">
                            <iconify-icon icon="solar:crown-line-duotone" class="fs-1 d-block mb-2" />
                            No branded residences found. <Link href="/admin/branded-residences/create">Add one now</Link>
                          </td>
                        </tr>
                      ) : (
                        residences.map((residence, i) => (
                          <tr key={residence.id}>
                            <td>{i + 1}</td>
                            <td>
                              {residence.main_image ? (
                                <img
                                  src={asset(residence.main_image)}
                                  alt={residence.title ?? ""}
                                  className="rounded"
                                  width={60}
                                  height={50}
                                  style={{ objectFit: "cover" }}
                                />
                              ) : (
                                <div className="rounded bg-light d-flex align-items-center justify-content-center" style={{ width: 60, height: 50 }}>
                                  <iconify-icon icon="solar:crown-line-duotone" class="text-muted fs-5" />
                                </div>
                              )}
                            </td>
                            <td>
                              <div className="d-flex align-items-center gap-2">
                                {residence.brand_logo && (
                                  <img src={asset(residence.brand_logo)} alt={residence.brand_name ?? ""} height={28} style={{ objectFit: "contain" }} />
                                )}
                                <span className="fw-semibold">{residence.brand_name}</span>
                              </div>
                            </td>
                            <td>
                              <span className="d-block">{strLimit(residence.title, 30)}</span>
                              <small className="text-muted">
                                <code>{residence.slug}</code>
                              </small>
                            </td>
                            <td>
                              <small>{residence.community_name ?? "—"}</small>
                            </td>
                            <td>
                              {residence.price_from !== null ? (
                                <span className="fw-semibold">AED {numberFormat(residence.price_from)}</span>
                              ) : (
                                <span className="text-muted">—</span>
                              )}
                            </td>
                            <td>
                              {residence.property_title ? (
                                <small className="badge bg-secondary-subtle text-secondary">{strLimit(residence.property_title, 20)}</small>
                              ) : (
                                <span className="text-muted">—</span>
                              )}
                            </td>
                            <td>
                              <form action={toggleBrandedResidenceFeatured.bind(null, residence.id)}>
                                <button type="submit" style={linkButton}>
                                  {residence.is_featured ? (
                                    <iconify-icon icon="solar:star-bold" class="fs-5 text-warning" />
                                  ) : (
                                    <iconify-icon icon="solar:star-line-duotone" class="fs-5 text-muted" />
                                  )}
                                </button>
                              </form>
                            </td>
                            <td>
                              <form action={toggleBrandedResidenceStatus.bind(null, residence.id)}>
                                <button type="submit" style={linkButton}>
                                  {residence.is_active ? (
                                    <span className="badge bg-success-subtle text-success">Active</span>
                                  ) : (
                                    <span className="badge bg-danger-subtle text-danger">Inactive</span>
                                  )}
                                </button>
                              </form>
                            </td>
                            <td className="text-center">
                              <div className="d-flex align-items-center justify-content-center gap-2">
                                <Link href={`/admin/branded-residences/${residence.id}/edit`} className="btn btn-sm btn-info" title="Edit">
                                  <iconify-icon icon="solar:pen-line-duotone" />
                                </Link>
                                <form action={deleteBrandedResidence.bind(null, residence.id)}>
                                  <ConfirmButton className="btn btn-sm btn-danger" message={`Delete ${residence.title ?? ""}?`}>
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
