"use client";

import Link from "next/link";
import { useActionState } from "react";
import { createRecognition, updateRecognition } from "@/actions/admin/recognitions";
import type { AdminFormState } from "@/lib/admin/form-state";
import { old, oldChecked } from "@/lib/admin/projects-format";
import { asset } from "@/lib/url";

export type RecognitionFormData = {
  id: number;
  media_name: string | null;
  url: string | null;
  logo: string | null;
  sort_order: number | null;
  is_active: number;
};

/** Form of admin/recognitions/create.blade.php (no `recognition`) and edit.blade.php. */
export function RecognitionForm({ recognition: r, flash }: { recognition?: RecognitionFormData; flash?: React.ReactNode }) {
  const isEdit = !!r;
  const action = isEdit ? updateRecognition.bind(null, r.id) : createRecognition;
  const [state, formAction] = useActionState<AdminFormState, FormData>(action, {});
  const o = (key: string, fallback: unknown = "") => old(state, key, fallback);
  const hasErrors = !!state.errors && Object.keys(state.errors).length > 0;

  return (
    <>
      {/* Alerts */}
      {hasErrors && <div className="alert alert-danger text-center">Please fill in the required fields.</div>}
      {flash}

      {/* Form */}
      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-body">
              <form action={formAction} key={state.n ?? 0}>
                <div className="row">
                  {/* Media Name */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="media_name" placeholder="Media Name" defaultValue={o("media_name", r?.media_name)} required />
                      {isEdit ? (
                        <label>Media Name *</label>
                      ) : (
                        <label>
                          Media Name * <small className="text-muted">(e.g. Forbes, CNN, Arabian Business)</small>
                        </label>
                      )}
                      {state.errors?.media_name && <span className="text-danger text-sm">This field is required.</span>}
                    </div>
                  </div>

                  {/* URL */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input type="url" className="form-control" name="url" placeholder="https://example.com" defaultValue={o("url", r?.url)} />
                      <label>Website URL</label>
                      {!isEdit && state.errors?.url && <span className="text-danger text-sm">Please enter a valid URL.</span>}
                    </div>
                  </div>

                  {/* Logo */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">{isEdit ? "Logo" : "Logo *"}</label>
                    {r?.logo && (
                      <div className="mb-2 d-flex align-items-center gap-3">
                        <img src={asset(r.logo)} alt={r.media_name ?? ""} height={50} style={{ objectFit: "contain", maxWidth: 120 }} />
                        <small className="text-muted">Current logo</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="logo" accept="image/*" />
                    <div className="form-text">{isEdit ? "Leave empty to keep current logo." : "PNG with transparent background recommended."}</div>
                  </div>

                  {/* Sort Order */}
                  <div className="col-md-3">
                    <div className="form-floating mb-3">
                      <input
                        type="number"
                        className="form-control"
                        name="sort_order"
                        placeholder="Sort Order"
                        defaultValue={o("sort_order", isEdit ? r.sort_order : 0)}
                        min={0}
                      />
                      <label>Sort Order</label>
                    </div>
                  </div>

                  {/* Status */}
                  <div className="col-md-3 d-flex align-items-center">
                    <div className="form-check form-switch ms-2">
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

                  {/* Submit */}
                  <div className="col-12">
                    <div className="d-flex justify-content-end gap-2 mt-3">
                      <Link href="/admin/recognitions" className="btn btn-outline-secondary">
                        Cancel
                      </Link>
                      <button type="submit" className="btn btn-primary hstack gap-2">
                        <iconify-icon icon="solar:diskette-line-duotone" class="fs-4" />
                        {isEdit ? "Update Recognition" : "Save Recognition"}
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
