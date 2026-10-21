import { redirect } from "next/navigation";
import { updateAmenity } from "@/actions/admin/amenities";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { toId } from "@/lib/form";
import { getAmenity } from "@/lib/admin/amenities";
import { AmenityForm } from "../../AmenityForm";

// AmenityController@edit → admin/amenities/edit.blade.php
export default async function EditAmenityPage({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const amenity = id ? await getAmenity(id) : null;
  if (!id || !amenity) redirect("/admin/record-missing?m=amenity");

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
                    <h4 className="mb-4 mb-sm-0 card-title">Edit Amenity</h4>
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

        <AmenityForm
          mode="edit"
          action={updateAmenity.bind(null, id)}
          defaults={{
            name: amenity.name ?? "",
            category: amenity.category ?? "",
            icon: amenity.icon ?? "",
            sort_order: amenity.sort_order === null ? "" : String(amenity.sort_order),
          }}
          flash={<AdminFlash />}
        />
      </div>
    </div>
  );
}
