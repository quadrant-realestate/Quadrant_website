import Link from "@/components/site/Link";
import { notFound } from "next/navigation";
import { cache } from "react";
import { Carousel } from "@/components/site/Carousel";
import { escapeHtml, numberFormat, strLimit, stripTags, ucfirst } from "@/lib/format";
import {
  getPropertyAmenities,
  getPropertyBySlug,
  getPropertyGallery,
  getRelatedProperties,
  type PropertyListItem,
} from "@/lib/queries/properties";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { formTimestamp } from "@/lib/spam";
import { asset, siteUrl } from "@/lib/url";
import { FallbackImg } from "../_components/FallbackImg";
import { decFilled, decString, phpUrlencode, strFilled } from "../_components/php";
import { DescriptionShowMore } from "./_components/DescriptionShowMore";
import { InterestForm } from "./_components/InterestForm";
import { PropertyGallery } from "./_components/PropertyGallery";
import { ShareDropdown } from "./_components/ShareDropdown";
import "./page.css";

// website/properties/show.blade.php — PropertyFrontController@show

type Props = { params: Promise<{ slug: string }> };

const getProperty = cache((slug: string) => getPropertyBySlug(slug));

const LISTING_ROUTES: Record<string, string> = {
  sale: "/properties/for-sale",
  rent: "/properties/for-rent",
  private: "/properties/private-office",
  international: "/properties/international",
};
const LISTING_LABELS: Record<string, string> = {
  sale: "Buy",
  rent: "Rent",
  private: "Private Office",
  international: "International",
};

// No pages are built in advance; each one is cached the first time it is visited.
export function generateStaticParams() {
  return [];
}

export async function generateMetadata({ params }: Props) {
  const { slug } = await params;
  const property = await getProperty(slug);
  if (!property) return {};
  const settings = await getSettings();

  const description =
    strLimit(stripTags(property.description ?? property.short_description), 160) ||
    `Discover ${property.title ?? ""} in ${property.community_name ?? "Dubai"}. ${pick(settings, "site_name") ?? ""} — Luxury Real Estate.`;

  return pageMetadata({
    title: `${property.title ?? ""} — ${pick(settings, "site_name", "Quadrant Properties Dubai") ?? ""}`,
    description,
    path: `/properties/${slug}`,
  });
}

function RelatedCard({ related }: { related: PropertyListItem }) {
  const href = `/properties/${related.slug ?? ""}`;
  const title = related.title ?? "";
  return (
    <div className="item">
      <div className="card-box">
        <figure>
          {strFilled(related.type_name) && (
            <div className="VillaText">
              <p>{related.type_name}</p>
            </div>
          )}
          <Link href={href}>
            {strFilled(related.main_image) ? (
              <FallbackImg loading="lazy" decoding="async" src={asset(related.main_image)} alt={title} />
            ) : (
              <img loading="lazy" src="/public/assets/img/placeholder.jpg" alt={title} />
            )}
          </Link>
        </figure>
        <figcaption>
          <Link href={href}>
            <h3>{related.title}</h3>
            {strFilled(related.community_name) && (
              <span className="address_section">
                <p>
                  <img loading="lazy" decoding="async" src="/public/assets/img/hotel/map.svg" alt="Map" />{" "}
                  {related.community_name}
                </p>
              </span>
            )}
            <div className="HotelViews">
              <ul>
                {decFilled(related.area_sqft) && (
                  <li>
                    <img loading="lazy" decoding="async" src="/public/assets/img/hotel/1.svg" alt="Area" />{" "}
                    {numberFormat(related.area_sqft)} SQ FT
                  </li>
                )}
                {related.bedrooms !== null && (
                  <li>
                    <img loading="lazy" decoding="async" src="/public/assets/img/hotel/2.svg" alt="Beds" />{" "}
                    {related.bedrooms}
                  </li>
                )}
                {related.bathrooms !== null && (
                  <li>
                    <img loading="lazy" decoding="async" src="/public/assets/img/hotel/3.svg" alt="Baths" />{" "}
                    {related.bathrooms}
                  </li>
                )}
              </ul>
            </div>
            {decFilled(related.price_on_request) ? (
              <h6>
                <span>Price on Request</span>
              </h6>
            ) : decFilled(related.price) ? (
              <h6>
                <span>{related.price_currency ?? "AED"}</span> {numberFormat(related.price)}/-
              </h6>
            ) : null}
          </Link>
        </figcaption>
      </div>
    </div>
  );
}

