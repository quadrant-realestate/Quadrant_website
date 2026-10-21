import { redirect } from "next/navigation";
import { updatePropertyType } from "@/actions/admin/property-types";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { toId } from "@/lib/form";
import { getPropertyType } from "@/lib/admin/property-types";
import { PropertyTypeForm } from "../../PropertyTypeForm";

// PropertyTypeController@edit → admin/property_types/edit.blade.php
export default async function EditPropertyTypePage({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const type = id ? await getPropertyType(id) : null;
  if (!id || !type) redirect("/admin/record-missing?m=property-type");

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
                    <h4 className="mb-4 mb-sm-0 card-title">Edit Property Type</h4>
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
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Edit</span>
                        </li>
                      </ol>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <PropertyTypeForm
          mode="edit"
          action={updatePropertyType.bind(null, id)}
          defaults={{
            name: type.name ?? "",
            slug: type.slug ?? "",
            sort_order: type.sort_order === null ? "" : String(type.sort_order),
            is_active: !!type.is_active,
          }}
          flash={<AdminFlash />}
        />
      </div>
    </div>
  );
}
