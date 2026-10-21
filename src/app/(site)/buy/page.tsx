import Link from "@/components/site/Link";
import { Pagination } from "@/components/site/Pagination";
import { numberFormat } from "@/lib/format";
import {
  getDevelopmentsPage,
  getFilterCommunities,
  getFilterDevelopers,
  getHandoverYears,
  type DevelopmentFilters,
} from "@/lib/queries/developments";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { asset } from "@/lib/url";
import { FallbackImg } from "./_components/FallbackImg";
import { FilterPanel, FilterProvider, FilterToggle, SortSelect } from "./_components/FilterPanel";
import { lines, pageNumber, requestInput, truthy, type SearchParams } from "./_components/php";
import "./page.css";

// website/developments/index.blade.php — DevelopmentFrontController@index

export const dynamic = "force-dynamic";

type Props = { searchParams: Promise<SearchParams> };

export async function generateMetadata() {
  const settings = await getSettings();
  return pageMetadata({
    title: `Off-Plan Properties for Sale in Dubai — ${pick(settings, "site_name", "Quadrant Properties")}`,
    description:
      "Dubai's future, available today. Explore landmark off-plan developments — each one assessed, shortlisted, and advised on with precision.",
    path: "/buy",
  });
}

const STATUS_LABELS: Record<string, string> = {
  upcoming: "New Launch",
  launched: "New Launch",
  under_construction: "Under Construction",
  completed: "Nearing Handover",
};

const FILTER_KEYS = ["community_id", "developer", "property_type", "bedrooms", "min_price", "max_price", "handover_year", "status"];

const PLACEHOLDER = "/public/assets/img/placeholder.jpg";

/** Carbon::parse($date)->format('Y') */
function year(date: string): string {
  const m = /^(\d{4})/.exec(date);
  if (m) return m[1];
  const d = new Date(date);
  return Number.isNaN(d.getTime()) ? "" : String(d.getFullYear());
}

