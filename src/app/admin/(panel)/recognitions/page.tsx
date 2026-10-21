import Link from "next/link";
import { deleteRecognition, toggleRecognitionStatus } from "@/actions/admin/recognitions";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { listRecognitions } from "@/lib/admin/recognitions";
import { strLimit } from "@/lib/format";
import { asset } from "@/lib/url";
import { PageHeader } from "../_projects/PageHeader";

export default async function RecognitionsIndex() {
  const recognitions = await listRecognitions();

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Recognitions & Awards" crumbs={["Media & Content", "Recognitions"]} />

        <AdminFlash />

        {/* Table */}
        <div className="row">
          <div className="col-12">
            <div className="card">
              <div className="card-body">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <h4 className="card-title mb-0">
                    All Recognitions
                    <span className="badge bg-primary-subtle text-primary ms-2">{recognitions.length}</span>
                  </h4>
                  <Link href="/admin/recognitions/create" className="btn btn-primary hstack gap-2">
                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-4" />
                    Add Recognition
                  </Link>
                </div>

                <div className="table-responsive">
                  <table className="table table-hover align-middle mb-0">
                    <thead className="table-light">
                      <tr>
                        <th>#</th>
                        <th>Logo</th>
                        <th>Media Name</th>
                        <th>URL</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th className="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {recognitions.length === 0 ? (
                        <tr>
                          <td colSpan={7} className="text-center py-5 text-muted">
                            <iconify-icon icon="solar:medal-ribbons-star-line-duotone" class="fs-1 d-block mb-2" />
                            No recognitions found. <Link href="/admin/recognitions/create">Add one now</Link>
                          </td>
                        </tr>
                      ) : (
                        recognitions.map((recognition, i) => (
                          <tr key={recognition.id}>
                            <td>{i + 1}</td>
                            <td>
                              {recognition.logo ? (
                                <img
                                  src={asset(recognition.logo)}
                                  alt={recognition.media_name ?? ""}
                                  height={40}
                                  style={{ objectFit: "contain", maxWidth: 100 }}
                                />
                              ) : (
                                <div className="rounded bg-light d-flex align-items-center justify-content-center" style={{ width: 60, height: 40 }}>
                                  <iconify-icon icon="solar:medal-ribbons-star-line-duotone" class="text-muted fs-5" />
                                </div>
                              )}
                            </td>
                            <td>
                              <span className="fw-semibold">{recognition.media_name}</span>
                            </td>
                            <td>
                              {recognition.url ? (
                                <a href={recognition.url} target="_blank" className="text-primary">
                                  <iconify-icon icon="solar:link-line-duotone" class="me-1" />
                                  {strLimit(recognition.url, 35)}
                                </a>
                              ) : (
                                <span className="text-muted">—</span>
                              )}
                            </td>
                            <td>{recognition.sort_order}</td>
                            <td>
                              {recognition.is_active ? (
                                <span className="badge bg-success-subtle text-success">Active</span>
                              ) : (
                                <span className="badge bg-danger-subtle text-danger">Inactive</span>
                              )}
                            </td>
                            <td className="text-center">
                              <div className="d-flex align-items-center justify-content-center gap-2">
                                <form action={toggleRecognitionStatus.bind(null, recognition.id)}>
                                  <button
                                    type="submit"
                                    className={`btn btn-sm ${recognition.is_active ? "btn-warning" : "btn-success"}`}
                                    title={recognition.is_active ? "Deactivate" : "Activate"}
                                  >
                                    <iconify-icon icon={recognition.is_active ? "solar:eye-closed-line-duotone" : "solar:eye-line-duotone"} />
                                  </button>
                                </form>
                                <Link href={`/admin/recognitions/${recognition.id}/edit`} className="btn btn-sm btn-info" title="Edit">
                                  <iconify-icon icon="solar:pen-line-duotone" />
                                </Link>
                                <form action={deleteRecognition.bind(null, recognition.id)}>
                                  <ConfirmButton className="btn btn-sm btn-danger" message={`Delete ${recognition.media_name ?? ""}?`}>
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
