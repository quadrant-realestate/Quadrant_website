import Link from "next/link";
import { Fragment } from "react";
import { deleteAmenity } from "@/actions/admin/amenities";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { DataTable } from "@/components/admin/DataTable";
import { listAmenities } from "@/lib/admin/amenities";

const CATEGORY_COLORS: Record<string, string> = {
  Indoor: "bg-info-subtle text-info",
  Outdoor: "bg-success-subtle text-success",
  Building: "bg-warning-subtle text-warning",
  View: "bg-primary-subtle text-primary",
};

// AmenityController@index → admin/amenities/index.blade.php
export default async function AmenitiesPage() {
  const amenities = await listAmenities();

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
                    <h4 className="mb-4 mb-sm-0 card-title">Amenities</h4>
                    <nav aria-label="breadcrumb" className="ms-auto">
                      <ol className="breadcrumb">
                        <li className="breadcrumb-item d-flex align-items-center">
                          <a className="text-muted text-decoration-none d-flex">
                            <iconify-icon icon="solar:home-2-line-duotone" className="fs-6" />
                          </a>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Amenities</span>
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
                  <h4 className="card-title mb-0">All Amenities</h4>
                  <Link href="/admin/amenities/create" className="btn btn-primary hstack gap-2">
                    <iconify-icon icon="solar:add-circle-line-duotone" className="fs-4" />
                    Add New Amenity
                  </Link>
                </div>

                <div className="table-responsive">
                  <DataTable
                    columns={[
                      { label: "#" },
                      { label: "Name" },
                      { label: "Category" },
                      { label: "Icon" },
                      { label: "Sort Order" },
                      { label: "Properties" },
                      { label: "Actions", className: "text-center" },
                    ]}
                    rows={amenities.map((amenity, i) => ({
                      key: amenity.id,
                      values: [i + 1, amenity.name, amenity.category, amenity.icon, amenity.sort_order, Number(amenity.properties_count), null],
                      cells: [
                        i + 1,
                        <span className="fw-semibold" key="n">
                          {amenity.name}
                        </span>,
                        <span className={`badge ${CATEGORY_COLORS[amenity.category ?? ""] ?? "bg-secondary-subtle text-secondary"}`} key="c">
                          {amenity.category}
                        </span>,
                        amenity.icon ? (
                          <Fragment key="i">
                            <iconify-icon icon={amenity.icon} className="fs-5" />
                            <small className="text-muted ms-1">{amenity.icon}</small>
                          </Fragment>
                        ) : (
                          <span className="text-muted" key="i">
                            —
                          </span>
                        ),
                        amenity.sort_order,
                        <span className="badge bg-primary-subtle text-primary" key="p">
                          {Number(amenity.properties_count)} properties
                        </span>,
                        <div className="d-flex align-items-center justify-content-center gap-2" key="a">
                          <Link href={`/admin/amenities/${amenity.id}/edit`} className="btn btn-sm btn-info" title="Edit">
                            <iconify-icon icon="solar:pen-line-duotone" />
                          </Link>
                          <form action={deleteAmenity.bind(null, amenity.id)} className="d-inline">
                            <ConfirmButton className="btn btn-sm btn-danger" title="Delete" message={`Are you sure you want to delete ${amenity.name ?? ""}?`}>
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
