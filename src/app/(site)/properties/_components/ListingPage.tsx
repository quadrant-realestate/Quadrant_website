import Link from "@/components/site/Link";
import { Pagination } from "@/components/site/Pagination";
import { numberFormat } from "@/lib/format";
import {
  param,
  type Community,
  type PropertyListItem,
  type PropertyType,
  type SearchParams,
} from "@/lib/queries/properties";
import type { Paginated } from "@/lib/db";
import { asset } from "@/lib/url";
import { FallbackImg } from "./FallbackImg";
import { FilterClose, FilterOverlay, FilterTrigger, PriceRange } from "./FilterControls";
import { SearchModal } from "./SearchModal";
import { decFilled, looseEq, strFilled } from "./php";
import "./listing.css";

/** Everything that differs between sale.blade.php / rent / private / international / search. */
export type ListingConfig = {
  /** Path of this listing page (filter form action, pagination links). */
  path: string;
  heading: string;
  bannerImage: string;
  /** hidden listing_type of the banner search form */
  bannerListingType: string;
  searchPlaceholder: string;
  breadcrumb: { href: string; label: string };
  cta: { image: string; heading: string; text: string };
  emptyText: string;
  /** "Clear Filters" link under the empty state and in the sidebar */
  clearHref: string;
  /** Price range slider: max + step */
  priceMax: number;
  priceStep: number;
  /** alt texts of the card icons */
  alts: { map: string; area: string; bed: string; bath: string };
  modal: { active: "buy" | "rent"; buyListingType: string | null };
};

const BEDS = ["1", "2", "3", "4", "5", "6", "7"];

function PropertyCard({ property, alts }: { property: PropertyListItem; alts: ListingConfig["alts"] }) {
  const href = `/properties/${property.slug ?? ""}`;
  const title = property.title ?? "";
  return (
    <div className="col-lg-3 col-md-6 col-12">
      <div className="card-box">
        <figure>
          {strFilled(property.type_name) && (
            <div className="VillaText">
              <p>{property.type_name}</p>
            </div>
          )}

          <Link href={href}>
            {strFilled(property.main_image) ? (
              <FallbackImg loading="lazy" src={asset(property.main_image)} alt={title} />
            ) : (
              <img loading="lazy" src="/public/assets/img/placeholder.jpg" alt={title} />
            )}
          </Link>
        </figure>

        <figcaption>
          <Link href={href}>
            <h3>{property.title}</h3>

            {strFilled(property.community_name) && (
              <p>
                <img loading="lazy" decoding="async" src="/public/assets/img/hotel/map.svg" alt={alts.map} />{" "}
                {property.community_name}
              </p>
            )}

            <div className="HotelViews">
              <ul>
                {decFilled(property.area_sqft) && (
                  <li>
                    <img loading="lazy" decoding="async" src="/public/assets/img/hotel/1.svg" alt={alts.area} />{" "}
                    {numberFormat(property.area_sqft)} SQ FT
                  </li>
                )}
                {property.bedrooms !== null && (
                  <li>
                    <img loading="lazy" decoding="async" src="/public/assets/img/hotel/2.svg" alt={alts.bed} />{" "}
                    {property.bedrooms}
                  </li>
                )}
                {property.bathrooms !== null && (
                  <li>
                    <img loading="lazy" decoding="async" src="/public/assets/img/hotel/3.svg" alt={alts.bath} />{" "}
                    {property.bathrooms}
                  </li>
                )}
              </ul>
            </div>

            {decFilled(property.price_on_request) ? (
              <h6>
                <span>Price on Request</span>
              </h6>
            ) : (
              <h6>
                <span>{property.price_currency}</span> {numberFormat(property.price)}/-
              </h6>
            )}
          </Link>
        </figcaption>
      </div>
    </div>
  );
}

