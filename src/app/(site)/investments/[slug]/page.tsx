import Link from "@/components/site/Link";
import { notFound } from "next/navigation";
import { DescriptionShowMore } from "../_components/DescriptionShowMore";
import { FallbackImg } from "../_components/FallbackImg";
import { InterestForm } from "../_components/InterestForm";
import { SearchModal } from "../_components/SearchModal";
import { ShareDropdown } from "../_components/ShareDropdown";
import { truthy } from "../_components/helpers";
import { Carousel } from "@/components/site/Carousel";
import { numberFormat, strLimit, stripTags } from "@/lib/format";
import { getInvestment, getRelatedInvestments } from "@/lib/queries/investments";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { formTimestamp } from "@/lib/spam";
import { asset, siteUrl } from "@/lib/url";
import "./page.css";

type Props = { params: Promise<{ slug: string }> };

const PLACEHOLDER = "/public/assets/img/placeholder.jpg";

// No pages are built in advance; each one is cached the first time it is visited.
export function generateStaticParams() {
  return [];
}

export async function generateMetadata({ params }: Props) {
  const { slug } = await params;
  const investment = await getInvestment(slug);
  if (!investment) return {};
  const settings = await getSettings();
  const description =
    strLimit(stripTags(investment.description), 160) ||
    `Discover ${investment.title ?? ""} in ${investment.community_name ?? "Dubai"}. ${pick(settings, "site_name") ?? ""} — Luxury Real Estate Investment.`;
  return pageMetadata({
    title: `${investment.title ?? ""} — ${pick(settings, "site_name", "Quadrant Properties Dubai") ?? ""}`,
    description,
    path: `/investments/${slug}`,
  });
}