export default async function DevelopmentsIndex({ searchParams }: Props) {
  const req = requestInput(await searchParams);
  const filters: DevelopmentFilters = req;

  const [developments, communities, developers, handoverYears] = await Promise.all([
    getDevelopmentsPage(filters, pageNumber(req.page)),
    getFilterCommunities(),
    getFilterDevelopers(),
    getHandoverYears(),
  ]);

  const sortHidden = Object.entries(req).filter(([key]) => key !== "sort" && key !== "page");
  const anyFilled = FILTER_KEYS.some((key) => req[key] !== undefined);

  return (
    <>
      {/* PAGE HERO */}
      <section className="qp-offplan-banner">
        <div className="container">
          <h1>Buy</h1>
          <p>
            Dubai&apos;s future, available today. Explore landmark off-plan developments — each one assessed,
            shortlisted, and advised on with precision.
          </p>
        </div>
      </section>

      {/* BREADCRUMB */}
      <section className="breadcrumb-sec">
        <div className="container">
          <div className="row">
            <div className="col-12">
              <div className="bread-container">
                <ul>
                  <li>
                    <Link href="/">Home</Link>
                  </li>
                  <li>
                    <span>Buy</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section style={{ padding: "0 0 60px" }}>
        <div className="container">
          <FilterProvider initialOpen={anyFilled}>
            {/* TOOLBAR: count, sort, filter toggle */}
            <div className="qp-toolbar">
              <p className="qp-result-count">{developments.total} projects found</p>

              <div style={{ display: "flex", gap: 12, alignItems: "center" }}>
                <form method="GET" action="/buy" id="qp-sort-form">
                  {/* preserve all current filters when sort changes */}
                  {sortHidden.map(([key, value]) => (
                    <input type="hidden" name={key} value={value} key={key} />
                  ))}
                  <SortSelect value={req.sort ?? "newest"} />
                </form>

                <FilterToggle />
              </div>
            </div>

            {/* FILTER PANEL */}
            <FilterPanel>
              <form method="GET" action="/buy">
                <input type="hidden" name="sort" value={req.sort ?? ""} />

                <div className="qp-filter-grid">
                  {/* Community */}
                  <div className="qp-filter-field">
                    <label>Community</label>
                    <select name="community_id" defaultValue={req.community_id ?? ""}>
                      <option value="">All Communities</option>
                      {communities.map((community) => (
                        <option value={String(community.id)} key={community.id}>
                          {community.name}
                        </option>
                      ))}
                    </select>
                  </div>

                  {/* Developer */}
                  <div className="qp-filter-field">
                    <label>Developer</label>
                    <select name="developer" defaultValue={req.developer ?? ""}>
                      <option value="">All Developers</option>
                      {developers.map((dev) => (
                        <option value={dev} key={dev}>
                          {dev}
                        </option>
                      ))}
                    </select>
                  </div>

                  {/* Property Type */}
                  <div className="qp-filter-field">
                    <label>Property Type</label>
                    <select name="property_type" defaultValue={req.property_type ?? ""}>
                      <option value="">All Types</option>
                      <option value="Apartment">Apartment</option>
                      <option value="Townhouse">Townhouse</option>
                      <option value="Villa">Villa</option>
                      <option value="Penthouse">Penthouse</option>
                      <option value="Plot">Plot</option>
                    </select>
                  </div>

                  {/* Bedrooms */}
                  <div className="qp-filter-field">
                    <label>Bedrooms</label>
                    <select name="bedrooms" defaultValue={req.bedrooms ?? ""}>
                      <option value="">Any</option>
                      <option value="Studio">Studio</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5+</option>
                    </select>
                  </div>

                  {/* Price Min */}
                  <div className="qp-filter-field">
                    <label>Min Price (AED)</label>
                    <input type="number" name="min_price" defaultValue={req.min_price ?? ""} placeholder="e.g. 500000" />
                  </div>

                  {/* Price Max */}
                  <div className="qp-filter-field">
                    <label>Max Price (AED)</label>
                    <input type="number" name="max_price" defaultValue={req.max_price ?? ""} placeholder="e.g. 5000000" />
                  </div>

                  {/* Handover Year */}
                  <div className="qp-filter-field">
                    <label>Handover</label>
                    <select name="handover_year" defaultValue={req.handover_year ?? ""}>
                      <option value="">Any Year</option>
                      {handoverYears.map((y) => (
                        <option value={String(y)} key={y}>
                          {y}
                        </option>
                      ))}
                    </select>
                  </div>

                  {/* Status */}
                  <div className="qp-filter-field">
                    <label>Status</label>
                    <select name="status" defaultValue={req.status ?? ""}>
                      <option value="">All Status</option>
                      <option value="upcoming">New Launch</option>
                      <option value="under_construction">Under Construction</option>
                      <option value="completed">Nearing Handover</option>
                    </select>
                  </div>
                </div>

                <div className="qp-filter-actions">
                  <button type="submit" className="qp-btn-navy-sm">
                    Apply Filters
                  </button>
                  <Link href="/buy" className="qp-btn-outline-sm">
                    Clear All
                  </Link>
                </div>
              </form>
            </FilterPanel>
          </FilterProvider>

          {/* PROJECT CARDS GRID */}
          <div className="qp-projects-grid">
            {developments.data.length > 0 ? (
              developments.data.map((dev, i) => {
                // Payment plan highlight — first line as the "60/40"-style highlight
                const first = dev.payment_plan ? lines(dev.payment_plan)[0] : undefined;
                const paymentHighlight = first && first.index === 0 ? first.line : null;
                // LazyLoadImages middleware: first 3 images eager
                const lazy = i >= 3 ? ({ loading: "lazy", decoding: "async" } as const) : {};

                return (
                  <div className="qp-project-card" key={dev.id}>
                    <div className="qp-project-img">
                      {truthy(dev.status) && (
                        <span className="qp-status-tag">{STATUS_LABELS[dev.status] ?? "New Launch"}</span>
                      )}
                      {truthy(dev.main_image) ? (
                        <FallbackImg {...lazy} src={asset(dev.main_image)} alt={dev.title} fallback={PLACEHOLDER} />
                      ) : (
                        <img {...lazy} src={PLACEHOLDER} alt={dev.title} />
                      )}
                    </div>

                    <div className="qp-project-body">
                      <h3>{dev.title}</h3>

                      {truthy(dev.developer_name) && <p className="qp-project-developer">{dev.developer_name}</p>}

                      {truthy(dev.community_name) && (
                        <p className="qp-project-loc">
                          <svg fill="none" viewBox="0 0 24 24" strokeWidth="1.5">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"
                            />
                          </svg>
                          {dev.community_name}
                        </p>
                      )}

                      {truthy(dev.price_from) && (
                        <p className="qp-project-price">
                          From {dev.price_currency ?? "AED"} {numberFormat(dev.price_from)}
                        </p>
                      )}

                      <div className="qp-project-facts">
                        {truthy(dev.bedroom_range) && <span>{dev.bedroom_range}</span>}
                        {truthy(dev.handover_date) && <span>&middot; Handover {year(dev.handover_date)}</span>}
                        {truthy(paymentHighlight) && <span>&middot; {paymentHighlight}</span>}
                      </div>

                      <Link href={`/buy/${dev.slug}`} className="qp-view-btn">
                        View Project
                      </Link>
                    </div>
                  </div>
                );
              })
            ) : (
              <p style={{ gridColumn: "1/-1", textAlign: "center", color: "#5B6678", padding: "40px 0" }}>
                No projects match your filters.{" "}
                <Link href="/buy" style={{ color: "#C8A965" }}>
                  Clear filters
                </Link>{" "}
                to see all projects.
              </p>
            )}
          </div>

          {/* PAGINATION */}
          <Pagination
            currentPage={developments.currentPage}
            lastPage={developments.lastPage}
            path="/buy"
            query={req}
            style={{ marginTop: 44 }}
          />
        </div>
      </section>
    </>
  );
}
