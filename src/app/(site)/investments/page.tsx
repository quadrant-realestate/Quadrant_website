import Link from "@/components/site/Link";
import { InterestForm } from "./_components/InterestForm";
import { FallbackImg } from "./_components/FallbackImg";
import { FilterCloseLink, FilterOpenLink, FilterOverlay } from "./_components/FilterNav";
import { PriceRange } from "./_components/PriceRange";
import { SearchModal } from "./_components/SearchModal";
import { pageNumber, param, queryString, truthy, type SearchParams } from "./_components/helpers";
import { Pagination } from "@/components/site/Pagination";
import { numberFormat, slugify } from "@/lib/format";
import { getActiveCommunities, getInvestments, getInvestmentTypes } from "@/lib/queries/investments";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { formTimestamp } from "@/lib/spam";
import { asset } from "@/lib/url";
import "./page.css";

type Props = { searchParams: Promise<SearchParams> };

function load(sp: SearchParams) {
  return getInvestments(
    param(sp, "search"),
    param(sp, "investment_type"),
    param(sp, "community_id"),
    param(sp, "min_price"),
    param(sp, "max_price"),
    param(sp, "sort"),
    pageNumber(sp),
  );
}

// Laravel only runs a filter when the value is truthy ("0" is skipped).
function filters(sp: SearchParams): SearchParams {
  const out: SearchParams = { ...sp };
  for (const key of ["search", "investment_type", "community_id", "min_price", "max_price"]) {
    if (!truthy(param(sp, key))) out[key] = undefined;
  }
  return out;
}

export async function generateMetadata({ searchParams }: Props) {
  const sp = await searchParams;
  const settings = await getSettings();
  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai");
  const investments = await load(filters(sp));
  return pageMetadata({
    title: `Exclusive Investment Opportunities in Dubai — ${siteName ?? ""}`,
    description: `Discover exclusive investment opportunities in Dubai with ${siteName ?? ""}. Prime plots, full buildings, hotels, offices and high-potential assets.`,
    path: "/investments",
    noindex: investments.total === 0,
  });
}

