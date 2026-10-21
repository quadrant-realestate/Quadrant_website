import Link from "next/link";
import { deletePropertyType, togglePropertyTypeStatus } from "@/actions/admin/property-types";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { DataTable } from "@/components/admin/DataTable";
import { listPropertyTypes } from "@/lib/admin/property-types";

// PropertyTypeController@index → admin/property_types/index.blade.php
export default async function PropertyTypesPage() {
  const types = await listPropertyTypes();

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
                    <h4 className="mb-4 mb-sm-0 card-title">Property Types</h4>
                    <nav aria-label="breadcrumb" className="ms-auto">
                      <ol className="breadcrumb">
                        <li className="breadcrumb-item d-flex align-items-center">
                          <a className="text-muted text-decoration-none d-flex">
                            <iconify-icon icon="solar:home-2-line-duotone" className="fs-6" />
                          </a>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Property Types</span>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">All</span>
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

        {/* Table */}
        <div className="row">
          <div className="col-12">
            <div className="card">
              <div className="card-body">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <h4 className="card-title mb-0">All Property Types</h4>
                  <Link href="/admin/property-types/create" className="btn btn-primary hstack gap-2">
                    <iconify-icon icon="solar:add-circle-line-duotone" className="fs-4" />
                    Add New Type
                  </Link>
                </div>

                <div className="table-responsive">
                  <DataTable
                    columns={[
                      { label: "#" },
                      { label: "Name" },
                      { label: "Slug" },
                      { label: "Sort Order" },
                      { label: "Properties" },
                      { label: "Status" },
                      { label: "Actions", className: "text-center" },
                    ]}
                    rows={types.map((type, i) => ({
                      key: type.id,
                      values: [i + 1, type.name, type.slug, type.sort_order, Number(type.properties_count), type.is_active ? "Active" : "Inactive", null],
                      cells: [
                        i + 1,
                        <span className="fw-semibold" key="n">
                          {type.name}
                        </span>,
                        <code key="s">{type.slug}</code>,
                        type.sort_order,
                        <span className="badge bg-primary-subtle text-primary" key="c">
                          {Number(type.properties_count)} properties
                        </span>,
                        type.is_active ? (
                          <span className="badge bg-success-subtle text-success" key="st">
                            Active
                          </span>
                        ) : (
                          <span className="badge bg-danger-subtle text-danger" key="st">
                            Inactive
                          </span>
                        ),
                        <div className="d-flex align-items-center justify-content-center gap-2" key="a">
                          <form action={togglePropertyTypeStatus.bind(null, type.id)} className="d-inline">
                            <button
                              type="submit"
                              className={`btn btn-sm ${type.is_active ? "btn-warning" : "btn-success"}`}
                              title={type.is_active ? "Deactivate" : "Activate"}
                            >
                              <iconify-icon icon={type.is_active ? "solar:eye-closed-line-duotone" : "solar:eye-line-duotone"} />
                            </button>
                          </form>
                          <Link href={`/admin/property-types/${type.id}/edit`} className="btn btn-sm btn-info" title="Edit">
                            <iconify-icon icon="solar:pen-line-duotone" />
                          </Link>
                          <form action={deletePropertyType.bind(null, type.id)} className="d-inline">
                            <ConfirmButton className="btn btn-sm btn-danger" title="Delete" message={`Are you sure you want to delete ${type.name ?? ""}?`}>
                              <iconify-icon icon="solar:trash-bin-trash-line-duotone" />
                            </ConfirmButton>
                          </form>
                        </div>,
                      ],
                    }))}
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
