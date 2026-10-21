"use client";

import Link from "next/link";
import { useActionState } from "react";
import { deletePropertyGalleryImage } from "@/actions/admin/properties";
import type { AdminFormState } from "@/lib/admin/form-state";

type Option = { id: number; name: string | null };
type Amenity = { id: number; name: string | null; icon: string | null; category: string | null };

type Props = {
  mode: "create" | "edit";
  action: (prev: AdminFormState, form: FormData) => Promise<AdminFormState>;
  /** old() fallbacks: the property's current values on edit, the Blade defaults on create. */
  defaults: Record<string, string | string[]>;
  communities: Option[];
  propertyTypes: Option[];
  amenities: Amenity[];
  /** Edit only */
  referenceNo?: string | null;
  mainImage?: string | null;
  floorPlanImage?: string | null;
  brochurePdf?: string | null;
  gallery?: { id: number; url: string }[];
  /** Flash alert (server rendered, edit page only). */
  flash?: React.ReactNode;
};

/** admin/properties/create.blade.php and edit.blade.php (same form). */
export function PropertyForm(props: Props) {
  const { mode, defaults, communities, propertyTypes, amenities, gallery = [] } = props;
  const [state, action] = useActionState<AdminFormState, FormData>(props.action, {});
  const isEdit = mode === "edit";

  // old('x', default): a submitted value wins (even when empty); missing keys fall back to the default.
  const values = { ...defaults, ...(state.values ?? {}) };
  const v = (key: string) => {
    const value = values[key];
    return typeof value === "string" ? value : "";
  };
  const checked = (key: string) => {
    const value = values[key];
    return typeof value === "string" && value !== "" && value !== "0";
  };
  const selectedAmenities = Array.isArray(values.amenities) ? values.amenities : [];
  const hasErrors = Object.keys(state.errors ?? {}).length > 0;

  // $amenities->groupBy('category')
  const grouped = new Map<string, Amenity[]>();
  for (const a of amenities) {
    const key = a.category ?? "";
    grouped.set(key, [...(grouped.get(key) ?? []), a]);
  }

  return (
    <>
      {/* Alerts */}
      {hasErrors && <div className="alert alert-danger text-center">Please fill in the required fields.</div>}
      {props.flash}

      <form action={action} key={state.n ?? 0}>
        {/* ===== SECTION 1: Basic Info ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:document-text-line-duotone" className="me-2" />
                  Basic Information
                  {isEdit && <small className="text-muted ms-3 fw-normal fs-6">Ref: {props.referenceNo}</small>}
                </h5>
                <div className="row">
                  {/* Title */}
                  <div className="col-md-8">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="title" placeholder="Property Title" defaultValue={v("title")} required />
                      <label>Property Title *</label>
                      {!isEdit && state.errors?.title && <span className="text-danger text-sm">This field is required.</span>}
                    </div>
                  </div>

                  {/* Listing Type */}
                  <div className="col-md-4">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="listing_type" required defaultValue={v("listing_type")}>
                        {!isEdit && (
                          <option value="" disabled>
                            Select
                          </option>
                        )}
                        <option value="sale">For Sale</option>
                        <option value="rent">For Rent</option>
                        <option value="off_plan">Off Plan</option>
                        <option value="international">International</option>
                        <option value="private">Private Listing</option>
                      </select>
                      <label>Listing Type *</label>
                    </div>
                  </div>

                  {/* Property Type */}
                  <div className="col-md-4">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="property_type_id" defaultValue={v("property_type_id")}>
                        <option value="">Select Type</option>
                        {propertyTypes.map((type) => (
                          <option value={type.id} key={type.id}>
                            {type.name}
                          </option>
                        ))}
                      </select>
                      <label>Property Type</label>
                    </div>
                  </div>

                  {/* Community */}
                  <div className="col-md-4">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="community_id" defaultValue={v("community_id")}>
                        <option value="">Select Community</option>
                        {communities.map((community) => (
                          <option value={community.id} key={community.id}>
                            {community.name}
                          </option>
                        ))}
                      </select>
                      <label>Community</label>
                    </div>
                  </div>

                  {/* Status */}
                  <div className="col-md-4">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="status" defaultValue={v("status")}>
                        <option value="active">Active</option>
                        <option value="sold">Sold</option>
                        <option value="rented">Rented</option>
                        <option value="reserved">Reserved</option>
                        <option value="inactive">Inactive</option>
                      </select>
                      <label>Status</label>
                    </div>
                  </div>

                  {/* Short Description */}
                  <div className="col-md-12">
                    <div className="form-floating mb-3">
                      <textarea
                        className="form-control"
                        name="short_description"
                        placeholder="Short Description"
                        style={{ height: 80 }}
                        defaultValue={v("short_description")}
                      />
                      <label>Short Description</label>
                    </div>
                  </div>

                  {/* Description */}
                  <div className="col-md-12">
                    <label className="form-label fw-semibold">Full Description</label>
                    <textarea
                      className="form-control"
                      name="description"
                      rows={6}
                      placeholder="Full property description..."
                      defaultValue={v("description")}
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 2: Property Details ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:ruler-cross-pen-line-duotone" className="me-2" />
                  Property Details
                </h5>
                <div className="row">
                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <input type="number" className="form-control" name="bedrooms" placeholder="Bedrooms" defaultValue={v("bedrooms")} min={0} />
                      <label>Bedrooms</label>
                    </div>
                  </div>

                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <input type="number" className="form-control" name="bathrooms" placeholder="Bathrooms" defaultValue={v("bathrooms")} min={0} />
                      <label>Bathrooms</label>
                    </div>
                  </div>

                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <input type="number" className="form-control" name="area_sqft" placeholder="Area SqFt" defaultValue={v("area_sqft")} step="0.01" />
                      <label>Area (SqFt)</label>
                    </div>
                  </div>

                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <input
                        type="number"
                        className="form-control"
                        name="plot_area_sqft"
                        placeholder="Plot Area"
                        defaultValue={v("plot_area_sqft")}
                        step="0.01"
                      />
                      <label>Plot Area (SqFt)</label>
                    </div>
                  </div>

                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <input type="number" className="form-control" name="parking_spaces" placeholder="Parking" defaultValue={v("parking_spaces")} min={0} />
                      <label>Parking</label>
                    </div>
                  </div>

                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="furnished" defaultValue={v("furnished")}>
                        <option value="">Select</option>
                        <option value="furnished">Furnished</option>
                        <option value="semi_furnished">Semi Furnished</option>
                        <option value="unfurnished">Unfurnished</option>
                      </select>
                      <label>Furnished</label>
                    </div>
                  </div>

                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input type="number" className="form-control" name="floor_number" placeholder="Floor Number" defaultValue={v("floor_number")} />
                      <label>Floor Number</label>
                    </div>
                  </div>

                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input type="number" className="form-control" name="total_floors" placeholder="Total Floors" defaultValue={v("total_floors")} />
                      <label>Total Floors</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 3: Pricing ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:wallet-money-line-duotone" className="me-2" />
                  Pricing
                </h5>
                <div className="row">
                  <div className="col-md-4">
                    <div className="form-floating mb-3">
                      <input type="number" className="form-control" name="price" placeholder="Price" defaultValue={v("price")} step="0.01" />
                      <label>Price</label>
                    </div>
                  </div>

                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="price_currency" defaultValue={v("price_currency")}>
                        <option value="AED">AED</option>
                        <option value="USD">USD</option>
                        <option value="GBP">GBP</option>
                        <option value="EUR">EUR</option>
                      </select>
                      <label>Currency</label>
                    </div>
                  </div>

                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input
                        type="number"
                        className="form-control"
                        name="service_charge"
                        placeholder="Service Charge"
                        defaultValue={v("service_charge")}
                        step="0.01"
                      />
                      <label>Service Charge (AED/yr)</label>
                    </div>
                  </div>

                  <div className="col-md-3 d-flex align-items-center">
                    <div className="form-check form-switch ms-2">
                      <input
                        className="form-check-input"
                        type="checkbox"
                        name="price_on_request"
                        id="price_on_request"
                        defaultChecked={checked("price_on_request")}
                      />
                      <label className="form-check-label fw-semibold" htmlFor="price_on_request">
                        Price on Request
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 4: Location ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:map-point-wave-line-duotone" className="me-2" />
                  Location
                </h5>
                <div className="row">
                  <div className="col-md-8">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="address" placeholder="Address" defaultValue={v("address")} />
                      <label>Address</label>
                    </div>
                  </div>

                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="city" placeholder="City" defaultValue={v("city")} />
                      <label>City</label>
                    </div>
                  </div>

                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="country" placeholder="Country" defaultValue={v("country")} />
                      <label>Country</label>
                    </div>
                  </div>

                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="latitude" placeholder="Latitude" defaultValue={v("latitude")} />
                      <label>Latitude</label>
                    </div>
                  </div>

                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="longitude" placeholder="Longitude" defaultValue={v("longitude")} />
                      <label>Longitude</label>
                    </div>
                  </div>

                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="google_maps_link"
                        placeholder="Google Maps Link"
                        defaultValue={v("google_maps_link")}
                      />
                      <label>Google Maps Link</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 5: Media ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:gallery-wide-line-duotone" className="me-2" />
                  Media
                </h5>
                <div className="row">
                  {/* Main Image */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">{isEdit ? "Main Image" : "Main Image *"}</label>
                    {isEdit && props.mainImage && (
                      <div className="mb-2">
                        <img src={props.mainImage} alt="Main Image" className="rounded" height={100} style={{ objectFit: "cover" }} />
                        <small className="text-muted d-block mt-1">Current main image</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="main_image" accept="image/*" />
                    <div className="form-text">{isEdit ? "Leave empty to keep current image." : "This is the cover image shown in listings."}</div>
                  </div>

                  {/* Gallery */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">{isEdit ? "Add More Gallery Images" : "Gallery Images"}</label>
                    <input className="form-control" type="file" name="gallery[]" accept="image/*" multiple />
                    <div className="form-text">{isEdit ? "These will be added to the existing gallery." : "Select multiple images for the gallery."}</div>
                  </div>

                  {/* Existing Gallery */}
                  {isEdit && gallery.length > 0 && (
                    <div className="col-12 mb-3">
                      <label className="form-label fw-semibold">Existing Gallery</label>
                      <div className="d-flex flex-wrap gap-3">
                        {gallery.map((img) => (
                          <div className="position-relative" key={img.id}>
                            <img src={img.url} alt="Gallery" className="rounded" width={100} height={80} style={{ objectFit: "cover" }} />
                            {/* Submits the separate delete form below (forms can't be nested). */}
                            <button
                              type="submit"
                              form={`gallery-delete-${img.id}`}
                              className="position-absolute top-0 end-0 btn btn-danger btn-sm p-0 px-1"
                              style={{ fontSize: 10 }}
                              onClick={(e) => {
                                if (!window.confirm("Delete this image?")) e.preventDefault();
                              }}
                            >
                              &times;
                            </button>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}

                  {/* Floor Plan */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">Floor Plan Image</label>
                    {isEdit && props.floorPlanImage && (
                      <div className="mb-2">
                        <img src={props.floorPlanImage} alt="Floor Plan" className="rounded" height={80} style={{ objectFit: "cover" }} />
                      </div>
                    )}
                    <input className="form-control" type="file" name="floor_plan_image" accept="image/*" />
                  </div>

                  {/* Brochure PDF */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">Brochure PDF</label>
                    {isEdit && props.brochurePdf && (
                      <div className="mb-2">
                        <a href={props.brochurePdf} target="_blank" className="btn btn-sm btn-outline-primary">
                          <iconify-icon icon="solar:file-download-line-duotone" />
                          Current Brochure
                        </a>
                      </div>
                    )}
                    <input className="form-control" type="file" name="brochure_pdf" accept=".pdf" />
                  </div>

                  {/* Video URL */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="video_url" placeholder="Video URL" defaultValue={v("video_url")} />
                      <label>Video URL (YouTube/Vimeo)</label>
                    </div>
                  </div>

                  {/* Virtual Tour URL */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="virtual_tour_url"
                        placeholder="Virtual Tour URL"
                        defaultValue={v("virtual_tour_url")}
                      />
                      <label>Virtual Tour URL</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 6: Amenities ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:star-shine-line-duotone" className="me-2" />
                  Amenities
                </h5>

                {[...grouped.entries()].map(([category, items]) => (
                  <div key={category}>
                    <h6 className="text-muted fw-semibold mb-3 mt-3">{category}</h6>
                    <div className="row mb-3">
                      {items.map((amenity) => (
                        <div className="col-md-3 col-6 mb-2" key={amenity.id}>
                          <div className="form-check">
                            <input
                              className="form-check-input"
                              type="checkbox"
                              name="amenities[]"
                              value={amenity.id}
                              id={`amenity_${amenity.id}`}
                              defaultChecked={selectedAmenities.includes(String(amenity.id))}
                            />
                            <label className="form-check-label" htmlFor={`amenity_${amenity.id}`}>
                              {amenity.icon && <iconify-icon icon={amenity.icon} className="me-1" />}
                              {amenity.name}
                            </label>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 7: SEO & Flags ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:settings-line-duotone" className="me-2" />
                  SEO &amp; Options
                </h5>
                <div className="row">
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="meta_title" placeholder="Meta Title" defaultValue={v("meta_title")} />
                      <label>Meta Title</label>
                    </div>
                  </div>

                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="meta_keywords" placeholder="Meta Keywords" defaultValue={v("meta_keywords")} />
                      <label>Meta Keywords</label>
                    </div>
                  </div>

                  <div className="col-md-12">
                    <div className="form-floating mb-3">
                      <textarea
                        className="form-control"
                        name="meta_desc"
                        placeholder="Meta Description"
                        style={{ height: 80 }}
                        defaultValue={v("meta_desc")}
                      />
                      <label>Meta Description</label>
                    </div>
                  </div>

                  {/* Flags */}
                  <div className="col-12 d-flex flex-wrap gap-4 mt-2">
                    <div className="form-check form-switch">
                      <input className="form-check-input" type="checkbox" name="is_featured" id="is_featured" defaultChecked={checked("is_featured")} />
                      <label className="form-check-label fw-semibold" htmlFor="is_featured">
                        Featured
                      </label>
                    </div>
                    <div className="form-check form-switch">
                      <input className="form-check-input" type="checkbox" name="is_exclusive" id="is_exclusive" defaultChecked={checked("is_exclusive")} />
                      <label className="form-check-label fw-semibold" htmlFor="is_exclusive">
                        Exclusive
                      </label>
                    </div>
                    <div className="form-check form-switch">
                      <input className="form-check-input" type="checkbox" name="is_new" id="is_new" defaultChecked={checked("is_new")} />
                      <label className="form-check-label fw-semibold" htmlFor="is_new">
                        New
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Submit */}
        <div className="row mb-4">
          <div className="col-12">
            <div className="d-flex justify-content-end gap-2">
              <Link href="/admin/properties" className="btn btn-outline-secondary px-4">
                Cancel
              </Link>
              <button type="submit" className="btn btn-primary px-4 hstack gap-2">
                <iconify-icon icon="solar:diskette-line-duotone" className="fs-4" />
                {isEdit ? "Update Property" : "Save Property"}
              </button>
            </div>
          </div>
        </div>
      </form>

      {/* Gallery delete forms (GET /properties/gallery/{id}/delete in Laravel) */}
      {isEdit &&
        gallery.map((img) => (
          <form key={img.id} id={`gallery-delete-${img.id}`} action={deletePropertyGalleryImage.bind(null, img.id)} hidden />
        ))}
    </>
  );
}
