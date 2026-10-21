"use client";

import Link from "next/link";
import { useActionState, useTransition } from "react";
import { createDevelopment, deleteDevelopmentGalleryImage, updateDevelopment } from "@/actions/admin/developments";
import type { AdminFormState } from "@/lib/admin/form-state";
import { dec, old, oldChecked } from "@/lib/admin/projects-format";
import { asset } from "@/lib/url";

export type DevelopmentFormData = {
  id: number;
  title: string | null;
  status: string | null;
  developer_name: string | null;
  community_id: number | null;
  property_types: string | null;
  total_units: number | null;
  floors: number | null;
  short_description: string | null;
  description: string | null;
  price_from: number | null;
  price_currency: string | null;
  down_payment_pct: number | null;
  completion_pct: number | null;
  handover_date: string | null;
  payment_plan: string | null;
  main_image: string | null;
  banner_image: string | null;
  developer_logo: string | null;
  brochure_pdf: string | null;
  video_url: string | null;
  meta_title: string | null;
  meta_desc: string | null;
  og_image: string | null;
  is_featured: number;
  is_active: number;
  rera_permit: string | null;
  bedroom_range: string | null;
  size_from: number | null;
  size_to: number | null;
  quadrant_view: string | null;
  nearby_landmarks: string | null;
};

type Props = {
  communities: { id: number; name: string | null }[];
  development?: DevelopmentFormData;
  gallery?: { id: number; image_path: string | null }[];
  /** <AdminFlash /> (edit page shows session('success') under the errors alert). */
  flash?: React.ReactNode;
};

