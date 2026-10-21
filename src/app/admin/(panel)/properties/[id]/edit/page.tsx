import { redirect } from "next/navigation";
import { updateProperty } from "@/actions/admin/properties";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { toId } from "@/lib/form";
import { getProperty, propertyAmenityIds, propertyFormOptions, propertyGallery } from "@/lib/admin/properties";
import { asset } from "@/lib/url";
import { PropertyForm } from "../../PropertyForm";

const FORM_FIELDS = [
  "title", "listing_type", "property_type_id", "community_id", "status", "short_description", "description",
  "bedrooms", "bathrooms", "area_sqft", "plot_area_sqft", "parking_spaces", "furnished", "floor_number", "total_floors",
  "price", "price_currency", "service_charge", "price_on_request", "address", "city", "country", "latitude", "longitude",
  "google_maps_link", "video_url", "virtual_tour_url", "meta_title", "meta_keywords", "meta_desc",
  "is_featured", "is_exclusive", "is_new",
];

// PropertyController@edit → admin/properties/edit.blade.php
export default async function EditPropertyPage({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const property = id ? await getProperty(id) : null;
  if (!id || !property) redirect("/admin/record-missing?m=property");

  const [{ communities, propertyTypes, amenities }, gallery, selectedAmenities] = await Promise.all([
    propertyFormOptions(),
    propertyGallery(id),
    propertyAmenityIds(id),
  ]);

  const defaults: Record<string, string | string[]> = { amenities: selectedAmenities };
  for (const field of FORM_FIELDS) {
    const value = property[field];
    defaults[field] = value === null || value === undefined ? "" : String(value);
  }

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
                    <h4 className="mb-4 mb-sm-0 card-title">Edit Property</h4>
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

        <PropertyForm
          mode="edit"
          action={updateProperty.bind(null, id)}
          defaults={defaults}
          communities={communities}
          propertyTypes={propertyTypes}
          amenities={amenities}
          referenceNo={property.reference_no}
          mainImage={property.main_image ? asset(property.main_image) : null}
          floorPlanImage={property.floor_plan_image ? asset(property.floor_plan_image) : null}
          brochurePdf={property.brochure_pdf ? asset(property.brochure_pdf) : null}
          gallery={gallery.map((img) => ({ id: img.id, url: asset(img.image_path) }))}
          flash={<AdminFlash />}
        />
      </div>
    </div>
  );
}
