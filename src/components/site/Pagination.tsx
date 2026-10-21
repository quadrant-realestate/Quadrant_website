import Link from "@/components/site/Link";

type Props = {
  currentPage: number;
  lastPage: number;
  /** Current path, e.g. "/buy" */
  path: string;
  /** Current query string values — kept on every page link (Laravel's withQueryString()). */
  query?: Record<string, string | string[] | undefined>;
  style?: React.CSSProperties;
};

/**
 * Same markup as the Blade "pagination-main" block used on the listing pages.
 * Like Laravel's hasPages(), it renders nothing only when there is one page and we're on it.
 */
export function Pagination({ currentPage, lastPage, path, query = {}, style = { marginTop: 44 } }: Props) {
  if (lastPage <= 1 && currentPage <= 1) return null;

  const href = (page: number) => {
    const params = new URLSearchParams();
    for (const [key, value] of Object.entries(query)) {
      if (key === "page" || value === undefined) continue;
      for (const v of Array.isArray(value) ? value : [value]) params.append(key, v);
    }
    params.set("page", String(page));
    return `${path}?${params.toString()}`;
  };

  return (
    <div className="pagination-main" style={style}>
      <div className="row">
        <div className="col-12">
          <div className="pagination-container">
            <ul>
              {currentPage <= 1 ? (
                <li className="prev disabled">
                  <span>Prev</span>
                </li>
              ) : (
                <li className="prev">
                  <Link href={href(currentPage - 1)}>Prev</Link>
                </li>
              )}

              {Array.from({ length: lastPage }, (_, i) => i + 1).map((page) =>
                page === currentPage ? (
                  <li className="active" key={page}>
                    <span>{page}</span>
                  </li>
                ) : (
                  <li key={page}>
                    <Link href={href(page)}>{page}</Link>
                  </li>
                ),
              )}

              {currentPage < lastPage ? (
                <li className="next">
                  <Link href={href(currentPage + 1)}>Next</Link>
                </li>
              ) : (
                <li className="next disabled">
                  <span>Next</span>
                </li>
              )}
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
}
