import Link from "next/link";
import { redirect } from "next/navigation";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { requireAdmin } from "@/lib/auth";
import { getAdminProfile } from "@/lib/admin/profile";
import { formatDate } from "@/lib/format";
import { asset } from "@/lib/url";
import { ProfileForm } from "./ProfileForm";

// AdminController@profile → admin/profile/index.blade.php
export default async function ProfilePage() {
  const session = await requireAdmin();
  const admin = await getAdminProfile(session.id);
  if (!admin) redirect("/admin/sign-in");

  const left = (
    <div className="col-lg-4">
      <div className="card">
        <div className="card-body py-5 text-center">
          {/* Avatar */}
          <div className="mb-3">
            {admin.avatar ? (
              <img
                src={asset(admin.avatar)}
                alt={admin.name ?? ""}
                className="rounded-circle"
                width={100}
                height={100}
                style={{ objectFit: "cover", border: "3px solid #214A8B" }}
              />
            ) : (
              <div
                className="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                style={{ width: 100, height: 100, background: "#214A8B", border: "3px solid #D4AF37" }}
              >
                <iconify-icon icon="solar:user-bold" className="text-white" style={{ fontSize: 48 }} />
              </div>
            )}
          </div>

          <h5 className="fw-bold mb-1">{admin.name}</h5>
          <p className="text-muted mb-1">{admin.email}</p>
          {admin.phone && (
            <p className="text-muted mb-1">
              <iconify-icon icon="solar:phone-line-duotone" className="me-1" />
              {admin.phone}
            </p>
          )}
          <span className="badge bg-primary-subtle text-primary px-3 py-1 mt-1">Administrator</span>

          <hr className="my-4" />

          <div className="text-start">
            <div className="d-flex align-items-center gap-2 mb-3">
              <iconify-icon icon="solar:calendar-line-duotone" className="text-muted fs-5" />
              <div>
                <small className="text-muted d-block">Member Since</small>
                <small className="fw-semibold">{formatDate(admin.created_at ?? new Date(), "d M Y")}</small>
              </div>
            </div>
            <div className="d-flex align-items-center gap-2 mb-3">
              <iconify-icon icon="solar:shield-check-line-duotone" className="text-success fs-5" />
              <div>
                <small className="text-muted d-block">Role</small>
                <small className="fw-semibold">Super Admin</small>
              </div>
            </div>
            <div className="d-flex align-items-center gap-2">
              <iconify-icon icon="solar:clock-circle-line-duotone" className="text-muted fs-5" />
              <div>
                <small className="text-muted d-block">Last Updated</small>
                <small className="fw-semibold">{formatDate(admin.updated_at ?? new Date(), "d M Y, h:i A")}</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Quick Links */}
      <div className="card mt-3">
        <div className="card-body p-2">
          <Link href="/admin" className="btn btn-outline-primary w-100 hstack gap-2 justify-content-center mb-2">
            <iconify-icon icon="solar:widget-5-line-duotone" />
            Go to Dashboard
          </Link>
          <Link href="/admin/settings" className="btn btn-outline-secondary w-100 hstack gap-2 justify-content-center">
            <iconify-icon icon="solar:settings-line-duotone" />
            Site Settings
          </Link>
        </div>
      </div>
    </div>
  );

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
                    <h4 className="mb-4 mb-sm-0 card-title">My Profile</h4>
                    <nav aria-label="breadcrumb" className="ms-auto">
                      <ol className="breadcrumb">
                        <li className="breadcrumb-item d-flex align-items-center">
                          <a className="text-muted text-decoration-none d-flex">
                            <iconify-icon icon="solar:home-2-line-duotone" className="fs-6" />
                          </a>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Admin</span>
                        </li>
                        <li className="breadcrumb-item" aria-current="page">
                          <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">Profile</span>
                        </li>
                      </ol>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <ProfileForm
          left={left}
          flash={<AdminFlash />}
          admin={{
            name: admin.name ?? "",
            email: admin.email ?? "",
            phone: admin.phone ?? "",
            avatar: admin.avatar ? asset(admin.avatar) : null,
            profilePhoto: admin.profile_photo ? asset(admin.profile_photo) : null,
          }}
        />
      </div>
    </div>
  );
}
