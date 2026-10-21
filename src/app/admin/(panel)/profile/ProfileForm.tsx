"use client";

import Link from "next/link";
import { useActionState } from "react";
import { updateProfile } from "@/actions/admin/profile";
import type { AdminFormState } from "@/lib/admin/form-state";

type Props = {
  /** The profile card column (server rendered). */
  left: React.ReactNode;
  /** Flash alerts (server rendered). */
  flash: React.ReactNode;
  admin: { name: string; email: string; phone: string; avatar: string | null; profilePhoto: string | null };
};

export function ProfileForm({ left, flash, admin }: Props) {
  const [state, action] = useActionState<AdminFormState, FormData>(updateProfile, {});
  const errors = state.errors ?? {};
  const old = (key: string, fallback: string) => {
    const v = state.values?.[key];
    return typeof v === "string" ? v : fallback;
  };

  return (
    <>
      {/* Alerts */}
      {flash}
      {state.error && (
        <div className="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
          {state.error}
          <button type="button" className="btn-close" data-bs-dismiss="alert" />
        </div>
      )}
      {Object.keys(errors).length > 0 && <div className="alert alert-danger text-center mt-3">Please fix the errors below.</div>}

      <div className="row">
        {left}

        {/* RIGHT: Edit Form */}
        <div className="col-lg-8">
          <form action={action} key={state.n ?? 0}>
            {/* ===== Section 1: Personal Info ===== */}
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:user-circle-line-duotone" className="me-2" />
                  Personal Information
                </h5>
                <div className="row">
                  {/* Name */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className={`form-control${errors.name ? " is-invalid" : ""}`}
                        name="name"
                        id="name"
                        placeholder="Full Name"
                        defaultValue={old("name", admin.name)}
                        required
                      />
                      <label htmlFor="name">Full Name *</label>
                      {errors.name && <span className="text-danger text-sm">{errors.name}</span>}
                    </div>
                  </div>

                  {/* Email */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="email"
                        className={`form-control${errors.email ? " is-invalid" : ""}`}
                        name="email"
                        id="email"
                        placeholder="Email"
                        defaultValue={old("email", admin.email)}
                        required
                      />
                      <label htmlFor="email">Email Address *</label>
                      {errors.email && <span className="text-danger text-sm">{errors.email}</span>}
                    </div>
                  </div>

                  {/* Phone */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="text"
                        className={`form-control${errors.phone ? " is-invalid" : ""}`}
                        name="phone"
                        id="phone"
                        placeholder="Phone"
                        defaultValue={old("phone", admin.phone)}
                      />
                      <label htmlFor="phone">Phone Number</label>
                      {errors.phone && <span className="text-danger text-sm">{errors.phone}</span>}
                    </div>
                  </div>

                  {/* Avatar */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">Avatar</label>
                    {admin.avatar && (
                      <div className="mb-2 d-flex align-items-center gap-3">
                        <img src={admin.avatar} alt={admin.name} className="rounded-circle" width={45} height={45} style={{ objectFit: "cover" }} />
                        <small className="text-muted">Current avatar</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="avatar" accept="image/*" />
                    <div className="form-text">Used in sidebar and header. Square image recommended.</div>
                  </div>

                  {/* Profile Photo */}
                  <div className="col-md-6 mb-3">
                    <label className="form-label fw-semibold">Profile Photo</label>
                    {admin.profilePhoto && (
                      <div className="mb-2 d-flex align-items-center gap-3">
                        <img src={admin.profilePhoto} alt={admin.name} className="rounded" width={45} height={45} style={{ objectFit: "cover" }} />
                        <small className="text-muted">Current profile photo</small>
                      </div>
                    )}
                    <input className="form-control" type="file" name="profile_photo" accept="image/*" />
                    <div className="form-text">Larger photo used on profile page.</div>
                  </div>
                </div>
              </div>
            </div>

            {/* ===== Section 2: Change Password ===== */}
            <div className="card mb-4">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  <iconify-icon icon="solar:lock-password-line-duotone" className="me-2" />
                  Change Password
                  <small className="text-muted fw-normal fs-6 ms-2">— Leave blank to keep current</small>
                </h5>
                <div className="row">
                  {/* Current Password */}
                  <div className="col-md-12">
                    <div className="form-floating mb-3">
                      <input
                        type="password"
                        className={`form-control${errors.current_password ? " is-invalid" : ""}`}
                        name="current_password"
                        id="current_password"
                        placeholder="Current Password"
                      />
                      <label htmlFor="current_password">Current Password</label>
                      {errors.current_password && <span className="text-danger text-sm">{errors.current_password}</span>}
                    </div>
                  </div>

                  {/* New Password */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="password"
                        className={`form-control${errors.new_password ? " is-invalid" : ""}`}
                        name="new_password"
                        id="new_password"
                        placeholder="New Password"
                      />
                      <label htmlFor="new_password">New Password</label>
                      {errors.new_password && <span className="text-danger text-sm">{errors.new_password}</span>}
                    </div>
                  </div>

                  {/* Confirm Password */}
                  <div className="col-md-6">
                    <div className="form-floating mb-3">
                      <input
                        type="password"
                        className="form-control"
                        name="new_password_confirmation"
                        id="new_password_confirmation"
                        placeholder="Confirm New Password"
                      />
                      <label htmlFor="new_password_confirmation">Confirm New Password</label>
                    </div>
                  </div>

                  <div className="col-12">
                    <div className="py-2 px-3 rounded" style={{ background: "#e8f4fd" }}>
                      <small>
                        <iconify-icon icon="solar:info-circle-line-duotone" className="me-1" />
                        Password must be at least 6 characters. Only fill this section if you want to change your password.
                      </small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Submit */}
            <div className="d-flex justify-content-end gap-2 mb-4">
              <Link href="/admin" className="btn btn-outline-secondary px-4">
                Cancel
              </Link>
              <button type="submit" className="btn btn-primary px-4 hstack gap-2">
                <iconify-icon icon="solar:diskette-line-duotone" className="fs-4" />
                Update Profile
              </button>
            </div>
          </form>
        </div>
      </div>
    </>
  );
}
