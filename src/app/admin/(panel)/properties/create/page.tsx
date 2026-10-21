import { storeProperty } from "@/actions/admin/properties";
import { propertyFormOptions } from "@/lib/admin/properties";
import { PropertyForm } from "../PropertyForm";

// PropertyController@create → admin/properties/create.blade.php
export default async function CreatePropertyPage() {
  const { communities, propertyTypes, amenities } = await propertyFormOptions();

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
                    <h4 className="mb-4 mb-sm-0 card-title">Add Property</h4>
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
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Add</span>
                        </li>
                      </ol>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <PropertyForm
          mode="create"
          action={storeProperty}
          defaults={{ listing_type: "", status: "active", price_currency: "AED", city: "Dubai", country: "UAE" }}
          communities={communities}
          propertyTypes={propertyTypes}
          amenities={amenities}
        />
      </div>
    </div>
  );
}
