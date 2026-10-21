"use client";

import Link from "next/link";
import { useActionState } from "react";
import { createInvestment, updateInvestment } from "@/actions/admin/investments";
import type { AdminFormState } from "@/lib/admin/form-state";
import { dec, old, oldChecked } from "@/lib/admin/projects-format";
import { asset } from "@/lib/url";

export type InvestmentFormData = {
  id: number;
  title: string | null;
  investment_type: string | null;
  property_id: number | null;
  community_id: number | null;
  description: string | null;
  price: number | null;
  price_currency: string | null;
  annual_rental: string | null;
  roi_percentage: number | null;
  gross_yield: number | null;
  net_yield: number | null;
  main_image: string | null;
  is_featured: number;
  is_active: number;
};

type Props = {
  communities: { id: number; name: string | null }[];
  properties: { id: number; title: string | null; reference_no: string | null }[];
  investment?: InvestmentFormData;
  flash?: React.ReactNode;
};

/** Form of admin/investments/create.blade.php (no `investment`) and edit.blade.php. */
export function InvestmentForm({ communities, properties, investment: inv, flash }: Props) {
  const isEdit = !!inv;
  const action = isEdit ? updateInvestment.bind(null, inv.id) : createInvestment;
  const [state, formAction] = useActionState<AdminFormState, FormData>(action, {});
  const o = (key: string, fallback: unknown = "") => old(state, key, fallback);
  const hasErrors = !!state.errors && Object.keys(state.errors).length > 0;

  const numberField = (name: string, placeholder: string, label: string, value: unknown) => (
    <div className="form-floating mb-3">
      <input type="number" className="form-control" name={name} placeholder={placeholder} defaultValue={o(name, value)} step="0.01" />
      <label>{label}</label>
    </div>
  );

  return (
    <>
      {hasErrors && <div className="alert alert-danger text-center">Please fill in the required fields.</div>}
      {flash}

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
                      <input type="text" className="form-control" name="title" placeholder="Investment Title" defaultValue={o("title", inv?.title)} required />
                      <label>Investment Title *</label>
                      {!isEdit && state.errors?.title && <span className="text-danger text-sm">This field is required.</span>}
                    </div>
                  </div>

                  {/* Investment Type */}
                  <div className="col-md-4">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="investment_type" defaultValue={o("investment_type", inv?.investment_type)}>
                        <option value="">Select Type</option>
                        <option value="Buy-to-Let">Buy-to-Let</option>
                        <option value="Commercial">Commercial</option>
                        <option value="Off-Plan">Off-Plan</option>
                        <option value="Short-Term">Short-Term Rental</option>
                        <option value="Land">Land</option>
                      </select>
                      <label>Investment Type</label>
                    </div>
                  </div>

                  {/* Linked Property */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="property_id" defaultValue={o("property_id", inv?.property_id)}>
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

                  {/* Community */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="community_id" defaultValue={o("community_id", inv?.community_id)}>
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

                  {/* Description */}
                  <div className="col-md-12">
                    <label className="form-label fw-semibold">Description</label>
                    <textarea
                      className="form-control"
                      name="description"
                      rows={5}
                      placeholder={isEdit ? undefined : "Investment description..."}
                      defaultValue={o("description", inv?.description)}
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 2: Financial Details ===== */}
        <div className="row">
          <div className="col-12">
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:chart-line-duotone" class="me-2" />
                  Financial Details
                </h5>
                <div className="row">
                  <div className="col-md-4">{numberField("price", "Price", "Price", dec(inv?.price))}</div>

                  <div className="col-md-2">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="price_currency" defaultValue={isEdit ? o("price_currency", inv.price_currency) : "AED"}>
                        <option value="AED">AED</option>
                        <option value="USD">USD</option>
                        <option value="GBP">GBP</option>
                        <option value="EUR">EUR</option>
                      </select>
                      <label>Currency</label>
                    </div>
                  </div>

                  <div className="col-md-3">{numberField("annual_rental", "Annual Rental", "Annual Rental Income", inv?.annual_rental)}</div>
                  <div className="col-md-3">{numberField("roi_percentage", "ROI %", "ROI (%)", dec(inv?.roi_percentage))}</div>
                  <div className="col-md-3">{numberField("gross_yield", "Gross Yield %", "Gross Yield (%)", dec(inv?.gross_yield))}</div>
                  <div className="col-md-3">{numberField("net_yield", "Net Yield %", "Net Yield (%)", dec(inv?.net_yield))}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===== SECTION 3: Media & Options ===== */}
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
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">Main Image</label>
                    {inv?.main_image && (
                      <div className="mb-2">
                        <img src={asset(inv.main_image)} alt="Main Image" className="rounded" height={80} style={{ objectFit: "cover" }} />
                        <small className="text-muted d-block mt-1">Current image</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="main_image" accept="image/*" />
                    {isEdit && <div className="form-text">Leave empty to keep current image.</div>}
                  </div>

                  {/* Flags */}
                  <div className="col-md-6 d-flex align-items-center gap-4">
                    <div className="form-check form-switch">
                      <input
                        className="form-check-input"
                        type="checkbox"
                        name="is_featured"
                        id="is_featured"
                        defaultChecked={oldChecked(state, "is_featured", inv?.is_featured)}
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
                        defaultChecked={isEdit ? oldChecked(state, "is_active", inv.is_active) : true}
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
              <Link href="/admin/investments" className="btn btn-outline-secondary px-4">
                Cancel
              </Link>
              <button type="submit" className="btn btn-primary px-4 hstack gap-2">
                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4" />
                {isEdit ? "Update Investment" : "Save Investment"}
              </button>
            </div>
          </div>
        </div>
      </form>
    </>
  );
}