export default async function InvestmentsPage({ searchParams }: Props) {
  const sp = await searchParams;
  const [investments, communities, investmentTypes] = await Promise.all([
    load(filters(sp)),
    getActiveCommunities(),
    getInvestmentTypes(),
  ]);

  const search = param(sp, "search");
  const type = param(sp, "investment_type");
  const communityId = param(sp, "community_id");
  const sort = param(sp, "sort");
  const minPrice = param(sp, "min_price");
  const maxPrice = param(sp, "max_price");
  // request('min_price', 0) / request('max_price', 1000000000)
  const rangeMin = minPrice ?? "0";
  const rangeMax = maxPrice ?? "1000000000";

  return (
    <>
      {/* INNER BANNER WITH SEARCH FORM */}
      <section
        className="banner inr-banner"
        style={{ backgroundImage: "url('/public/assets/images/quadrant-skyline-night.jpg')" }}
      >
        <div className="container">
          <div className="slider-info">
            <div className="BannerBox">
              <div className="banner-heading text-center">
                <h1>Investments</h1>
              </div>

              <div className="banner-form mobile-none">
                <form action="/investments" method="GET">
                  <div className="BookingBox">
                    <div className="BookingLocation">
                      <div className="BookingFrom">
                        <input
                          type="text"
                          name="search"
                          className="form-control"
                          placeholder="Search country and city..."
                          defaultValue={search ?? ""}
                        />
                      </div>

                      <div className="BookingFrom p-0">
                        <select className="form-control" name="investment_type" defaultValue={investmentTypes.includes(type ?? "") ? (type ?? "") : ""}>
                          <option value="">Property Type</option>
                          {investmentTypes.map((t) => (
                            <option value={t} key={t}>
                              {t}
                            </option>
                          ))}
                        </select>
                      </div>

                      <div className="BookingFrom p-0">
                        <select
                          className="form-control"
                          name="community_id"
                          defaultValue={communities.some((c) => String(c.id) === communityId) ? (communityId ?? "") : ""}
                        >
                          <option value="">Community</option>
                          {communities.map((community) => (
                            <option value={community.id} key={community.id}>
                              {community.name}
                            </option>
                          ))}
                        </select>
                      </div>

                      <div className="BookingFromBtn">
                        <button type="submit">
                          <img src="/public/assets/img/search.svg" alt="Search" />
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
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
                    <Link href="/investments">Investments</Link>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CTA STRIP */}
      <section className="CTA-strip space pb-0">
        <div className="container">
          <div className="row" style={{ backgroundImage: "url('/public/assets/images/quadrant-skyline-night.jpg')" }}>
            <div className="col-lg-6">
              <div className="heading-pnel fff m-0">
                <h2 className="m-0">Exclusive Investment Opportunities in Dubai</h2>
                <p>
                  Dubai&apos;s dynamic landscape stands at the crossroads of ambition and innovation, offering a wealth
                  of opportunities for forward-thinking investors and developers. Our curated selection showcases an
                  impressive breadth of high-potential assets — from prime plots and full buildings to exclusive hotels,
                  offices, and entire floors. Each listing is meticulously chosen to reflect the region&apos;s rapid
                  growth, strategic value, and enduring appeal.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* INVESTMENTS LISTING */}
      <section className="space position-relative">
        <div className="container">
          {/* Top Bar */}
          <div className="listing-top-area">
            <div className="row">
              <div className="col-12">
                <div className="listing-top-area-container">
                  <div className="item-counter">
                    <p>
                      Results: <span>{investments.total} Properties</span>
                    </p>
                  </div>
                  <div className="filter-trigger">
                    <FilterOpenLink className="green-btn">
                      <img src="/public/assets/img/filter.svg" alt="Filter" /> Filters
                    </FilterOpenLink>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Investment Cards */}
          <div className="cards-main">
            <div className="row">
              {investments.data.length > 0 ? (
                investments.data.map((investment, i) => (
                  <div className="col-lg-3 col-md-6 col-12" key={investment.id}>
                    <div className="card-box">
                      <figure>
                        {truthy(investment.investment_type) && (
                          <div className="VillaText">
                            <p>{investment.investment_type}</p>
                          </div>
                        )}
                        <Link href={`/investments/${investment.slug}`}>
                          {truthy(investment.main_image) ? (
                            <FallbackImg
                              loading="lazy"
                              src={asset(investment.main_image)}
                              alt={investment.title ?? ""}
                              fallbackSrc="/public/assets/img/placeholder.jpg"
                            />
                          ) : (
                            <img loading="lazy" src="/public/assets/img/placeholder.jpg" alt={investment.title ?? ""} />
                          )}
                        </Link>
                      </figure>

                      <figcaption>
                        <Link href={`/investments/${investment.slug}`}>
                          <h3>{investment.title}</h3>

                          {truthy(investment.community_name) && (
                            <p>
                              {/* LazyLoadImages kept the first 3 images without a loading attribute eager */}
                              <img
                                loading={i === 0 ? undefined : "lazy"}
                                decoding={i === 0 ? undefined : "async"}
                                src="/public/assets/img/hotel/map.svg"
                                alt="Map Icon"
                              />{" "}
                              {investment.community_name}
                            </p>
                          )}

                          {(truthy(investment.property_area_sqft) || truthy(investment.roi_percentage)) && (
                            <div className="HotelViews">
                              <ul>
                                {truthy(investment.property_area_sqft) && (
                                  <li>
                                    <img
                                      loading="lazy"
                                      decoding="async"
                                      src="/public/assets/img/hotel/1.svg"
                                      alt="Area icon"
                                    />{" "}
                                    {numberFormat(investment.property_area_sqft)} SQ FT
                                  </li>
                                )}
                                {truthy(investment.roi_percentage) && (
                                  <li>
                                    <img
                                      loading="lazy"
                                      decoding="async"
                                      src="/public/assets/img/hotel/2.svg"
                                      alt="ROI icon"
                                    />{" "}
                                    {investment.roi_percentage}% ROI
                                  </li>
                                )}
                              </ul>
                            </div>
                          )}

                          {truthy(investment.price) && (
                            <h6>
                              <span>{investment.price_currency ?? "AED"}</span> {numberFormat(investment.price)}/-
                            </h6>
                          )}
                        </Link>
                      </figcaption>
                    </div>
                  </div>
                ))
              ) : (
                <div className="col-12 text-center py-5">
                  <p className="text-muted">No investment opportunities found matching your search.</p>
                  <Link href="/investments" className="green-btn mt-3">
                    Clear Filters
                  </Link>
                </div>
              )}
            </div>
          </div>

          {/* PAGINATION */}
          <Pagination
            currentPage={investments.currentPage}
            lastPage={investments.lastPage}
            path="/investments"
            query={queryString(sp)}
            style={{}}
          />
        </div>
      </section>

      {/* REGISTER INTEREST SECTION */}
      <section className="list-us-sec space bg-grey pt-0">
        <div className="container">
          <div className="row align-items-end">
            <div className="col-lg-6">
              <div className="list-us-content">
                <h2>Let Us Find Your Perfect Investment</h2>
                <p>
                  Transform your aspirations into tangible gains through our bespoke property sourcing service. Entrust
                  us with your investment criteria — from growth targets and geographic preferences to nuanced asset
                  class considerations — and allow our seasoned experts to orchestrate a global search for premier
                  opportunities. With a profound understanding of market dynamics, an eye for hidden gems, and access to
                  exclusive listings, we&apos;re poised to curate a shortlist tailored precisely to your vision. Our
                  commitment extends beyond mere introductions: we provide incisive analysis, strategic guidance, and
                  diligent support at every step.
                </p>
              </div>
            </div>

            <div className="col-lg-6">
              <div className="list-from">
                <h2 className="mb-2">Register your interest!</h2>
                <InterestForm ts={formTimestamp()} source="investments_page" />
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* FILTER SIDEBAR */}
      <form method="GET" action="/investments">
        <div id="filters-sidebar" className="sidenav">
          <FilterCloseLink />

          <div className="filter-head">
            <h2>Filters</h2>
          </div>

          <div className="filter-boxes">
            {/* Sort By */}
            <div className="filter-box">
              <h4>Sort By</h4>
              <div className="filter-checkboxes">
                <ul>
                  <li>
                    <input
                      className="styled-checkbox"
                      id="sort_asc"
                      name="sort"
                      type="radio"
                      value="price_asc"
                      defaultChecked={sort === "price_asc"}
                    />
                    <label htmlFor="sort_asc">Price: Low to High</label>
                  </li>
                  <li>
                    <input
                      className="styled-checkbox"
                      id="sort_desc"
                      name="sort"
                      type="radio"
                      value="price_desc"
                      defaultChecked={sort === "price_desc"}
                    />
                    <label htmlFor="sort_desc">Price: High to Low</label>
                  </li>
                  <li>
                    <input
                      className="styled-checkbox"
                      id="sort_roi"
                      name="sort"
                      type="radio"
                      value="roi_desc"
                      defaultChecked={sort === "roi_desc"}
                    />
                    <label htmlFor="sort_roi">Highest ROI</label>
                  </li>
                </ul>
              </div>
            </div>

            {/* Property Type */}
            <div className="filter-box">
              <h4>Property Type</h4>
              <div className="filter-checkboxes">
                <ul>
                  {investmentTypes.map((t) => (
                    <li key={t}>
                      <input
                        className="styled-checkbox"
                        id={`type_${slugify(t)}`}
                        name="investment_type"
                        type="radio"
                        value={t}
                        defaultChecked={type === t}
                      />
                      <label htmlFor={`type_${slugify(t)}`}>{t}</label>
                    </li>
                  ))}
                </ul>
              </div>
            </div>

            {/* Community */}
            <div className="filter-box">
              <h4>Community</h4>
              <div className="filter-checkboxes">
                <ul>
                  {communities.map((community) => (
                    <li key={community.id}>
                      <input
                        className="styled-checkbox"
                        id={`comm_${community.id}`}
                        name="community_id"
                        type="radio"
                        value={community.id}
                        defaultChecked={communityId === String(community.id)}
                      />
                      <label htmlFor={`comm_${community.id}`}>{community.name}</label>
                    </li>
                  ))}
                </ul>
              </div>
            </div>

            {/* Price Range */}
            <div className="filter-box">
              <h4>Price Range</h4>
              <div className="filter-checkboxes">
                <PriceRange rangeMin={rangeMin} rangeMax={rangeMax} inputMin={minPrice ?? ""} inputMax={maxPrice ?? ""} />
              </div>
            </div>

            {/* Filter Buttons */}
            <div className="filter-box">
              <div className="menu-btn-grup filter-btn-grp p-0">
                <Link className="btn border-btn" href="/investments">
                  Clear Filters
                </Link>
                <button type="submit" className="green-btn">
                  Apply Filters
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <FilterOverlay />

      {/* MOBILE SEARCH MODAL */}
      <SearchModal />
    </>
  );
}
