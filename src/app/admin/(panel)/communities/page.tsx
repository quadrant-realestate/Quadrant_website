import { Fragment } from "react";
import Link from "next/link";
import { deleteCommunity, toggleCommunityFeatured, toggleCommunityStatus } from "@/actions/admin/communities";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { DataTable, type DataTableRow } from "@/components/admin/DataTable";
import { listCommunities } from "@/lib/admin/communities";
import { asset } from "@/lib/url";
import { linkButton, PageHeader } from "../_projects/PageHeader";

export default async function CommunitiesIndex() {
  const communities = await listCommunities();

  const rows: DataTableRow[] = communities.map((community, i) => ({
    key: community.id,
    values: [
      i + 1,
      "",
      `${community.name ?? ""} ${community.slug ?? ""}`,
      community.city,
      `${community.property_count} properties`,
      "",
      community.is_active ? "Active" : "Inactive",
      "",
    ],
    cells: [
      i + 1,
      community.image ? (
        <img src={asset(community.image)} alt={community.name ?? ""} className="rounded" width={50} height={50} style={{ objectFit: "cover" }} />
      ) : (
        <div className="rounded bg-light d-flex align-items-center justify-content-center" style={{ width: 50, height: 50 }}>
          <iconify-icon icon="solar:map-point-line-duotone" class="text-muted fs-5" />
        </div>
      ),
      <Fragment key="name">
        <span className="fw-semibold">{community.name}</span>
        <br />
        <small className="text-muted">
          <code>{community.slug}</code>
        </small>
      </Fragment>,
      community.city,
      <span key="count" className="badge bg-primary-subtle text-primary">{Number(community.property_count)} properties</span>,
      <form key="featured" action={toggleCommunityFeatured.bind(null, community.id)}>
        <button type="submit" style={linkButton} title={community.is_featured ? "Remove from Featured" : "Mark as Featured"}>
          {community.is_featured ? (
            <iconify-icon icon="solar:star-bold" class="fs-5 text-warning" />
          ) : (
            <iconify-icon icon="solar:star-line-duotone" class="fs-5 text-muted" />
          )}
        </button>
      </form>,
      community.is_active ? (
        <span className="badge bg-success-subtle text-success">Active</span>
      ) : (
        <span className="badge bg-danger-subtle text-danger">Inactive</span>
      ),
      <div key="actions" className="d-flex align-items-center justify-content-center gap-2">
        <form action={toggleCommunityStatus.bind(null, community.id)}>
          <button
            type="submit"
            className={`btn btn-sm ${community.is_active ? "btn-warning" : "btn-success"}`}
            title={community.is_active ? "Deactivate" : "Activate"}
          >
            <iconify-icon icon={community.is_active ? "solar:eye-closed-line-duotone" : "solar:eye-line-duotone"} />
          </button>
        </form>
        <Link href={`/admin/communities/${community.id}/edit`} className="btn btn-sm btn-info" title="Edit">
          <iconify-icon icon="solar:pen-line-duotone" />
        </Link>
        <form action={deleteCommunity.bind(null, community.id)}>
          <ConfirmButton
            className="btn btn-sm btn-danger"
            title="Delete"
            message={`Are you sure you want to delete ${community.name ?? ""}?`}
          >
            <iconify-icon icon="solar:trash-bin-trash-line-duotone" />
          </ConfirmButton>
        </form>
      </div>,
    ],
  }));

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Communities" crumbs={["Communities", "All"]} />

        <AdminFlash />

        {/* Table */}
        <div className="row">
          <div className="col-12">
            <div className="card">
              <div className="card-body">
                <div className="d-flex align-items-center justify-content-between mb-3">
                  <h4 className="card-title mb-0">All Communities</h4>
                  <Link href="/admin/communities/create" className="btn btn-primary hstack gap-2">
                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-4" />
                    Add New Community
                  </Link>
                </div>

                <div className="table-responsive">
                  <DataTable
                    columns={[
                      { label: "#" },
                      { label: "Image" },
                      { label: "Name" },
                      { label: "City" },
                      { label: "Properties" },
                      { label: "Featured" },
                      { label: "Status" },
                      { label: "Actions", className: "text-center" },
                    ]}
                    rows={rows}
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