/** Form of admin/developments/create.blade.php (no `development`) and edit.blade.php. */
export function DevelopmentForm({ communities, development: d, gallery = [], flash }: Props) {
  const isEdit = !!d;
  const action = isEdit ? updateDevelopment.bind(null, d.id) : createDevelopment;
  const [state, formAction] = useActionState<AdminFormState, FormData>(action, {});
  const [, startTransition] = useTransition();
  const o = (key: string, fallback: unknown = "") => old(state, key, fallback);
  const hasErrors = !!state.errors && Object.keys(state.errors).length > 0;

  return (
    <>
      {/* Alerts */}
      {hasErrors && <div className="alert alert-danger text-center">Please fill in the required fields.</div>}
      {flash}

      {isEdit && (
        <div className="card mt-4">
          <div className="card-header">
            <h5 className="mb-0">Project Detail Page Content</h5>
          </div>
          <div className="card-body">
            <p className="text-muted mb-3">These sections power the Project Detail Page (Floor Plans tab and Highlights &amp; Amenities grid).</p>
            <Link href={`/admin/developments/${d.id}/floor-plans`} className="btn btn-outline-primary">
              <iconify-icon icon="solar:layers-line-duotone" />
              Manage Floor Plans
            </Link>{" "}
            <Link href={`/admin/developments/${d.id}/amenities`} className="btn btn-outline-primary">
              <iconify-icon icon="solar:star-shine-line-duotone" />
              Manage Amenities
            </Link>
          </div>
        </div>
      )}

      <form action={formAction} key={state.n ?? 0}>
        {/* ===== SECTION 1: Basic Info ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:document-text-line-duotone" class="me-2" />
                  Basic Information
                </h5>
                <div className="row">
                  {/* Title */}
                  <div className="col-md-8">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="title"
                        id={isEdit ? undefined : "dev_title"}
                        placeholder="Development Title"
                        defaultValue={o("title", d?.title)}
                        required
                      />
                      <label>Development Title *</label>
                      {!isEdit && state.errors?.title && <span className="text-danger text-sm">This field is required.</span>}
                    </div>
                  </div>

                  {/* Status */}
                  <div className="col-md-4">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="status" defaultValue={o("status", isEdit ? d.status : "launched")}>
                        <option value="upcoming">Upcoming</option>
                        <option value="launched">Launched</option>
                        <option value="under_construction">Under Construction</option>
                        <option value="completed">Completed</option>
                      </select>
                      <label>Status</label>
                    </div>
                  </div>

                  {/* Developer Name */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="developer_name"
                        placeholder="Developer Name"
                        defaultValue={o("developer_name", d?.developer_name)}
                      />
                      <label>Developer Name</label>
                    </div>
                  </div>

                  {/* Community */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="community_id" defaultValue={o("community_id", d?.community_id)}>
                        <option value="">Select Community</option>
                        {communities.map((c) => (
                          <option key={c.id} value={c.id}>
                            {c.name}
                          </option>
                        ))}
                      </select>
                      <label>Community</label>
                    </div>
                  </div>

                  {/* Property Types */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="property_types"
                        placeholder="Property Types"
                        defaultValue={o("property_types", d?.property_types)}
                      />
                      <label>Property Types</label>
                    </div>
                    {!isEdit && <div className="form-text mb-3">e.g. Apartments, Townhouses, Villas</div>}
                  </div>

                  {/* Total Units */}
                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input
                        type="number"
                        className="form-control"
                        name="total_units"
                        placeholder="Total Units"
                        defaultValue={o("total_units", d?.total_units)}
                        min={0}
                      />
                      <label>Total Units</label>
                    </div>
                  </div>

                  {/* Floors */}
                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input type="number" className="form-control" name="floors" placeholder="Floors" defaultValue={o("floors", d?.floors)} min={0} />
                      <label>Total Floors</label>
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
                        defaultValue={o("short_description", d?.short_description)}
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
                      placeholder={isEdit ? undefined : "Full development description..."}
                      defaultValue={o("description", d?.description)}
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 2: Pricing & Timeline ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:wallet-money-line-duotone" class="me-2" />
                  Pricing &amp; Timeline
                </h5>
                <div className="row">
                  {/* Price From */}
                  <div className="col-md-4">
                    <div className="form-floating mb-3">
                      <input
                        type="number"
                        className="form-control"
                        name="price_from"
                        placeholder="Starting Price"
                        defaultValue={o("price_from", dec(d?.price_from))}
                        step="0.01"
                      />
                      <label>Starting Price</label>
                    </div>
                  </div>

                  {/* Currency */}
                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="price_currency" defaultValue={isEdit ? o("price_currency", d.price_currency) : "AED"}>
                        <option value="AED">AED</option>
                        <option value="USD">USD</option>
                        <option value="GBP">GBP</option>
                        <option value="EUR">EUR</option>
                      </select>
                      <label>Currency</label>
                    </div>
                  </div>

                  {/* Down Payment % */}
                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input
                        type="number"
                        className="form-control"
                        name="down_payment_pct"
                        placeholder="Down Payment %"
                        defaultValue={o("down_payment_pct", dec(d?.down_payment_pct))}
                        step="0.01"
                        min={0}
                        max={100}
                      />
                      <label>Down Payment (%)</label>
                    </div>
                  </div>

                  {/* Completion % */}
                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input
                        type="number"
                        className="form-control"
                        name="completion_pct"
                        placeholder="Completion %"
                        defaultValue={o("completion_pct", dec(d?.completion_pct))}
                        min={0}
                        max={100}
                      />
                      <label>Completion (%)</label>
                    </div>
                  </div>

                  {/* Handover Date */}
                  <div className="col-md-4">
                    <div className="form-floating mb-3">
                      <input type="date" className="form-control" name="handover_date" defaultValue={o("handover_date", d?.handover_date)} />
                      <label>Handover Date</label>
                    </div>
                  </div>

                  {/* Payment Plan */}
                  <div className="col-md-12">
                    <div className="form-floating mb-3">
                      <textarea
                        className="form-control"
                        name="payment_plan"
                        placeholder="Payment Plan"
                        style={{ height: 100 }}
                        defaultValue={o("payment_plan", d?.payment_plan)}
                      />
                      <label>Payment Plan Details</label>
                    </div>
                    {!isEdit && <div className="form-text mb-3">e.g. 20% Down, 40% During Construction, 40% on Handover</div>}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 3: Media ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:gallery-wide-line-duotone" class="me-2" />
                  Media
                </h5>
                <div className="row">
                  {/* Main Image */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">{isEdit ? "Main Image" : "Main Image *"}</label>
                    {d?.main_image && (
                      <div className="mb-2">
                        <img src={asset(d.main_image)} alt="Main" className="rounded" height={80} style={{ objectFit: "cover" }} />
                        <small className="text-muted d-block mt-1">Current image</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="main_image" accept="image/*" />
                    {!isEdit && <div className="form-text">Cover image for this development.</div>}
                  </div>

                  {/* Banner Image */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">Banner Image</label>
                    {d?.banner_image && (
                      <div className="mb-2">
                        <img src={asset(d.banner_image)} alt="Banner" className="rounded" height={80} style={{ objectFit: "cover" }} />
                        <small className="text-muted d-block mt-1">Current banner</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="banner_image" accept="image/*" />
                    {!isEdit && <div className="form-text">Recommended: 1920x600px</div>}
                  </div>

                  {/* Developer Logo */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">Developer Logo</label>
                    {d?.developer_logo && (
                      <div className="mb-2">
                        <img src={asset(d.developer_logo)} alt="Logo" height={40} />
                        <small className="text-muted d-block mt-1">Current logo</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="developer_logo" accept="image/*" />
                  </div>

                  {/* Brochure PDF */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">Brochure PDF</label>
                    {d?.brochure_pdf && (
                      <div className="mb-2">
                        <a href={asset(d.brochure_pdf)} target="_blank" className="btn btn-sm btn-outline-primary">
                          <iconify-icon icon="solar:file-download-line-duotone" />
                          Current Brochure
                        </a>
                      </div>
                    )}
                    <input className="form-control" type="file" name="brochure_pdf" accept=".pdf" />
                  </div>

                  {/* Gallery */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">{isEdit ? "Add More Gallery Images" : "Gallery Images"}</label>
                    <input className="form-control" type="file" name="gallery[]" accept="image/*" multiple />
                    {!isEdit && <div className="form-text">Select multiple images.</div>}
                  </div>

                  {/* Video URL */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="video_url" placeholder="Video URL" defaultValue={o("video_url", d?.video_url)} />
                      <label>{isEdit ? "Video URL" : "Video URL (YouTube/Vimeo)"}</label>
                    </div>
                  </div>

                  {/* Existing Gallery */}
                  {isEdit && gallery.length > 0 && (
                    <div className="col-12 mt-2">
                      <label className="form-label fw-semibold">Existing Gallery</label>
                      <div className="d-flex flex-wrap gap-3">
                        {gallery.map((img) => (
                          <div className="position-relative" key={img.id}>
                            <img src={asset(img.image_path)} alt="Gallery" className="rounded" width={100} height={80} style={{ objectFit: "cover" }} />
                            <a
                              href="#"
                              className="position-absolute top-0 end-0 btn btn-danger btn-sm p-0 px-1"
                              style={{ fontSize: 10 }}
                              onClick={(e) => {
                                e.preventDefault();
                                if (!window.confirm("Delete this image?")) return;
                                startTransition(() => deleteDevelopmentGalleryImage(img.id));
                              }}
                            >
                              &times;
                            </a>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 4: SEO & Options ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:settings-line-duotone" class="me-2" />
                  SEO &amp; Options
                </h5>
                <div className="row">
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="meta_title" placeholder="Meta Title" defaultValue={o("meta_title", d?.meta_title)} />
                      <label>Meta Title</label>
                    </div>
                  </div>

                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="meta_desc"
                        placeholder="Meta Description"
                        defaultValue={o("meta_desc", d?.meta_desc)}
                      />
                      <label>Meta Description</label>
                    </div>
                  </div>

                  {isEdit && (
                    <div className="col-md-6">
                      <div className="form-group">
                        <label>
                          OG Image <small className="text-muted">(Social share image — ideally 1200×630px)</small>
                        </label>
                        <input type="file" name="og_image" className="form-control" />
                        {d.og_image && <img src={asset(d.og_image)} height={60} className="mt-2 d-block" alt="" />}
                      </div>
                    </div>
                  )}

                  <div className="col-12 d-flex flex-wrap gap-4 mt-2">
                    <div className="form-check form-switch">
                      <input
                        className="form-check-input"
                        type="checkbox"
                        name="is_featured"
                        id="is_featured"
                        defaultChecked={oldChecked(state, "is_featured", d?.is_featured)}
                      />
                      <label className="form-check-label fw-semibold" htmlFor="is_featured">
                        Featured
                      </label>
                    </div>
                    <div className="form-check form-switch">
                      <input
                        className="form-check-input"
                        type="checkbox"
                        name="is_active"
                        id="is_active"
                        defaultChecked={isEdit ? oldChecked(state, "is_active", d.is_active) : true}
                      />
                      <label className="form-check-label fw-semibold" htmlFor="is_active">
                        Active
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* RERA Compliance */}
        <div className="card mt-4">
          <div className="card-header">
            <h5>RERA Compliance</h5>
          </div>
          <div className="card-body">
            <div className="row">
              <div className="col-md-6">
                <div className="form-group">
                  <label>RERA Permit Number</label>
                  <input type="text" name="rera_permit" className="form-control" defaultValue={o("rera_permit", d?.rera_permit)} placeholder="e.g. 7123456789" />
                </div>
              </div>
              <div className="col-md-6">
                <div className="form-group">
                  <label>RERA QR Code Image</label>
                  <input type="file" name="rera_qr_image" className="form-control" />
                </div>
              </div>
              <div className="col-md-4">
                <div className="form-group">
                  <label>Bedroom Range</label>
                  <input type="text" name="bedroom_range" className="form-control" defaultValue={o("bedroom_range", d?.bedroom_range)} placeholder="e.g. 1-3 BR" />
                </div>
              </div>
              <div className="col-md-4">
                <div className="form-group">
                  <label>Size From (sq ft)</label>
                  <input type="text" name="size_from" className="form-control" defaultValue={o("size_from", dec(d?.size_from))} placeholder="e.g. 750" />
                </div>
              </div>
              <div className="col-md-4">
                <div className="form-group">
                  <label>Size To (sq ft)</label>
                  <input type="text" name="size_to" className="form-control" defaultValue={o("size_to", dec(d?.size_to))} placeholder="e.g. 3200" />
                </div>
              </div>
              <div className="col-12">
                <div className="form-group">
                  <label>
                    Quadrant&apos;s View <small className="text-muted">(advisory note — your differentiator)</small>
                  </label>
                  <textarea
                    name="quadrant_view"
                    className="form-control"
                    rows={4}
                    placeholder="Short advisory note from Quadrant about this project..."
                    defaultValue={o("quadrant_view", d?.quadrant_view)}
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* LOCATION */}
        <div className="card mt-4">
          <div className="card-header">
            <h5 className="mb-0">Location</h5>
          </div>
          <div className="card-body">
            <div className="form-group">
              <label>
                Nearby Landmarks <small className="text-muted">(one per line — format: &quot;Dubai Mall — 8 min&quot;)</small>
              </label>
              <textarea
                name="nearby_landmarks"
                className="form-control"
                rows={5}
                placeholder={"Dubai Mall — 8 min\nBurj Khalifa — 10 min\nDXB Airport — 20 min"}
                defaultValue={o("nearby_landmarks", d?.nearby_landmarks)}
              />
            </div>
          </div>
        </div>

        {/* Submit */}
        <div className="row mb-4">
          <div className="col-12">
            <div className="d-flex justify-content-end gap-2">
              <Link href="/admin/developments" className="btn btn-outline-secondary px-4">
                Cancel
              </Link>
              <button type="submit" className="btn btn-primary px-4 hstack gap-2">
                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4" />
                {isEdit ? "Update Development" : "Save Development"}
              </button>
            </div>
          </div>
        </div>
      </form>
    </>
  );
}
