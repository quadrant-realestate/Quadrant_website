import Link from "@/components/site/Link";
import { Pagination } from "@/components/site/Pagination";
import { getCommunitiesPage } from "@/lib/queries/communities";
import { pageMetadata } from "@/lib/seo";
import { getSettings, pick } from "@/lib/settings";
import { asset } from "@/lib/url";
import { FallbackImg } from "../buy/_components/FallbackImg";
import { pageNumber, requestInput, truthy, type SearchParams } from "../buy/_components/php";
import { SearchModal } from "./_components/SearchModal";
import "./page.css";

// website/communities/index.blade.php — CommunityFrontController@index

export const revalidate = 60;

type Props = { searchParams: Promise<SearchParams> };

const PLACEHOLDER = "/public/assets/img/thumbnail-placeholder-gallery.png";

export async function generateMetadata() {
  const settings = await getSettings();
  const siteName = pick(settings, "site_name", "Quadrant Properties Dubai");
  return pageMetadata({
    title: `Communities in Dubai — ${siteName}`,
    description: `Explore Dubai's most prestigious communities with ${siteName}. Find luxury properties in Palm Jumeirah, Dubai Marina, Downtown Dubai and more.`,
    path: "/communities",
  });
}

export default async function CommunitiesIndex({ searchParams }: Props) {
  const req = requestInput(await searchParams);
  const communities = await getCommunitiesPage(req.search, pageNumber(req.page));

  return (
    <>
      {/* PAGE HERO */}
      <section className="qp-offplan-banner">
        <div className="container">
          <h1>Communities</h1>
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
                    <span>Communities</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* COMMUNITIES GRID */}
      <section className="space position-relative">
        <div className="container">
          {/* Top Bar */}
          <div className="listing-top-area">
            <div className="row">
              <div className="col-12">
                <div className="listing-top-area-container">
                  <div className="item-counter">
                    <p>
                      Results: <span>{communities.total} Communities</span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Community Cards */}
          <div className="cards-main">
            <div className="row">
              {communities.data.length > 0 ? (
                communities.data.map((community, i) => {
                  const lazy = i >= 3 ? ({ loading: "lazy", decoding: "async" } as const) : {};
                  return (
                    <div className="col-lg-3 col-md-6 col-12" key={community.id}>
                      <div className="new-development card-box">
                        <Link href={`/communities/${community.slug}`}>
                          <figure>
                            {truthy(community.image) ? (
                              <FallbackImg {...lazy} src={asset(community.image)} fallback={PLACEHOLDER} alt={community.name} />
                            ) : (
                              <img {...lazy} src={PLACEHOLDER} alt={community.name} />
                            )}
                          </figure>
                        </Link>
                        <figcaption>
                          <Link href={`/communities/${community.slug}`}>
                            <h3>{community.name}</h3>
                          </Link>
                        </figcaption>
                      </div>
                    </div>
                  );
                })
              ) : (
                <div className="col-12 text-center py-5">
                  <p className="text-muted">No communities found.</p>
                </div>
              )}
            </div>
          </div>

          {/* PAGINATION */}
          <Pagination
            currentPage={communities.currentPage}
            lastPage={communities.lastPage}
            path="/communities"
            query={req}
            style={{}}
          />
        </div>
      </section>

      {/* MOBILE SEARCH MODAL */}
      <SearchModal bedrooms />
    </>
  );
}
