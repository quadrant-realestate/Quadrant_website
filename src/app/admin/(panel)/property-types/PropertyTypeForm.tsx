"use client";

import Link from "next/link";
import { useActionState, useRef } from "react";
import type { AdminFormState } from "@/lib/admin/form-state";

type Props = {
  mode: "create" | "edit";
  action: (prev: AdminFormState, form: FormData) => Promise<AdminFormState>;
  defaults: { name: string; slug: string; sort_order: string; is_active: boolean };
  flash: React.ReactNode;
};

/** generateSlug() from the Blade view (the controller ignores the submitted slug and uses Str::slug(name)). */
function generateSlug(name: string): string {
  return name
    .trim()
    .toLowerCase()
    .replace(/&/g, "-and-")
    .replace(/[\s\W-]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

/** admin/property_types/create.blade.php and edit.blade.php */
export function PropertyTypeForm({ mode, action: submit, defaults, flash }: Props) {
  const [state, action] = useActionState<AdminFormState, FormData>(submit, {});
  const slugRef = useRef<HTMLInputElement>(null);
  const isEdit = mode === "edit";
  const errors = state.errors ?? {};
  const old = (key: string, fallback: string) => {
    const v = state.values?.[key];
    return typeof v === "string" ? v : fallback;
  };
  // Create always rendered the switch checked; edit used old('is_active', $type->is_active).
  const activeChecked = isEdit ? (state.values ? state.values.is_active !== undefined || defaults.is_active : defaults.is_active) : true;

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
              <form action={action} key={state.n ?? 0}>
                <div className="row">
                  {/* Name */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="name"
                        id="type_name"
                        placeholder="Enter Type Name"
                        defaultValue={old("name", defaults.name)}
                        onInput={(e) => {
                          if (slugRef.current) slugRef.current.value = generateSlug(e.currentTarget.value);
                        }}
                      />
                      <label>Name *</label>
                      {errors.name && <span className="text-danger text-sm ml-2">This field is required.</span>}
                    </div>
                  </div>

                  {/* Slug */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="slug"
                        id="type_slug"
                        placeholder="Slug"
                        defaultValue={old("slug", defaults.slug)}
                        ref={slugRef}
                      />
                      <label>{isEdit ? "Slug" : "Slug *"}</label>
                      {!isEdit && errors.slug && <span className="text-danger text-sm ml-2">This field is required.</span>}
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

                  {/* Status */}
                  <div className="col-md-6 d-flex align-items-center mb-3">
                    <div className="form-check form-switch ms-2">
                      <input className="form-check-input" type="checkbox" name="is_active" id="is_active" defaultChecked={activeChecked} />
                      <label className="form-check-label fw-semibold" htmlFor="is_active">
                        Active
                      </label>
                    </div>
                  </div>

                  {/* Submit */}
                  <div className="col-12">
                    {isEdit ? (
                      <div className="d-md-flex align-items-center">
                        <div className="ms-auto mt-3 mt-md-0">
                          <Link href="/admin/property-types" className="btn btn-outline-secondary me-2">
                            Cancel
                          </Link>
                          <button type="submit" className="btn btn-primary hstack gap-2">
                            <iconify-icon icon="solar:diskette-line-duotone" className="fs-4" />
                            Update Type
                          </button>
                        </div>
                      </div>
                    ) : (
                      <div className="d-flex  gap-2 mt-3">
                        <Link href="/admin/property-types" className="btn btn-outline-secondary">
                          Cancel
                        </Link>
                        <button type="submit" className="btn btn-primary hstack gap-2">
                          <iconify-icon icon="solar:diskette-line-duotone" className="fs-4" />
                          Save Type
                        </button>
                      </div>
                    )}
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
