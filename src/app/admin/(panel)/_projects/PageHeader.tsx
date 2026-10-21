// Page header card with breadcrumb badges, as at the top of the Blade admin pages
// (developments, investments, branded residences, communities, recognitions, settings).
export function PageHeader({ title, crumbs }: { title: string; crumbs: string[] }) {
  return (
    <div className="row">
      <div className="col-lg-12 d-flex align-items-strech">
        <div className="card card-body py-3">
          <div className="row align-items-center">
            <div className="col-12">
              <div className="d-sm-flex align-items-center justify-space-between">
                <h4 className="mb-4 mb-sm-0 card-title">{title}</h4>
                <nav aria-label="breadcrumb" className="ms-auto">
                  <ol className="breadcrumb">
                    <li className="breadcrumb-item d-flex align-items-center">
                      <a className="text-muted text-decoration-none d-flex">
                        <iconify-icon icon="solar:home-2-line-duotone" class="fs-6" />
                      </a>
                    </li>
                    {crumbs.map((crumb) => (
                      <li className="breadcrumb-item" aria-current="page" key={crumb}>
                        <span className="badge fw-medium fs-2 bg-primary-subtle text-primary">{crumb}</span>
                      </li>
                    ))}
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

/** Style for a <button> that replaces a bare <a href> toggle link (no button chrome). */
export const linkButton: React.CSSProperties = { background: "none", border: 0, padding: 0, cursor: "pointer" };