function ModalSearchForm({ listingType }: { listingType: string }) {
  return (
    <form action="/properties/search" method="GET">
      <input type="hidden" name="listing_type" value={listingType} />
      <div className="BookingBox">
        <div className="BookingLocation">
          <div className="BookingFrom">
            <input type="text" name="search" className="form-control" placeholder="Search..." />
          </div>
          <div className="BookingFrom p-0">
            <select className="form-control" name="bedrooms">
              <option value="">Bedrooms</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5+</option>
            </select>
          </div>
          <div className="BookingFromBtn">
            <button type="submit">
              <img width={17} height={17} loading="lazy" decoding="async" src="/public/assets/img/search.svg" alt="Search" />{" "}
              Search
            </button>
          </div>
        </div>
      </div>
    </form>
  );
}

export default async function PropertyShowPage({ params }: Props) {
  const { slug } = await params;
  const property = await getProperty(slug);
  if (!property) notFound();

  const [gallery, amenities, relatedProperties, settings] = await Promise.all([
    getPropertyGallery(property.id),
    getPropertyAmenities(property.id),
    getRelatedProperties(property),
    getSettings(),
  ]);

  const listingRoute = LISTING_ROUTES[property.listing_type ?? ""] ?? "/properties/for-sale";
  const listingLabel = LISTING_LABELS[property.listing_type ?? ""] ?? "Buy";

  const pageUrl = siteUrl(`/properties/${slug}`);
  const title = property.title ?? "";
  const shareLinks = {
    facebook: `https://www.facebook.com/sharer/sharer.php?u=${phpUrlencode(pageUrl)}`,
    twitter: `https://twitter.com/intent/tweet?url=${phpUrlencode(pageUrl)}&text=${phpUrlencode(title)}`,
    linkedin: `https://www.linkedin.com/shareArticle?mini=true&url=${phpUrlencode(pageUrl)}`,
    whatsapp: `https://wa.me/?text=${phpUrlencode(`${title} ${pageUrl}`)}`,
  };

  // Number of <a> links inside #aniimated-thumbnials ("See more" overlay when > 5).
  const galleryLinks = (strFilled(property.main_image) ? 1 : 0) + gallery.length;

  const currency = property.price_currency ?? "AED";
  const whatsapp = pick(settings, "whatsapp_number");
  const phone = pick(settings, "contact_phone");

  const hasDetails =
    decFilled(property.plot_area_sqft) ||
    strFilled(property.parking_spaces) ||
    strFilled(property.furnished) ||
    strFilled(property.floor_number) ||
    decFilled(property.total_floors) ||
    strFilled(property.service_charge);

  return (
    <>
      <div>
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
                      <Link href={listingRoute}>{listingLabel}</Link>
                    </li>
                    <li>
                      <span>{property.title}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* GALLERY GRID */}
        <section className="property-gallery-sec space pb-0">
          <div className="container">
            <div className="row">
              <div className="col-12">
                <div className="gallery-topbar">
                  <div className="topbar-left">
                    {strFilled(property.type_name) && <p className="category-label">{property.type_name}</p>}
                  </div>

                  <div className="topbar-right">
                    <div className="share-blog mb-0">
                      <ShareDropdown links={shareLinks} />
                    </div>
                  </div>
                </div>
              </div>

              <div className="col-12">
                <PropertyGallery
                  title={title}
                  mainImage={strFilled(property.main_image) ? asset(property.main_image) : null}
                  images={gallery.map((image) => ({ id: image.id, src: asset(image.image_path) }))}
                />

                {/* "See more" overlay on the 5th gallery image */}
                {galleryLinks > 5 && (
                  <style>{`
            #gallery-item-5:after {
                content: "See more";
                display: flex;
                width: 100%;
                height: 100%;
                background-color: rgba(58, 53, 38, 0.84);
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                font-size: 14px;
                letter-spacing: 1px;
                color: #FCF9F2;
                align-items: center;
                justify-content: center;
            }
        `}</style>
                )}
              </div>
            </div>
          </div>
        </section>

        {/* MAIN CONTENT + SIDEBAR */}
        <section className="space blog-detail-page">
          <div className="container">
            <div className="row">
              {/* LEFT: Main Content */}
              <div className="col-lg-8">
                <div className="content-wrapper">
                  <h1 className="mt-0">{property.title}</h1>
                  {(strFilled(property.community_name) || strFilled(property.address)) && (
                    <p>
                      <img loading="lazy" decoding="async" src="/public/assets/img/hotel/map.svg" alt="Location" />{" "}
                      &nbsp; {property.community_name ?? property.address}
                    </p>
                  )}

                  {/* Price + Key Metrics */}
                  <div className="price-amenitity">
                    {decFilled(property.price_on_request) ? (
                      <h3>Price on Request</h3>
                    ) : decFilled(property.price) ? (
                      <h3>
                        {currency} {numberFormat(property.price)}/-
                      </h3>
                    ) : null}

                    <div className="main-amenity">
                      {decFilled(property.area_sqft) && (
                        <div className="amenity-box">
                          <div className="amenty-img">
                            <img loading="lazy" decoding="async" src="/public/assets/img/square.svg" alt="Area" />
                          </div>
                          <p>{numberFormat(property.area_sqft)} SQ FT</p>
                        </div>
                      )}

                      {property.bedrooms !== null && (
                        <div className="amenity-box">
                          <div className="amenty-img">
                            <img loading="lazy" decoding="async" src="/public/assets/img/bed.svg" alt="Bedrooms" />
                          </div>
                          <p>{property.bedrooms} Beds</p>
                        </div>
                      )}

                      {property.bathrooms !== null && (
                        <div className="amenity-box">
                          <div className="amenty-img">
                            <img loading="lazy" decoding="async" src="/public/assets/img/bathtub.svg" alt="Bathrooms" />
                          </div>
                          <p>{property.bathrooms} Bathrooms</p>
                        </div>
                      )}
                    </div>
                  </div>

                  {/* Mobile Register Interest CTA */}
                  <a className="green-btn desktop-none mb-4" href="#interest">
                    Register your Interest
                  </a>

                  <div className="seperator" />

                  {/* Description */}
                  {(strFilled(property.description) || strFilled(property.short_description)) && (
                    <>
                      <h5>Description</h5>
                      <DescriptionShowMore
                        html={
                          strFilled(property.description)
                            ? property.description
                            : `<p>${escapeHtml(property.short_description ?? "")}</p>`
                        }
                      />
                      <div className="seperator" />
                    </>
                  )}

                  {/* Additional Property Details */}
                  {hasDetails && (
                    <>
                      <div className="pay-plans">
                        <h5>Property Details</h5>
                        <div className="plan-items">
                          {decFilled(property.plot_area_sqft) && (
                            <div className="plan-item">
                              <h4>
                                <span>Plot Area</span> {numberFormat(property.plot_area_sqft)} <span>SQ FT</span>
                              </h4>
                            </div>
                          )}
                          {strFilled(property.parking_spaces) && (
                            <div className="plan-item">
                              <h4>
                                <span>Parking</span> {property.parking_spaces} <span>Spaces</span>
                              </h4>
                            </div>
                          )}
                          {strFilled(property.furnished) && (
                            <div className="plan-item">
                              <h4>
                                <span>Furnishing</span> {ucfirst(property.furnished.replace(/_/g, " "))}
                              </h4>
                            </div>
                          )}
                          {strFilled(property.floor_number) && (
                            <div className="plan-item">
                              <h4>
                                <span>Floor</span> {property.floor_number}{" "}
                                {decFilled(property.total_floors) && <span>of {decString(property.total_floors)}</span>}
                              </h4>
                            </div>
                          )}
                          {strFilled(property.service_charge) && (
                            <div className="plan-item">
                              <h4>
                                <span>Service Charge</span> {currency} {numberFormat(property.service_charge)}{" "}
                                <span>Per Year</span>
                              </h4>
                            </div>
                          )}
                        </div>
                      </div>
                      <div className="seperator" />
                    </>
                  )}

                  {/* Amenities */}
                  {amenities.length > 0 && (
                    <>
                      <div className="ameneties-panel">
                        <h5>Amenities</h5>
                        <div className="ameneties-items">
                          {amenities.map((amenity) => (
                            <div className="ameneties-item" key={amenity.id}>
                              <img loading="lazy" decoding="async" src="/public/assets/img/check.svg" alt="" />
                              <p>{amenity.name}</p>
                            </div>
                          ))}
                        </div>
                      </div>
                      <div className="seperator" />
                    </>
                  )}

                  {/* Floor Plan / Brochure */}
                  {(strFilled(property.floor_plan_image) || strFilled(property.brochure_pdf)) && (
                    <>
                      <div className="card floor-card-custom">
                        <div className="card-header">
                          <h5>Floor Plan</h5>
                        </div>
                        <div className="card-body">
                          {strFilled(property.floor_plan_image) && (
                            <div className="floor-img">
                              <img
                                loading="lazy"
                                decoding="async"
                                src={asset(property.floor_plan_image)}
                                className="img-fluid"
                                alt={`${title} Floor Plan`}
                              />
                            </div>
                          )}
                          {strFilled(property.brochure_pdf) && (
                            <div className="mt-3">
                              <a href={asset(property.brochure_pdf)} target="_blank" className="light-btn">
                                <img loading="lazy" decoding="async" src="/public/assets/img/download-dark.svg" alt="" />{" "}
                                Download Brochure
                              </a>
                            </div>
                          )}
                        </div>
                      </div>
                      <div className="seperator" />
                    </>
                  )}

                  {/* Video / Virtual Tour */}
                  {(strFilled(property.video_url) || strFilled(property.virtual_tour_url)) && (
                    <>
                      <div className="ameneties-panel">
                        <h5>Media</h5>
                        <div className="d-flex gap-3 mt-3 flex-wrap">
                          {strFilled(property.video_url) && (
                            <a href={property.video_url} target="_blank" className="light-btn">
                              Watch Video
                            </a>
                          )}
                          {strFilled(property.virtual_tour_url) && (
                            <a href={property.virtual_tour_url} target="_blank" className="light-btn">
                              Virtual Tour
                            </a>
                          )}
                        </div>
                      </div>
                      <div className="seperator" />
                    </>
                  )}

                  {/* Location Map */}
                  {decFilled(property.latitude) && decFilled(property.longitude) ? (
                    <div className="property-location">
                      <h5>Property Location</h5>
                      {property.community_name ?? property.city ?? "Dubai"}
                      <iframe
                        width="100%"
                        height="450"
                        frameBorder="0"
                        style={{ border: 0, marginTop: 15 }}
                        src={`https://www.google.com/maps?q=${decString(property.latitude)},${decString(property.longitude)}&hl=en&z=14&output=embed`}
                        allowFullScreen
                      />
                    </div>
                  ) : strFilled(property.google_maps_link) ? (
                    <div className="property-location">
                      <h5>Property Location</h5>
                      {property.community_name ?? property.city ?? "Dubai"}
                      <div className="mt-3">
                        <a href={property.google_maps_link} target="_blank" className="light-btn">
                          View on Google Maps
                        </a>
                      </div>
                    </div>
                  ) : strFilled(property.community_name) || strFilled(property.city) ? (
                    <div className="property-location">
                      <h5>Property Location</h5>
                      {property.community_name ?? property.city}
                    </div>
                  ) : null}
                </div>
              </div>

              {/* RIGHT: Sidebar */}
              <div className="col-lg-4">
                <div className="new-p-detail-right" id="interest">
                  <InterestForm
                    ts={formTimestamp()}
                    propertyId={property.id}
                    defaultNotes={`Inquiry about: ${title} (${property.reference_no ?? ""})`}
                  />

                  {/* Contact Buttons */}
                  <div className="agent-panel">
                    <div className="agent-contact-btn">
                      {strFilled(whatsapp) && (
                        <a href={`https://wa.me/${whatsapp.replace(/[^0-9]/g, "")}`} target="_blank" className="light-btn">
                          <img loading="lazy" src="/public/assets/img/whatsapp-dark.png" alt="WhatsApp" />
                          {" "}Whatsapp
                        </a>
                      )}
                      {strFilled(phone) && (
                        <a href={`tel:${phone}`} className="light-btn">
                          <img loading="lazy" src="/public/assets/img/call-dark.png" alt="Call" />
                          {" "}Call Us Now!
                        </a>
                      )}
                    </div>
                  </div>

                  {/* Download Brochure */}
                  {strFilled(property.brochure_pdf) && (
                    <div className="download-btn-grp">
                      <a href={asset(property.brochure_pdf)} target="_blank" className="green-btn dark w-100">
                        <img loading="lazy" src="/public/assets/img/download-light.svg" alt="" />
                        {" "}Download the Brochure
                      </a>
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* RELATED PROPERTIES */}
        {relatedProperties.length > 0 && (
          <section className="space position-relative pt-0">
            <div className="container">
              <div className="heading-pnel HeadingMiddleBorder">
                <div className="row">
                  <div className="col-lg-8 col-12">
                    <h2 className="m-0">More Properties</h2>
                  </div>
                  <div className="col-lg-4 col-12" />
                </div>
              </div>

              <div className="row">
                <div className="col-12">
                  <div className="cards-main">
                    <Carousel
                      id="related-slider"
                      loop
                      margin={20}
                      nav
                      dots={false}
                      responsive={{ 0: 1, 768: 2, 1024: 3 }}
                    >
                      {relatedProperties.map((related) => (
                        <RelatedCard related={related} key={related.id} />
                      ))}
                    </Carousel>
                  </div>
                </div>
              </div>
            </div>
          </section>
        )}
      </div>

      {/* MOBILE SEARCH MODAL (Bootstrap .modal, hidden — nothing on the page opens it) */}
      <div className="modal fade" id="search-modal" tabIndex={-1} role="dialog" aria-hidden="true">
        <div className="modal-dialog modal-dialog-centered" role="document">
          <div className="modal-content">
            <div className="modal-body">
              <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <div className="search-container">
                <div className="TopTabsBar">
                  <ul className="nav nav-tabs" role="tablist">
                    <li className="nav-item">
                      <a className="nav-link active" data-toggle="tab" href="#Buy-two" role="tab">
                        Buy
                      </a>
                    </li>
                    <li className="nav-item">
                      <a className="nav-link" data-toggle="tab" href="#Rent-two" role="tab">
                        Rent
                      </a>
                    </li>
                  </ul>
                </div>
                <div className="tab-content">
                  <div id="Buy-two" className="tab-pane fade show active" role="tabpanel">
                    <ModalSearchForm listingType="sale" />
                  </div>
                  <div id="Rent-two" className="tab-pane fade" role="tabpanel">
                    <ModalSearchForm listingType="rent" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
