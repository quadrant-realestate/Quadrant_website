import Link from "@/components/site/Link";
import { FallbackImg } from "../investments/_components/FallbackImg";
import { FilterCloseLink, FilterOpenLink, FilterOverlay } from "../investments/_components/FilterNav";
import { SearchModal } from "../investments/_components/SearchModal";
import { pageNumber, param, queryString, truthy, type SearchParams } from "../investments/_components/helpers";
import { Pagination } from "@/components/site/Pagination";
import { numberFormat } from "@/lib/format";
import { getResidences } from "@/lib/queries/branded-residences";
import { getActiveCommunities } from "@/lib/queries/investments";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { asset } from "@/lib/url";
import "./page.css";

type Props = { searchParams: Promise<SearchParams> };

const PLACEHOLDER = "/public/assets/img/thumbnail-placeholder-gallery.png";

function load(sp: SearchParams) {
  // Laravel only applies a filter when the value is truthy ("0" is skipped).
  const search = param(sp, "search");
  const communityId = param(sp, "community_id");
  return getResidences(truthy(search) ? search : null, truthy(communityId) ? communityId : null, pageNumber(sp));
}

export async function generateMetadata({ searchParams }: Props) {
  const sp = await searchParams;
  const settings = await getSettings();
  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai") ?? "";
  const residences = await load(sp);
  return pageMetadata({
    title: `Top Branded Residences in Dubai — ${siteName}`,
    description: `Discover the top branded residences in Dubai with ${siteName}. Luxury homes built in collaboration with world-renowned global brands.`,
    path: "/branded-residences",
    noindex: residences.total === 0,
  });
}