export function ListingPage({
  config,
  properties,
  propertyTypes,
  communities,
  searchParams,
}: {
  config: ListingConfig;
  properties: Paginated<PropertyListItem>;
  propertyTypes: PropertyType[];
  communities: Community[];
  searchParams: SearchParams;
}) {
  const req = (key: string) => param(searchParams, key);
  // request('min_price', 0): the default applies only when the key is missing
  const minPrice = req("min_price");
  const maxPrice = req("max_price");

  return (
    <>
      {/* INNER BANNER WITH SEARCH FORM */}
      <section className="banner inr-banner" style={{ backgroundImage: `url('${config.bannerImage}')` }}>
        <div className="container">
          <div className="slider-info">
            <div className="BannerBox">
              <div className="banner-heading text-center">
                <h1>{config.heading}</h1>
              </div>

              <div className="banner-form mobile-none">
                <form action="/properties/search" method="GET">
                  <input type="hidden" name="listing_type" value={config.bannerListingType} />
                  <div className="BookingBox">
                    <div className="BookingLocation">
                      <div className="BookingFrom">
                        <input
                          type="text"
                          name="search"
                          className="form-control"
                          placeholder={config.searchPlaceholder}
                          defaultValue={req("search") ?? ""}
                        />
                      </div>

                      <div className="BookingFrom p-0">
                        <select
                          className="form-control"
                          name="property_type_id"
                          defaultValue={String(propertyTypes.find((t) => looseEq(req("property_type_id"), t.id))?.id ?? "")}
                        >
                          <option value="">Property Type</option>
                          {propertyTypes.map((type) => (
                            <option value={type.id} key={type.id}>
                              {type.name}
                            </option>
                          ))}
                        </select>
                      </div>

                      <div className="BookingFrom p-0">
                        <select
                          className="form-control"
                          name="bedrooms"
                          defaultValue={BEDS.find((b) => looseEq(req("bedrooms"), b)) ?? ""}
                        >
                          <option value="">Bedrooms</option>
                          {BEDS.map((b) => (
                            <option value={b} key={b}>
                              {b === "7" ? "7+" : b}
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
                    <Link href={config.breadcrumb.href}>{config.breadcrumb.label}</Link>
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
          <div className="row" style={{ backgroundImage: `url('${config.cta.image}')` }}>
            <div className="col-lg-6">
              <div className="heading-pnel fff m-0">
                <h2 className="m-0">{config.cta.heading}</h2>
                <p>{config.cta.text}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* PROPERTIES LISTING */}
      <section className="space position-relative">
        <div className="container">
          <div className="listing-top-area">
            <div className="row">
              <div className="col-12">
                <div className="listing-top-area-container">
                  <div className="item-counter">
                    <p>
                      Results: <span>{properties.total} Properties</span>
                    </p>
                  </div>
                  <div className="filter-trigger">
                    <FilterTrigger />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div className="cards-main">
            <div className="row">
              {properties.data.length > 0 ? (
                properties.data.map((property) => (
                  <PropertyCard property={property} alts={config.alts} key={property.id} />
                ))
              ) : (
                <div className="col-12 text-center py-5">
                  <p className="text-muted">{config.emptyText}</p>
                  <Link href={config.clearHref} className="green-btn mt-3">
                    Clear Filters
                  </Link>
                </div>
              )}
            </div>
          </div>

          {/* PAGINATION */}
          <Pagination
            currentPage={properties.currentPage}
            lastPage={properties.lastPage}
            path={config.path}
            query={searchParams}
            style={{}}
          />
        </div>
      </section>

      {/* FILTER SIDEBAR (offcanvas) */}
      <form method="GET" action={config.path}>
        <div id="filters-sidebar" className="sidenav">
          <FilterClose />

          <div className="filter-head">
            <h2>Filters</h2>
          </div>

          <div className="filter-boxes">
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
                      defaultChecked={req("sort") === "price_asc"}
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
                      defaultChecked={req("sort") === "price_desc"}
                    />
                    <label htmlFor="sort_desc">Price: High to Low</label>
                  </li>
                </ul>
              </div>
            </div>

            <div className="filter-box">
              <h4>Property Type</h4>
              <div className="filter-checkboxes">
                <ul>
                  {propertyTypes.map((type) => (
                    <li key={type.id}>
                      <input
                        className="styled-checkbox"
                        id={`type_${type.id}`}
                        name="property_type_id"
                        type="radio"
                        value={type.id}
                        defaultChecked={looseEq(req("property_type_id"), type.id)}
                      />
                      <label htmlFor={`type_${type.id}`}>{type.name}</label>
                    </li>
                  ))}
                </ul>
              </div>
            </div>

            <div className="filter-box">
              <h4>Bedrooms</h4>
              <div className="filter-checkboxes">
                <ul>
                  {BEDS.map((bed) => (
                    <li key={bed}>
                      <input
                        className="styled-checkbox"
                        id={`bed_${bed}`}
                        name="bedrooms"
                        type="radio"
                        value={bed}
                        defaultChecked={looseEq(req("bedrooms"), bed)}
                      />
                      <label htmlFor={`bed_${bed}`}>{bed === "7" ? "7+" : bed}</label>
                    </li>
                  ))}
                </ul>
              </div>
            </div>

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
                        defaultChecked={looseEq(req("community_id"), community.id)}
                      />
                      <label htmlFor={`comm_${community.id}`}>{community.name}</label>
                    </li>
                  ))}
                </ul>
              </div>
            </div>

            <div className="filter-box">
              <h4>Price Range</h4>
              <div className="filter-checkboxes">
                <div className="d-flex">
                  <PriceRange
                    max={config.priceMax}
                    step={config.priceStep}
                    rangeMin={minPrice ?? "0"}
                    rangeMax={maxPrice ?? String(config.priceMax)}
                    minPrice={minPrice ?? ""}
                    maxPrice={maxPrice ?? ""}
                  />
                </div>
              </div>
            </div>

            <div className="filter-box">
              <div className="menu-btn-grup filter-btn-grp p-0">
                <Link className="btn border-btn" href={config.clearHref}>
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

      {/* SEARCH MODAL (Mobile) */}
      <SearchModal propertyTypes={propertyTypes} active={config.modal.active} buyListingType={config.modal.buyListingType} />
    </>
  );
}
