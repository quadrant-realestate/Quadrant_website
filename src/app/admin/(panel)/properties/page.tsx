import Link from "next/link";
import { deleteProperty, togglePropertyFeatured } from "@/actions/admin/properties";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { activeCommunities, listProperties } from "@/lib/admin/properties";
import { numberFormat, strLimit, ucfirst } from "@/lib/format";
import { asset } from "@/lib/url";

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
  international: "International",
  private: "Private",
};
const STATUS_COLORS: Record<string, string> = {
  active: "bg-success-subtle text-success",
  sold: "bg-danger-subtle text-danger",
  rented: "bg-info-subtle text-info",
  reserved: "bg-warning-subtle text-warning",
  inactive: "bg-secondary-subtle text-secondary",
};

type Search = Record<string, string | string[] | undefined>;
const one = (v: string | string[] | undefined) => (Array.isArray(v) ? v[0] : v) ?? "";

// PropertyController@index → admin/properties/index.blade.php
export default async function PropertiesPage({ searchParams }: { searchParams: Promise<Search> }) {
  const sp = await searchParams;
  const filters = {
    search: one(sp.search).trim(),
    listing_type: one(sp.listing_type),
    status: one(sp.status),
    community_id: one(sp.community_id),
  };
  const [properties, communities] = await Promise.all([listProperties(filters), activeCommunities()]);

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
                    <h4 className="mb-4 mb-sm-0 card-title">Properties</h4>
                    <nav aria-label="breadcrumb" className="ms-auto">
                      <ol className="breadcrumb">
                        <li className="breadcrumb-item d-flex align-items-center">
                          <a className="text-muted text-decoration-none d-flex">
                            <iconify-icon icon="solar:home-2-line-duotone" className="fs-6" />
                          </a>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Properties</span>
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

        {/* Filters */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-3">
              <div className="card-body py-3">
                {/* key: reset the uncontrolled fields when the query string changes (e.g. sidebar links) */}
                <form method="GET" action="/admin/properties" key={JSON.stringify(filters)}>
                  <div className="row g-2 align-items-end">
                    {/* Search */}
                    <div className="col-md-3">
                      <label className="form-label fw-semibold">Search</label>
                      <input type="text" className="form-control" name="search" placeholder="Title or Reference No..." defaultValue={one(sp.search)} />
                    </div>

                    {/* Listing Type */}
                    <div className="col-md-2">
                      <label className="form-label fw-semibold">Listing Type</label>
                      <select className="form-select" name="listing_type" defaultValue={filters.listing_type}>
                        <option value="">All Types</option>
                        <option value="sale">For Sale</option>
                        <option value="rent">For Rent</option>
                        <option value="off_plan">Off Plan</option>
                        <option value="international">International</option>
                        <option value="private">Private</option>
                      </select>
                    </div>

                    {/* Status */}
                    <div className="col-md-2">
                      <label className="form-label fw-semibold">Status</label>
                      <select className="form-select" name="status" defaultValue={filters.status}>
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="sold">Sold</option>
                        <option value="rented">Rented</option>
                        <option value="reserved">Reserved</option>
                        <option value="inactive">Inactive</option>
                      </select>
                    </div>

                    {/* Community */}
                    <div className="col-md-3">
                      <label className="form-label fw-semibold">Community</label>
                      <select className="form-select" name="community_id" defaultValue={filters.community_id}>
                        <option value="">All Communities</option>
                        {communities.map((community) => (
                          <option value={community.id} key={community.id}>
                            {community.name}
                          </option>
                        ))}
                      </select>
                    </div>

                    {/* Buttons */}
                    <div className="col-md-2 d-flex gap-2">
                      <button type="submit" className="btn btn-primary w-100">
                        <iconify-icon icon="solar:filter-line-duotone" className="me-1" />
                        Filter
                      </button>
                      <Link href="/admin/properties" className="btn btn-outline-secondary w-100">
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
                    All Properties
                    <span className="badge bg-primary-subtle text-primary ms-2">{properties.length}</span>
                  </h4>
                  <Link href="/admin/properties/create" className="btn btn-primary hstack gap-2">
                    <iconify-icon icon="solar:add-circle-line-duotone" className="fs-4" />
                    Add New Property
                  </Link>
                </div>

                <div className="table-responsive">
                  <table className="table table-hover align-middle mb-0">
                    <thead className="table-light">
                      <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title / Ref</th>
                        <th>Type</th>
                        <th>Listing</th>
                        <th>Price</th>
                        <th>Beds/Baths</th>
                        <th>Community</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th className="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {properties.length === 0 ? (
                        <tr>
                          <td colSpan={11} className="text-center py-5 text-muted">
                            <iconify-icon icon="solar:buildings-line-duotone" className="fs-1 d-block mb-2" />
                            No properties found. <Link href="/admin/properties/create">Add one now</Link>
                          </td>
                        </tr>
                      ) : (
                        properties.map((property, i) => (
                          <tr key={property.id}>
                            <td>{i + 1}</td>
                            <td>
                              {property.main_image ? (
                                <img
                                  src={asset(property.main_image)}
                                  alt={property.title ?? ""}
                                  className="rounded"
                                  width={60}
                                  height={50}
                                  style={{ objectFit: "cover" }}
                                />
                              ) : (
                                <div className="rounded bg-light d-flex align-items-center justify-content-center" style={{ width: 60, height: 50 }}>
                                  <iconify-icon icon="solar:buildings-line-duotone" className="text-muted fs-5" />
                                </div>
                              )}
                            </td>
                            <td>
                              <span className="fw-semibold d-block">{strLimit(property.title, 35)}</span>
                              <small className="text-muted">{property.reference_no}</small>
                            </td>
                            <td>
                              <small className="badge bg-secondary-subtle text-secondary">{property.type_name ?? "—"}</small>
                            </td>
                            <td>
                              <span className={`badge ${LISTING_COLORS[property.listing_type ?? ""] ?? "bg-secondary-subtle text-secondary"}`}>
                                {LISTING_LABELS[property.listing_type ?? ""] ?? property.listing_type}
                              </span>
                            </td>
                            <td>
                              {property.price_on_request ? (
                                <span className="text-muted">On Request</span>
                              ) : (
                                <span className="fw-semibold">
                                  {property.price_currency} {numberFormat(property.price)}
                                </span>
                              )}
                            </td>
                            <td>
                              {property.bedrooms !== null ? (
                                <span>
                                  {property.bedrooms} BR / {property.bathrooms} BA
                                </span>
                              ) : (
                                <span className="text-muted">—</span>
                              )}
                            </td>
                            <td>
                              <small>{property.community_name ?? "—"}</small>
                            </td>
                            <td>
                              <form action={togglePropertyFeatured.bind(null, property.id)} className="d-inline">
                                <button type="submit" className="border-0 bg-transparent p-0">
                                  {property.is_featured ? (
                                    <iconify-icon icon="solar:star-bold" className="fs-5 text-warning" />
                                  ) : (
                                    <iconify-icon icon="solar:star-line-duotone" className="fs-5 text-muted" />
                                  )}
                                </button>
                              </form>
                            </td>
                            <td>
                              <span className={`badge ${STATUS_COLORS[property.status ?? ""] ?? "bg-secondary-subtle text-secondary"}`}>
                                {ucfirst(property.status)}
                              </span>
                            </td>
                            <td className="text-center">
                              <div className="d-flex align-items-center justify-content-center gap-2">
                                <Link href={`/admin/properties/${property.id}/edit`} className="btn btn-sm btn-info" title="Edit">
                                  <iconify-icon icon="solar:pen-line-duotone" />
                                </Link>
                                <form action={deleteProperty.bind(null, property.id)} className="d-inline">
                                  <ConfirmButton
                                    className="btn btn-sm btn-danger"
                                    title="Delete"
                                    message={`Delete ${property.title ?? ""}? This cannot be undone.`}
                                  >
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
