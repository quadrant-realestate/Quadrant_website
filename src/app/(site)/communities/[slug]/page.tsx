import Link from "@/components/site/Link";
import { notFound } from "next/navigation";
import { strLimit, stripTags } from "@/lib/format";
import { getCommentedPropertyIconCount, getCommunityBySlug, getOtherCommunities } from "@/lib/queries/communities";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { asset, siteUrl } from "@/lib/url";
import { FallbackImg } from "../../buy/_components/FallbackImg";
import { publicPath, truthy } from "../../buy/_components/php";
import { SearchModal } from "../_components/SearchModal";
import "./page.css";

// website/communities/show.blade.php — CommunityFrontController@show
// The properties / developments sections of the Blade page are commented out, so they are not
// rendered; only the property icon count is loaded, for the lazy-loading attributes (see query).

export const dynamic = "force-dynamic";

type Props = { params: Promise<{ slug: string }> };

const PLACEHOLDER = "/public/assets/img/thumbnail-placeholder-gallery.png";

export async function generateMetadata({ params }: Props) {
  const { slug } = await params;
  const [community, settings] = await Promise.all([getCommunityBySlug(slug), getSettings()]);
  if (!community) return pageMetadata({ path: `/communities/${slug}` });

  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai");
  return pageMetadata({
    title: `${community.name} — ${siteName}`,
    description:
      strLimit(stripTags(community.description), 160) ||
      `Explore properties in ${community.name} with ${siteName}. Luxury homes, apartments and villas.`,
    path: `/communities/${slug}`,
    ogImage: siteUrl(publicPath(community.image)),
  });
}

export default async function CommunityShow({ params }: Props) {
  const { slug } = await params;
  const community = await getCommunityBySlug(slug);
  if (!community) notFound();

  const [otherCommunities, iconCount] = await Promise.all([
    getOtherCommunities(community.id),
    getCommentedPropertyIconCount(community.id),
  ]);
  // LazyLoadImages middleware: the first 3 images without a loading attribute stayed eager
  const eager = Math.max(0, 3 - iconCount);

  return (
    <>
      {/* INNER BANNER */}
      <section
        className="banner inr-banner"
        style={{ backgroundImage: `url('${publicPath(community.banner_image ?? community.image)}')` }}
      >
        <div className="container">
          <div className="slider-info">
            <div className="BannerBox">
              <div className="banner-heading text-center">
                <h1>{community.name}</h1>
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
                    <Link href="/communities">Communities</Link>
                  </li>
                  <li>
                    <span>{community.name}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ABOUT COMMUNITY */}
      {truthy(community.description) && (
        <section className="space pb-0">
          <div className="container">
            <div className="row">
              <div className="col-lg-8">
                <div className="content-wrapper">
                  <h2>About {community.name}</h2>
                  <p>{community.description}</p>
                  {truthy(community.city) && (
                    <p>
                      <img loading="lazy" decoding="async" src="/public/assets/img/hotel/map.svg" alt="Location" />
                      &nbsp; {community.city}
                    </p>
                  )}
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* LOCATION MAP */}
      {truthy(community.latitude) && truthy(community.longitude) && (
        <section className="space pt-0">
          <div className="container">
            <div className="property-location">
              <h5>{community.name} Location</h5>
              <iframe
                width="100%"
                height="450"
                frameBorder="0"
                style={{ border: 0, marginTop: 15 }}
                src={`https://www.google.com/maps?q=${community.latitude},${community.longitude}&hl=en&z=13&output=embed`}
                allowFullScreen
              />
            </div>
          </div>
        </section>
      )}

      {/* OTHER COMMUNITIES */}
      {otherCommunities.length > 0 && (
        <section className="space position-relative pt-0">
          <div className="container">
            <div className="heading-pnel HeadingMiddleBorder">
              <div className="row">
                <div className="col-lg-8 col-12">
                  <h2 className="m-0">Explore Other Communities</h2>
                </div>
                <div className="col-lg-4 col-12 mobile-none">
                  <div className="head-btn">
                    <Link href="/communities" className="light-btn ml-auto">
                      View All Communities
                    </Link>
                  </div>
                </div>
              </div>
            </div>

            <div className="cards-main">
              <div className="row">
                {otherCommunities.map((other, i) => {
                  const lazy = i >= eager ? ({ loading: "lazy", decoding: "async" } as const) : {};
                  return (
                    <div className="col-lg-3 col-md-6 col-12" key={other.id}>
                      <div className="new-development card-box">
                        <Link href={`/communities/${other.slug}`}>
                          <figure>
                            {truthy(other.image) ? (
                              <FallbackImg {...lazy} src={asset(other.image)} fallback={PLACEHOLDER} alt={other.name} />
                            ) : (
                              <img {...lazy} src={PLACEHOLDER} alt={other.name} />
                            )}
                          </figure>
                        </Link>
                        <figcaption>
                          <Link href={`/communities/${other.slug}`}>
                            <h3>{other.name}</h3>
                          </Link>
                        </figcaption>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          </div>
        </section>
      )}

      {/* MOBILE SEARCH MODAL */}
      <SearchModal bedrooms={false} />
    </>
  );
}