export default async function InvestmentPage({ params }: Props) {
  const { slug } = await params;
  const investment = await getInvestment(slug);
  if (!investment) notFound();

  const [related, settings] = await Promise.all([
    getRelatedInvestments(investment.id, investment.community_id),
    getSettings(),
  ]);
  const whatsapp = pick(settings, "whatsapp_number");
  const phone = pick(settings, "contact_phone");
  const title = investment.title ?? "";
  const currency = investment.price_currency ?? "AED";

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
                      <Link href="/investments">Investment</Link>
                    </li>
                    <li>
                      <span>{investment.title}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* GALLERY / MAIN IMAGE */}
        <section className="property-gallery-sec space pb-0">
          <div className="container">
            <div className="row">
              {/* Gallery Top Bar */}
              <div className="col-12">
                <div className="gallery-topbar">
                  <div className="topbar-left">
                    {truthy(investment.investment_type) && (
                      <p className="category-label">{investment.investment_type}</p>
                    )}
                  </div>

                  <div className="topbar-right">
                    <div className="share-blog mb-0">
                      <ShareDropdown url={siteUrl(`/investments/${slug}`)} title={title} />
                    </div>
                  </div>
                </div>
              </div>

              {/* Main Image */}
              <div className="col-12">
                <div>
                  {truthy(investment.main_image) ? (
                    <FallbackImg
                      loading="lazy"
                      decoding="async"
                      className="w-100"
                      style={{ borderRadius: 5 }}
                      src={asset(investment.main_image)}
                      alt={title}
                      fallbackSrc={PLACEHOLDER}
                    />
                  ) : (
                    <img loading="lazy" className="w-100" style={{ borderRadius: 5 }} src={PLACEHOLDER} alt={title} />
                  )}
                </div>
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
                  {/* Title + Location */}
                  <h1 className="mt-0">{investment.title}</h1>
                  {truthy(investment.community_name) && (
                    <p>
                      <img loading="lazy" decoding="async" src="/public/assets/img/hotel/map.svg" alt="Location" />
                      &nbsp; {investment.community_name}
                    </p>
                  )}

                  {/* Price + Key Metrics */}
                  <div className="price-amenitity">
                    {truthy(investment.price) ? (
                      <h3>
                        {currency} {numberFormat(investment.price)}/-
                      </h3>
                    ) : (
                      <h3>Price on Application</h3>
                    )}

                    <div className="main-amenity">
                      {truthy(investment.property_area_sqft) && (
                        <div className="amenity-box">
                          <div className="amenty-img">
                            <img loading="lazy" decoding="async" src="/public/assets/img/square.svg" alt="Area" />
                          </div>
                          <p>{numberFormat(investment.property_area_sqft)} SQ FT</p>
                        </div>
                      )}

                      {investment.property_bedrooms !== null && (
                        <div className="amenity-box">
                          <div className="amenty-img">
                            <img loading="lazy" decoding="async" src="/public/assets/img/hotel/2.svg" alt="Bedrooms" />
                          </div>
                          <p>{investment.property_bedrooms} Bedrooms</p>
                        </div>
                      )}
                    </div>
                  </div>

                  {/* Investment Metrics */}
                  {(truthy(investment.roi_percentage) ||
                    truthy(investment.gross_yield) ||
                    truthy(investment.net_yield) ||
                    truthy(investment.annual_rental)) && (
                    <div className="pay-plans">
                      <h5>Investment Highlights</h5>
                      <div className="plan-items">
                        {truthy(investment.roi_percentage) && (
                          <div className="plan-item">
                            <h4>
                              <span>ROI</span> {investment.roi_percentage}% <span>Estimated</span>
                            </h4>
                          </div>
                        )}
                        {truthy(investment.gross_yield) && (
                          <div className="plan-item">
                            <h4>
                              <span>Gross Yield</span> {investment.gross_yield}% <span>Annual</span>
                            </h4>
                          </div>
                        )}
                        {truthy(investment.net_yield) && (
                          <div className="plan-item">
                            <h4>
                              <span>Net Yield</span> {investment.net_yield}% <span>Annual</span>
                            </h4>
                          </div>
                        )}
                        {truthy(investment.annual_rental) && (
                          <div className="plan-item">
                            <h4>
                              <span>Rental Income</span> {currency} {numberFormat(investment.annual_rental)}{" "}
                              <span>Per Year</span>
                            </h4>
                          </div>
                        )}
                      </div>
                    </div>
                  )}

                  {/* Mobile Register Interest CTA */}
                  <a className="green-btn desktop-none mb-4" href="#interest">
                    Register your Interest
                  </a>

                  <div className="seperator" />

                  {/* Description */}
                  {truthy(investment.description) && (
                    <>
                      <h5>Description</h5>
                      <DescriptionShowMore html={investment.description ?? ""} />
                      <div className="seperator" />
                    </>
                  )}

                  {/* Linked Property */}
                  {truthy(investment.property_title) && (
                    <>
                      <div className="ameneties-panel">
                        <h5>Linked Property</h5>
                        <div className="mt-3">
                          <Link href={`/properties/${investment.property_slug}`} className="light-btn">
                            View {investment.property_title}
                          </Link>
                        </div>
                      </div>
                      <div className="seperator" />
                    </>
                  )}

                  {/* Location Map */}
                  {truthy(investment.community_latitude) && truthy(investment.community_longitude) ? (
                    <div className="property-location">
                      <h5>Property Location</h5>
                      {investment.community_name ?? ""}
                      <iframe
                        width="100%"
                        height="450"
                        frameBorder="0"
                        style={{ border: 0, marginTop: 15 }}
                        src={`https://www.google.com/maps?q=${investment.community_latitude},${investment.community_longitude}&hl=en&z=14&output=embed`}
                        allowFullScreen
                      />
                    </div>
                  ) : (
                    truthy(investment.community_name) && (
                      <div className="property-location">
                        <h5>Property Location</h5>
                        {investment.community_name}
                      </div>
                    )
                  )}
                </div>
              </div>

              {/* RIGHT: Sidebar */}
              <div className="col-lg-4">
                <div className="new-p-detail-right" id="interest">
                  {/* Register Interest Form */}
                  <div className="list-from">
                    <h3 className="mb-2">Register Your Interest</h3>
                    <InterestForm ts={formTimestamp()} source="investment_detail" notes={`Inquiry about: ${title}`} />
                  </div>

                  {/* Contact Buttons */}
                  <div className="agent-panel">
                    <div className="agent-contact-btn">
                      {truthy(whatsapp) && (
                        <a href={`https://wa.me/${(whatsapp ?? "").replace(/[^0-9]/g, "")}`} target="_blank" className="light-btn">
                          <img loading="lazy" src="/public/assets/img/whatsapp-dark.png" alt="WhatsApp" />{" "}
                          Whatsapp
                        </a>
                      )}
                      {truthy(phone) && (
                        <a href={`tel:${phone}`} className="light-btn">
                          <img loading="lazy" src="/public/assets/img/call-dark.png" alt="Call" />{" "}
                          Call Us Now!
                        </a>
                      )}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* MORE INVESTMENTS */}
        {related.length > 0 && (
          <section className="space position-relative pt-0">
            <div className="container">
              <div className="heading-pnel HeadingMiddleBorder">
                <div className="row">
                  <div className="col-lg-8 col-12">
                    <h2 className="m-0">More Investments</h2>
                  </div>
                  <div className="col-lg-4 col-12" />
                </div>
              </div>

              <div className="row">
                <div className="col-12">
                  <div className="cards-main">
                    <Carousel id="related-slider" loop margin={20} nav dots={false} responsive={{ 0: 1, 768: 2, 1024: 3 }}>
                      {related.map((item) => (
                        <div className="item" key={item.id}>
                          <div className="card-box">
                            <figure>
                              {truthy(item.investment_type) && (
                                <div className="VillaText">
                                  <p>{item.investment_type}</p>
                                </div>
                              )}
                              <Link href={`/investments/${item.slug}`}>
                                {truthy(item.main_image) ? (
                                  <FallbackImg
                                    loading="lazy"
                                    decoding="async"
                                    src={asset(item.main_image)}
                                    alt={item.title ?? ""}
                                    fallbackSrc={PLACEHOLDER}
                                  />
                                ) : (
                                  <img loading="lazy" src={PLACEHOLDER} alt={item.title ?? ""} />
                                )}
                              </Link>
                            </figure>
                            <figcaption>
                              <Link href={`/investments/${item.slug}`}>
                                <h3>{item.title}</h3>
                                {truthy(item.community_name) && (
                                  <span className="address_section">
                                    <p>
                                      <img loading="lazy" decoding="async" src="/public/assets/img/hotel/map.svg" alt="Map" />{" "}
                                      {item.community_name}
                                    </p>
                                  </span>
                                )}
                                {truthy(item.price) ? (
                                  <h6>
                                    <span>{item.price_currency ?? "AED"}</span> {numberFormat(item.price)}/-
                                  </h6>
                                ) : (
                                  <h6>Price on Application</h6>
                                )}
                              </Link>
                            </figcaption>
                          </div>
                        </div>
                      ))}
                    </Carousel>
                  </div>
                </div>
              </div>
            </div>
          </section>
        )}
      </div>

      {/* MOBILE SEARCH MODAL */}
      <SearchModal />
    </>
  );
}
