import Link from "@/components/site/Link";
import { notFound } from "next/navigation";
import { DescriptionShowMore } from "../../investments/_components/DescriptionShowMore";
import { FallbackImg } from "../../investments/_components/FallbackImg";
import { InterestForm } from "../../investments/_components/InterestForm";
import { SearchModal } from "../../investments/_components/SearchModal";
import { ShareDropdown } from "../../investments/_components/ShareDropdown";
import { truthy } from "../../investments/_components/helpers";
import { Carousel } from "@/components/site/Carousel";
import { numberFormat, strLimit, stripTags } from "@/lib/format";
import { getRelatedResidences, getResidence } from "@/lib/queries/branded-residences";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { formTimestamp } from "@/lib/spam";
import { asset, siteUrl } from "@/lib/url";
import "./page.css";

type Props = { params: Promise<{ slug: string }> };

const PLACEHOLDER = "/public/assets/img/placeholder.jpg";

export async function generateMetadata({ params }: Props) {
  const { slug } = await params;
  const residence = await getResidence(slug);
  if (!residence) return {};
  const settings = await getSettings();
  const description =
    strLimit(stripTags(residence.description), 160) ||
    `Discover ${residence.title ?? ""} by ${residence.brand_name ?? ""} in ${residence.community_name ?? "Dubai"}. ${pick(settings, "site_name") ?? ""} — Luxury Branded Residences.`;
  return pageMetadata({
    title: `${residence.title ?? ""} — ${pick(settings, "site_name", "Quadrant Properties Dubai") ?? ""}`,
    description,
    path: `/branded-residences/${slug}`,
  });
}

export default async function BrandedResidencePage({ params }: Props) {
  const { slug } = await params;
  const residence = await getResidence(slug);
  if (!residence) notFound();

  const [related, settings] = await Promise.all([getRelatedResidences(residence.id), getSettings()]);
  const whatsapp = pick(settings, "whatsapp_number");
  const phone = pick(settings, "contact_phone");
  const title = residence.title ?? "";

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
                      <Link href="/branded-residences">Branded Residences</Link>
                    </li>
                    <li>
                      <span>{residence.title}</span>
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
                    <p className="category-label">{residence.brand_name ?? "Branded Residence"}</p>
                  </div>

                  <div className="topbar-right">
                    <div className="share-blog mb-0">
                      <ShareDropdown url={siteUrl(`/branded-residences/${slug}`)} title={title} />
                    </div>
                  </div>
                </div>
              </div>

              {/* Main Image */}
              <div className="col-12">
                <div>
                  {truthy(residence.main_image) ? (
                    <FallbackImg
                      loading="lazy"
                      decoding="async"
                      className="w-100"
                      style={{ borderRadius: 5 }}
                      src={asset(residence.main_image)}
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
                  <h1 className="mt-0">{residence.title}</h1>
                  {truthy(residence.community_name) && (
                    <p>
                      <img loading="lazy" decoding="async" src="/public/assets/img/hotel/map.svg" alt="Location" />
                      &nbsp; {residence.community_name}
                    </p>
                  )}

                  {/* Brand Logo */}
                  {truthy(residence.brand_logo) && (
                    <div className="mb-3">
                      <img
                        src={asset(residence.brand_logo)}
                        alt={residence.brand_name ?? ""}
                        height={50}
                        style={{ objectFit: "contain", maxWidth: 180 }}
                      />
                    </div>
                  )}

                  {/* Price + Key Metrics */}
                  <div className="price-amenitity">
                    {truthy(residence.price_from) && <h3>Starting Price: AED {numberFormat(residence.price_from)}/-</h3>}

                    <div className="main-amenity">
                      {truthy(residence.property_area_sqft) && (
                        <div className="amenity-box">
                          <div className="amenty-img">
                            <img loading="lazy" decoding="async" src="/public/assets/img/square.svg" alt="Area" />
                          </div>
                          <p>{numberFormat(residence.property_area_sqft)} SQ FT</p>
                        </div>
                      )}

                      {residence.property_bedrooms !== null && (
                        <div className="amenity-box">
                          <div className="amenty-img">
                            <img loading="lazy" decoding="async" src="/public/assets/img/hotel/2.svg" alt="Bedrooms" />
                          </div>
                          <p>{residence.property_bedrooms} Bedrooms</p>
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
                  {truthy(residence.description) && (
                    <>
                      <h5>Description</h5>
                      <DescriptionShowMore html={residence.description ?? ""} />
                      <div className="seperator" />
                    </>
                  )}

                  {/* Linked Property */}
                  {truthy(residence.property_title) && (
                    <>
                      <div className="ameneties-panel">
                        <h5>Linked Property</h5>
                        <div className="mt-3">
                          <Link href={`/properties/${residence.property_slug}`} className="light-btn">
                            View {residence.property_title}
                          </Link>
                        </div>
                      </div>
                      <div className="seperator" />
                    </>
                  )}

                  {/* Location Map */}
                  {truthy(residence.community_latitude) && truthy(residence.community_longitude) ? (
                    <div className="property-location">
                      <h5>Property Location</h5>
                      {residence.community_name ?? ""}
                      <iframe
                        width="100%"
                        height="450"
                        frameBorder="0"
                        style={{ border: 0, marginTop: 15 }}
                        src={`https://www.google.com/maps?q=${residence.community_latitude},${residence.community_longitude}&hl=en&z=14&output=embed`}
                        allowFullScreen
                      />
                    </div>
                  ) : (
                    truthy(residence.community_name) && (
                      <div className="property-location">
                        <h5>Property Location</h5>
                        {residence.community_name}
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
                    <InterestForm
                      ts={formTimestamp()}
                      source="branded_residence_detail"
                      notes={`Inquiry about: ${title}`}
                    />
                  </div>

                  {/* Contact Buttons */}
                  <div className="agent-panel">
                    <div className="agent-contact-btn">
                      {truthy(whatsapp) && (
                        <a
                          href={`https://wa.me/${(whatsapp ?? "").replace(/[^0-9]/g, "")}`}
                          target="_blank"
                          className="light-btn"
                        >
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

        {/* RELATED BRANDED RESIDENCES */}
        {related.length > 0 && (
          <section className="space position-relative pt-0">
            <div className="container">
              <div className="heading-pnel HeadingMiddleBorder">
                <div className="row">
                  <div className="col-lg-8 col-12">
                    <h2 className="m-0">More Branded Residences</h2>
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
                              <div className="VillaText">
                                <p>Branded Residence</p>
                              </div>
                              {/* Blade links these to route('developments.show') = /buy/{slug} */}
                              <Link href={`/buy/${item.slug}`}>
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
                              <Link href={`/buy/${item.slug}`}>
                                <h3>{item.title}</h3>
                                {truthy(item.community_name) && (
                                  <span className="address_section">
                                    <p>
                                      <img
                                        loading="lazy"
                                        decoding="async"
                                        src="/public/assets/img/hotel/map.svg"
                                        alt="Map"
                                      />{" "}
                                      {item.community_name}
                                    </p>
                                  </span>
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