export default async function BrandedResidencesPage({ searchParams }: Props) {
  const sp = await searchParams;
  const [residences, communities, settings] = await Promise.all([load(sp), getActiveCommunities(), getSettings()]);
  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai");
  const search = param(sp, "search");
  const communityId = param(sp, "community_id");

  return (
    <>
      {/* INNER BANNER */}
      <section
        className="banner inr-banner"
        style={{ backgroundImage: "url('/public/assets/images/image_175377051490.png')" }}
      >
        <div className="container">
          <div className="slider-info">
            <div className="BannerBox">
              <div className="banner-heading text-center">
                <h1>Top Branded Residences in Dubai</h1>
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
                    <span>Branded Residences in Dubai</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* AT A GLANCE / CTA STRIP */}
      <section className="CTA-strip space">
        <div className="container">
          <div className="row" style={{ backgroundImage: "url('/public/assets/images/image_173434756190.jpg')" }}>
            <div className="col-lg-6">
              <div className="heading-pnel fff m-0">
                <h2 className="m-0">At a Glance</h2>
                <p>
                  In 2010, the launch of branded residences in the iconic Burj Khalifa marked a turning point in
                  Dubai&apos;s luxury property scene. As the first of its kind, this development introduced the concept
                  of branded residences in Dubai to a global audience. Since then, demand for these prestigious
                  properties has surged, attracting discerning investors, affluent homeowners, and some of the
                  world&apos;s most renowned hospitality and lifestyle brands.
                </p>
                <Link href="/contact" className="light-btn">
                  Read more
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* WHAT THEY ARE & EXAMPLES */}
      <section className="about-sec after-none space bg-grey">
        <div className="container">
          <div className="heading-pnel text-center">
            <h2>Branded Residences in Dubai: What They Are and Examples</h2>
          </div>
          <div className="row align-items-center">
            <div className="col-lg-6">
              <div className="about-left">
                <div className="about-img about-img-after">
                  <div className="img-item">
                    <img
                      src="/public/assets/images/image_173434713991.jpg"
                      alt="Best branded residences for sale in Dubai"
                      className="w-100"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div className="col-lg-6">
              <div className="about-content">
                <p>
                  The market for branded homes in Dubai has expanded significantly over the past ten years. In addition
                  to increasing the value of the city&apos;s real estate, this expansion has created a new source of
                  income for developers and global brands. By building a prosperous, branded living ecosystem that
                  benefits all parties — developers, investors, and residents — {siteName} aims to influence the future
                  of real estate as leaders in this industry.
                </p>
                <p>
                  What makes these properties interesting? Branded residences in Dubai are high-end luxury homes built
                  in collaboration with well-known global brands. They offer all of these things under one roof:
                  high-end design, premium services, and a better way of life. Buyers can enjoy the comfort and privacy
                  of a private home while also getting personalized services and high-end amenities that are typically
                  only found in five-star hotels, such as concierge service, private pools, spas, and fitness centers.
                </p>
                <p>
                  These changes are the result of strategic partnerships. A brand gives a developer a license to use
                  its name and standards, and then the developer builds, designs, and sells homes under that brand. The
                  developer pays royalties and design fees, while the buyers pay for regular services and management.
                  In return, they get a way of life and an investment that is backed by brand integrity, consistency,
                  and global appeal.
                </p>
                <p>
                  This partnership means more than just prestige for its residents. A trusted brand&apos;s involvement
                  makes sure that everything, from the quality of the construction to the delivery of the service, is
                  up to standard. This gives buyers more confidence and helps keep property values high, even when the
                  market is fluctuating.
                </p>
              </div>
            </div>
          </div>
        </div>
        <br />
        <br />
      </section>

      {/* TRIPLE WIN FORMULA */}
      <section className="formula-sec space">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-lg-6">
              <div className="formula-content">
                <p>Branded Residences offer a special kind of synergy. What our experts refer to as a Triple Win Formula:</p>
                <ul>
                  <li>
                    For developers, branded projects provide a competitive advantage and access to a larger, more
                    devoted customer base. Developers can command premium pricing thanks to customized design and
                    marketing strategies, which also increase visibility and demand.
                  </li>
                  <li>
                    For brands, through licensing agreements, these properties open up a profitable revenue stream.
                    They increase their presence in fast-growing real estate markets like Dubai while strengthening
                    brand loyalty.
                  </li>
                  <li>
                    For customers, the advantage is clear: premium interiors, reliable property management, and access
                    to top-notch amenities. Branded residences for sale have great investment potential due to their
                    higher rental yields, greater capital appreciation, and flexibility for short-term rentals.
                  </li>
                </ul>
                <p>
                  <br />
                  The trust that comes with a well-known worldwide brand is one of the main reasons branded residences
                  are still gaining popularity. Brand familiarity provides comfort in a global market like Dubai, where
                  consumers frequently come from various nations and backgrounds.
                </p>
              </div>
            </div>
            <div className="col-lg-6">
              <div className="formula-boxes d-flex">
                <div>
                  <div className="formula-box">
                    <figure>
                      <img
                        src="/public/assets/images/image_173642082695.png"
                        alt="Developer"
                        className=""
                      />
                    </figure>
                    <figcaption>
                      <h3>Developer</h3>
                    </figcaption>
                  </div>
                  <div className="formula-box">
                    <figure>
                      <img
                        src="/public/assets/images/image_173642082696.png"
                        alt="Brand"
                        className=""
                      />
                    </figure>
                    <figcaption>
                      <h3>Brand</h3>
                    </figcaption>
                  </div>
                </div>
                <div className="formula-box center-box1">
                  <figure>
                    <img
                      loading="lazy"
                      decoding="async"
                      src="/public/assets/images/image_173642082697.png"
                      alt="Client"
                      className=""
                    />
                  </figure>
                  <figcaption>
                    <h3>Client</h3>
                  </figcaption>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* TYPES OF BRANDED RESIDENCES */}
      <section className="residences-type-sec after-none">
        <div className="container">
          <div className="heading-pnel HeadingMiddleBorder">
            <div className="row">
              <div className="col-12">
                <h2 className="m-0">Types of Branded Residences</h2>
              </div>
            </div>
          </div>
          <div className="row">
            {[
              ["image_17325276200.png", "Residential Units within a Hotel"],
              ["image_17325276201.png", "Residential Development Adjacent to a Hotel"],
              ["image_17325276202.png", "Residential Development with Hotel Management"],
              ["image_17325276203.png", "Stand-Alone Residential Development with Brand Association"],
            ].map(([file, label]) => (
              <div className="col-lg-3 col-md-6 col-12" key={file}>
                <div className="resid-type-box">
                  <div className="type-img">
                    <img loading="lazy" decoding="async" src={`/public/assets/images/${file}`} alt={label} />
                  </div>
                  <div className="type-content">
                    <h3>{label}</h3>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* FEATURED BRANDED RESIDENCES GRID */}
      <section className="space position-relative logo-icon">
        <div className="container">
          <div className="heading-pnel HeadingMiddleBorder">
            <div className="row">
              <div className="col-lg-8 col-12">
                <h2 className="m-0">Featured Branded Residences in Dubai</h2>
              </div>
              <div className="col-lg-4 col-12">
                <div className="head-btn">
                  <FilterOpenLink className="green-btn ml-auto">
                    <img loading="lazy" decoding="async" src="/public/assets/img/filter.svg" alt="Filters" />{" "}
                    Filters
                  </FilterOpenLink>
                </div>
              </div>
            </div>
          </div>

          <div className="cards-main">
            <div className="row">
              {residences.data.length > 0 ? (
                residences.data.map((residence) => (
                  <div className="col-lg-3 col-md-6 col-12" key={residence.id}>
                    <div className="card-box">
                      <figure>
                        <div className="VillaText">
                          <p>{residence.brand_name ?? "Branded Residence"}</p>
                        </div>
                        <Link href={`/branded-residences/${residence.slug}`}>
                          {truthy(residence.main_image) ? (
                            <FallbackImg
                              loading="lazy"
                              decoding="async"
                              src={asset(residence.main_image)}
                              fallbackSrc={PLACEHOLDER}
                              alt={residence.title ?? ""}
                            />
                          ) : (
                            <img loading="lazy" decoding="async" src={PLACEHOLDER} alt={residence.title ?? ""} />
                          )}
                        </Link>
                      </figure>

                      <figcaption>
                        <Link href={`/branded-residences/${residence.slug}`}>
                          <h3>{residence.title}</h3>
                          {truthy(residence.community_name) && (
                            <p>
                              <img loading="lazy" decoding="async" src="/public/assets/img/hotel/map.svg" alt="Map" />{" "}
                              {residence.community_name}
                            </p>
                          )}
                          {truthy(residence.price_from) && (
                            <h6>
                              <span>From AED</span> {numberFormat(residence.price_from)}/-
                            </h6>
                          )}
                        </Link>
                      </figcaption>
                    </div>
                  </div>
                ))
              ) : (
                <div className="col-12 text-center py-5">
                  <p className="text-muted">No branded residences found.</p>
                  <Link href="/branded-residences" className="green-btn mt-3">
                    Clear Filters
                  </Link>
                </div>
              )}
            </div>
          </div>

          {/* PAGINATION */}
          <Pagination
            currentPage={residences.currentPage}
            lastPage={residences.lastPage}
            path="/branded-residences"
            query={queryString(sp)}
            style={{}}
          />
        </div>
      </section>

      {/* FILTER SIDEBAR */}
      <form method="GET" action="/branded-residences">
        <div id="filters-sidebar" className="sidenav">
          <FilterCloseLink />

          <div className="filter-head">
            <h2>Filters</h2>
          </div>

          <div className="filter-boxes">
            {/* Search */}
            <div className="filter-box">
              <h4>Search</h4>
              <div className="filter-checkboxes">
                <input
                  type="text"
                  name="search"
                  className="form-control"
                  placeholder="Brand or community..."
                  defaultValue={search ?? ""}
                />
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

            {/* Filter Buttons */}
            <div className="filter-box">
              <div className="menu-btn-grup filter-btn-grp p-0">
                <Link className="btn border-btn" href="/branded-residences">
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
