import Link from "next/link";
import { updateSettings } from "@/actions/admin/settings";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { settingsForGroup } from "@/lib/admin/settings";
import { ucfirst } from "@/lib/format";
import { asset } from "@/lib/url";
import { PageHeader } from "../_projects/PageHeader";

const TABS = [
  { group: "general", icon: "solar:settings-line-duotone", label: "General", title: "General Settings", info: "Site name, logo, favicon and company RERA number." },
  {
    group: "contact",
    icon: "solar:phone-line-duotone",
    label: "Contact Info",
    title: "Contact Information",
    info: "Phone, WhatsApp, email, office address and Google Maps embed.",
  },
  { group: "social", icon: "solar:share-line-duotone", label: "Social Links", title: "Social Media Links", info: "Links to all social media profiles." },
  { group: "homepage", icon: "solar:home-angle-line-duotone", label: "Homepage", title: "Homepage Settings", info: "Hero section, about text and stats counters." },
];

export default async function SettingsPage({ searchParams }: { searchParams: Promise<Record<string, string | string[] | undefined>> }) {
  const sp = await searchParams;
  const group = typeof sp.group === "string" && sp.group !== "" ? sp.group : "general";
  const settings = await settingsForGroup(group);
  const current = TABS.find((t) => t.group === group);

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Site Settings" crumbs={["Configuration", ucfirst(group)]} />

        <AdminFlash />

        <div className="row">
          {/* LEFT: Tab Navigation */}
          <div className="col-lg-3">
            <div className="card">
              <div className="card-body p-2">
                <div className="nav flex-column nav-pills">
                  {TABS.map((tab) => (
                    <Link
                      key={tab.group}
                      className={`nav-link hstack gap-3 ${group === tab.group ? "active" : ""}`}
                      href={`/admin/settings?group=${tab.group}`}
                    >
                      <iconify-icon icon={tab.icon} class="fs-5" />
                      {tab.label}
                    </Link>
                  ))}
                </div>
              </div>
            </div>

            {/* Group Info Card */}
            <div className="card mt-3">
              <div className="card-body">
                <h6 className="fw-semibold mb-2">
                  {current && (
                    <>
                      <iconify-icon icon={current.icon} class="me-1" />
                      {current.label}
                    </>
                  )}
                </h6>
                <small className="text-muted">{current?.info}</small>
                <div className="mt-2">
                  <span className="badge bg-primary-subtle text-primary">{settings.length} settings</span>
                </div>
              </div>
            </div>
          </div>

          {/* RIGHT: Settings Form */}
          <div className="col-lg-9">
            <div className="card">
              <div className="card-body">
                <h5 className="card-title mb-4 pb-2 border-bottom">
                  {current && (
                    <>
                      <iconify-icon icon={current.icon} class="me-2" />
                      {current.title}
                    </>
                  )}
                </h5>

                {settings.length > 0 ? (
                  <form action={updateSettings}>
                    <input type="hidden" name="group" value={group} />

                    <div className="row">
                      {settings.map((setting) => (
                        // Full width for textarea and video, half for rest
                        <div className={`col-md-${setting.type === "textarea" || setting.type === "video" ? "12" : "6"} mb-4`} key={setting.id}>
                          {setting.type === "textarea" ? (
                            <>
                              <label className="form-label fw-semibold">{setting.label}</label>
                              <textarea
                                className="form-control"
                                name={`settings[${setting.key}]`}
                                rows={4}
                                placeholder={setting.label ?? ""}
                                defaultValue={setting.value ?? ""}
                              />
                            </>
                          ) : setting.type === "image" ? (
                            <>
                              <label className="form-label fw-semibold">{setting.label}</label>
                              {setting.value && (
                                <div className="mb-2">
                                  <img
                                    src={asset(setting.value)}
                                    alt={setting.label ?? ""}
                                    height={50}
                                    style={{ objectFit: "contain", maxWidth: 150, background: "#f8f9fa", padding: 4, borderRadius: 6 }}
                                  />
                                  <small className="text-muted d-block mt-1">Current {(setting.label ?? "").toLowerCase()}</small>
                                </div>
                              )}
                              <input className="form-control" type="file" name={`settings[${setting.key}]`} accept="image/*" />
                              <div className="form-text">Leave empty to keep current image.</div>
                            </>
                          ) : setting.type === "video" ? (
                            <>
                              <label className="form-label fw-semibold">{setting.label}</label>
                              {setting.value && (
                                <div className="mb-2">
                                  <a href={setting.value} target="_blank" className="btn btn-sm btn-outline-primary">
                                    <iconify-icon icon="solar:play-circle-line-duotone" class="me-1" />
                                    Preview Current Video
                                  </a>
                                </div>
                              )}
                              <div className="form-floating">
                                <input
                                  type="url"
                                  className="form-control"
                                  name={`settings[${setting.key}]`}
                                  id={`setting_${setting.key}`}
                                  placeholder={setting.label ?? ""}
                                  defaultValue={setting.value ?? ""}
                                />
                                <label htmlFor={`setting_${setting.key}`}>{setting.label}</label>
                              </div>
                              <div className="form-text">Enter a YouTube or direct video URL.</div>
                            </>
                          ) : (
                            <div className="form-floating">
                              <input
                                type="text"
                                className="form-control"
                                name={`settings[${setting.key}]`}
                                id={`setting_${setting.key}`}
                                placeholder={setting.label ?? ""}
                                defaultValue={setting.value ?? ""}
                              />
                              <label htmlFor={`setting_${setting.key}`}>{setting.label}</label>
                            </div>
                          )}
                        </div>
                      ))}
                    </div>

                    {/* Submit */}
                    <div className="d-flex justify-content-end gap-2 mt-2 pt-3 border-top">
                      <button type="submit" className="btn btn-primary px-4 hstack gap-2">
                        <iconify-icon icon="solar:diskette-line-duotone" class="fs-4" />
                        Save Settings
                      </button>
                    </div>
                  </form>
                ) : (
                  <div className="text-center py-5 text-muted">
                    <iconify-icon icon="solar:settings-line-duotone" class="fs-1 d-block mb-3" />
                    <p className="mb-1">No settings found for this group.</p>
                    <small>
                      Make sure the <code>settings</code> table has rows with <code>group = &apos;{group}&apos;</code>
                    </small>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
