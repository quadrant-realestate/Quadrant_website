"use client";

import Link from "next/link";
import { useActionState, useRef } from "react";
import { createCommunity, updateCommunity } from "@/actions/admin/communities";
import type { AdminFormState } from "@/lib/admin/form-state";
import { dec, jsSlug, old, oldChecked } from "@/lib/admin/projects-format";
import { asset } from "@/lib/url";

export type CommunityFormData = {
  id: number;
  name: string | null;
  slug: string | null;
  city: string | null;
  sort_order: number | null;
  description: string | null;
  image: string | null;
  banner_image: string | null;
  latitude: number | null;
  longitude: number | null;
  meta_title: string | null;
  meta_desc: string | null;
  is_active: number;
  is_featured: number;
};

/** Form of admin/communities/create.blade.php (no `community`) and edit.blade.php. */
export function CommunityForm({ community: c, flash }: { community?: CommunityFormData; flash?: React.ReactNode }) {
  const isEdit = !!c;
  const action = isEdit ? updateCommunity.bind(null, c.id) : createCommunity;
  const [state, formAction] = useActionState<AdminFormState, FormData>(action, {});
  const slugRef = useRef<HTMLInputElement>(null);
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
                  {/* Name */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="name"
                        id="community_name"
                        placeholder="Community Name"
                        defaultValue={o("name", c?.name)}
                        onInput={(e) => {
                          // generateSlug()
                          if (slugRef.current) slugRef.current.value = jsSlug(e.currentTarget.value);
                        }}
                        required
                      />
                      <label>Name *</label>
                      {state.errors?.name && <span className="text-danger text-sm">This field is required.</span>}
                    </div>
                  </div>

                  {/* Slug */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="slug"
                        id="community_slug"
                        placeholder="Slug"
                        defaultValue={o("slug", c?.slug)}
                        ref={slugRef}
                      />
                      <label>{isEdit ? "Slug" : "Slug *"}</label>
                    </div>
                  </div>

                  {/* City */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="city"
                        placeholder="City"
                        defaultValue={o("city", isEdit ? c.city : "Dubai")}
                        required
                      />
                      <label>City *</label>
                      {!isEdit && state.errors?.city && <span className="text-danger text-sm">This field is required.</span>}
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
                        defaultValue={o("sort_order", isEdit ? c.sort_order : 0)}
                        min={0}
                      />
                      <label>Sort Order</label>
                    </div>
                  </div>

                  {/* Description */}
                  <div className="col-md-12">
                    <div className="form-floating mb-3">
                      <textarea
                        className="form-control"
                        name="description"
                        placeholder="Description"
                        style={{ height: 120 }}
                        defaultValue={o("description", c?.description)}
                      />
                      <label>Description</label>
                    </div>
                  </div>

                  {/* Community Image */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">Community Image</label>
                    {c?.image && (
                      <div className="mb-2">
                        <img src={asset(c.image)} alt="Current Image" className="rounded" height={80} style={{ objectFit: "cover" }} />
                        <small className="text-muted d-block mt-1">Current image</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="image" accept="image/*" />
                    <div className="form-text">{isEdit ? "Leave empty to keep current image." : "Recommended: 800x600px"}</div>
                  </div>

                  {/* Banner Image */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">Banner Image</label>
                    {c?.banner_image && (
                      <div className="mb-2">
                        <img src={asset(c.banner_image)} alt="Current Banner" className="rounded" height={80} style={{ objectFit: "cover" }} />
                        <small className="text-muted d-block mt-1">Current banner</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="banner_image" accept="image/*" />
                    <div className="form-text">{isEdit ? "Leave empty to keep current banner." : "Recommended: 1920x600px"}</div>
                  </div>

                  {/* Latitude */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="latitude" placeholder="Latitude" defaultValue={o("latitude", dec(c?.latitude))} />
                      <label>Latitude</label>
                    </div>
                  </div>

                  {/* Longitude */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="longitude" placeholder="Longitude" defaultValue={o("longitude", dec(c?.longitude))} />
                      <label>Longitude</label>
                    </div>
                  </div>

                  {/* Meta Title */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input type="text" className="form-control" name="meta_title" placeholder="Meta Title" defaultValue={o("meta_title", c?.meta_title)} />
                      <label>Meta Title</label>
                    </div>
                  </div>

                  {/* Meta Description */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className="form-control"
                        name="meta_desc"
                        placeholder="Meta Description"
                        defaultValue={o("meta_desc", c?.meta_desc)}
                      />
                      <label>Meta Description</label>
                    </div>
                  </div>

                  {/* Toggles */}
                  <div className="col-md-6 d-flex align-items-center gap-4 mb-3">
                    <div className="form-check form-switch">
                      <input
                        className="form-check-input"
                        type="checkbox"
                        name="is_active"
                        id="is_active"
                        defaultChecked={isEdit ? oldChecked(state, "is_active", c.is_active) : true}
                      />
                      <label className="form-check-label fw-semibold" htmlFor="is_active">
                        Active
                      </label>
                    </div>
                    <div className="form-check form-switch">
                      <input
                        className="form-check-input"
                        type="checkbox"
                        name="is_featured"
                        id="is_featured"
                        defaultChecked={isEdit ? oldChecked(state, "is_featured", c.is_featured) : false}
                      />
                      <label className="form-check-label fw-semibold" htmlFor="is_featured">
                        Featured
                      </label>
                    </div>
                  </div>

                  {/* Submit */}
                  <div className="col-12">
                    <div className="d-flex justify-content-end gap-2 mt-3">
                      <Link href="/admin/communities" className="btn btn-outline-secondary">
                        Cancel
                      </Link>
                      <button type="submit" className="btn btn-primary hstack gap-2">
                        <iconify-icon icon="solar:diskette-line-duotone" class="fs-4" />
                        {isEdit ? "Update Community" : "Save Community"}
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
