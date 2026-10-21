"use client";

import Link from "next/link";
import { useActionState } from "react";
import { createBrandedResidence, updateBrandedResidence } from "@/actions/admin/branded-residences";
import type { AdminFormState } from "@/lib/admin/form-state";
import { dec, old, oldChecked } from "@/lib/admin/projects-format";
import { asset } from "@/lib/url";

export type BrandedResidenceFormData = {
  id: number;
  brand_name: string | null;
  title: string | null;
  price_from: number | null;
  community_id: number | null;
  property_id: number | null;
  description: string | null;
  main_image: string | null;
  brand_logo: string | null;
  is_featured: number;
  is_active: number;
};

type Props = {
  communities: { id: number; name: string | null }[];
  properties: { id: number; title: string | null; reference_no: string | null }[];
  residence?: BrandedResidenceFormData;
  flash?: React.ReactNode;
};

/** Form of admin/branded_residences/create.blade.php (no `residence`) and edit.blade.php. */
export function BrandedResidenceForm({ communities, properties, residence: r, flash }: Props) {
  const isEdit = !!r;
  const action = isEdit ? updateBrandedResidence.bind(null, r.id) : createBrandedResidence;
  const [state, formAction] = useActionState<AdminFormState, FormData>(action, {});
  const o = (key: string, fallback: unknown = "") => old(state, key, fallback);
  const hasErrors = !!state.errors && Object.keys(state.errors).length > 0;

  return (
    <>
      {hasErrors && <div className="alert alert-danger text-center">Please fill in the required fields.</div>}
      {flash}

      <form action={formAction} key={state.n ?? 0}>
        {/* ===== SECTION 1: Brand & Basic Info ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:crown-line-duotone" class="me-2" />
                  Brand &amp; Basic Information
                </h5>
                <div className="row">
                  {/* Brand Name */}
                  <div className="col-md-4">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="brand_name" placeholder="Brand Name" defaultValue={o("brand_name", r?.brand_name)} required />
                      {isEdit ? (
                        <label>Brand Name *</label>
                      ) : (
                        <label>
                          Brand Name * <small className="text-muted">(e.g. Bulgari, Armani)</small>
                        </label>
                      )}
                      {!isEdit && state.errors?.brand_name && <span className="text-danger text-sm">This field is required.</span>}
                    </div>
                  </div>

                  {/* Title */}
                  <div className="col-md-5">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="title"
                        id={isEdit ? undefined : "residence_title"}
                        placeholder="Residence Title"
                        defaultValue={o("title", r?.title)}
                        required
                      />
                      <label>Residence Title *</label>
                      {!isEdit && state.errors?.title && <span className="text-danger text-sm">This field is required.</span>}
                    </div>
                  </div>

                  {/* Price From */}
                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input
                        type="number"
                        className="form-control"
                        name="price_from"
                        placeholder="Price From"
                        defaultValue={o("price_from", dec(r?.price_from))}
                        step="0.01"
                      />
                      <label>Starting Price (AED)</label>
                    </div>
                  </div>

                  {/* Community */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="community_id" defaultValue={o("community_id", r?.community_id)}>
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

                  {/* Linked Property */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="property_id" defaultValue={o("property_id", r?.property_id)}>
                        <option value="">No Linked Property</option>
                        {properties.map((p) => (
                          <option key={p.id} value={p.id}>
                            {p.title} ({p.reference_no})
                          </option>
                        ))}
                      </select>
                      <label>Linked Property (Optional)</label>
                    </div>
                  </div>

                  {/* Description */}
                  <div className="col-md-12">
                    <label className="form-label fw-semibold">Description</label>
                    <textarea
                      className="form-control"
                      name="description"
                      rows={5}
                      placeholder={isEdit ? undefined : "Branded residence description..."}
                      defaultValue={o("description", r?.description)}
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 2: Media & Options ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:gallery-wide-line-duotone" class="me-2" />
                  Media &amp; Options
                </h5>
                <div className="row">
                  {/* Main Image */}
                  <div className="col-md-4 mb-3">
                    <label className="form-label fw-semibold">Main Image</label>
                    {r?.main_image && (
                      <div className="mb-2">
                        <img src={asset(r.main_image)} alt="Main" className="rounded" height={80} style={{ objectFit: "cover" }} />
                        <small className="text-muted d-block mt-1">Current image</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="main_image" accept="image/*" />
                    <div className="form-text">{isEdit ? "Leave empty to keep current image." : "Cover image for this residence."}</div>
                  </div>

                  {/* Brand Logo */}
                  <div className="col-md-4 mb-3">
                    <label className="form-label fw-semibold">Brand Logo</label>
                    {r?.brand_logo && (
                      <div className="mb-2">
                        <img src={asset(r.brand_logo)} alt="Brand Logo" height={40} style={{ objectFit: "contain" }} />
                        <small className="text-muted d-block mt-1">Current logo</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="brand_logo" accept="image/*" />
                    <div className="form-text">
                      {isEdit ? "Leave empty to keep current logo." : "e.g. Bulgari logo, Armani logo. PNG with transparent bg recommended."}
                    </div>
                  </div>

                  {/* Flags */}
                  <div className="col-md-4 d-flex align-items-center gap-4">
                    <div className="form-check form-switch">
                      <input
                        className="form-check-input"
                        type="checkbox"
                        name="is_featured"
                        id="is_featured"
                        defaultChecked={oldChecked(state, "is_featured", r?.is_featured)}
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
                        defaultChecked={isEdit ? oldChecked(state, "is_active", r.is_active) : true}
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

        {/* Submit */}
        <div className="row mb-4">
          <div className="col-12">
            <div className="d-flex justify-content-end gap-2">
              <Link href="/admin/branded-residences" className="btn btn-outline-secondary px-4">
                Cancel
              </Link>
              <button type="submit" className="btn btn-primary px-4 hstack gap-2">
                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4" />
                {isEdit ? "Update Residence" : "Save Residence"}
              </button>
            </div>
          </div>
        </div>
      </form>
    </>
  );
}
