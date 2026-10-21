"use client";

import Link from "next/link";
import { useActionState, useState } from "react";
import type { AdminFormState } from "@/lib/admin/form-state";

type Props = {
  mode: "create" | "edit";
  action: (prev: AdminFormState, form: FormData) => Promise<AdminFormState>;
  defaults: { name: string; category: string; icon: string; sort_order: string };
  flash: React.ReactNode;
};

/** admin/amenities/create.blade.php and edit.blade.php */
export function AmenityForm(props: Props) {
  const [state, action] = useActionState<AdminFormState, FormData>(props.action, {});
  // The form remounts (key) after a failed submit, which resets the preview like a page reload did.
  return <AmenityFormInner key={state.n ?? 0} {...props} state={state} formAction={action} />;
}

function AmenityFormInner({
  mode,
  defaults,
  flash,
  state,
  formAction,
}: Props & { state: AdminFormState; formAction: (form: FormData) => void }) {
  const isEdit = mode === "edit";
  const errors = state.errors ?? {};
  const old = (key: string, fallback: string) => {
    const v = state.values?.[key];
    return typeof v === "string" ? v : fallback;
  };
  // previewIcon(): create starts empty, edit starts with the saved icon
  const [preview, setPreview] = useState(isEdit ? defaults.icon : "");

  return (
    <>
      {/* Alerts */}
      {Object.keys(errors).length > 0 && <div className="alert alert-danger text-center">Please fill in the required fields.</div>}
      {flash}

      {/* Form */}
      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-body">
              <form action={formAction}>
                <div className="row">
                  {/* Name */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="name"
                        id="amenity_name"
                        placeholder="Enter Amenity Name"
                        defaultValue={old("name", defaults.name)}
                        required
                      />
                      <label>Name *</label>
                      {errors.name && <span className="text-danger text-sm">This field is required.</span>}
                    </div>
                  </div>

                  {/* Category */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <select className="form-select" name="category" id="category" required defaultValue={old("category", defaults.category)}>
                        <option value="" disabled>
                          Select Category
                        </option>
                        <option value="Indoor">Indoor</option>
                        <option value="Outdoor">Outdoor</option>
                        <option value="Building">Building</option>
                        <option value="View">View</option>
                      </select>
                      <label htmlFor="category">Category *</label>
                      {errors.category && <span className="text-danger text-sm">This field is required.</span>}
                    </div>
                  </div>

                  {/* Icon */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="icon"
                        id="amenity_icon"
                        placeholder="Icon"
                        defaultValue={old("icon", defaults.icon)}
                        onInput={(e) => setPreview(e.currentTarget.value)}
                      />
                      <label>Icon (Iconify)</label>
                    </div>
                    <div className="form-text mb-3">
                      Enter iconify icon name e.g. <code>solar:swimming-bold</code> — browse at{" "}
                      <a href="https://icon-sets.iconify.design" target="_blank">
                        icon-sets.iconify.design
                      </a>
                      &nbsp;
                      <span id="icon_preview" className="ms-2 fs-4 text-primary">
                        {preview && <iconify-icon icon={preview} />}
                      </span>
                    </div>
                  </div>

                  {/* Sort Order */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="number"
                        className="form-control"
                        name="sort_order"
                        placeholder="Sort Order"
                        defaultValue={old("sort_order", defaults.sort_order)}
                        min={0}
                      />
                      <label>Sort Order</label>
                    </div>
                  </div>

                  {/* Submit */}
                  <div className="col-12">
                    <div className="d-flex justify-content-end gap-2 mt-3">
                      <Link href="/admin/amenities" className="btn btn-outline-secondary">
                        Cancel
                      </Link>
                      <button type="submit" className="btn btn-primary hstack gap-2">
                        <iconify-icon icon="solar:diskette-line-duotone" className="fs-4" />
                        {isEdit ? "Update Amenity" : "Save Amenity"}
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
