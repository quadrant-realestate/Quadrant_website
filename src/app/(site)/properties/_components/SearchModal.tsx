import type { PropertyType } from "@/lib/queries/properties";

const BEDROOMS = ["1", "2", "3", "4", "5", "6"];

function ModalForm({ listingType, propertyTypes }: { listingType: string | null; propertyTypes: PropertyType[] }) {
  return (
    <form action="/properties/search" method="GET">
      {listingType !== null && <input type="hidden" name="listing_type" value={listingType} />}
      <div className="BookingBox">
        <div className="BookingLocation">
          <div className="BookingFrom">
            <input type="text" name="search" className="form-control" placeholder="Search..." />
          </div>
          <div className="BookingFrom p-0">
            <select name="property_type_id" className="form-control">
              <option value="">Property Type</option>
              {propertyTypes.map((type) => (
                <option value={type.id} key={type.id}>
                  {type.name}
                </option>
              ))}
            </select>
          </div>
          <div className="BookingFrom p-0">
            <select className="form-control" name="bedrooms">
              <option value="">Bedrooms</option>
              {BEDROOMS.map((b) => (
                <option value={b} key={b}>
                  {b}
                </option>
              ))}
              <option value="7">7+</option>
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

/**
 * Mobile search modal (Bootstrap .modal, hidden — nothing on these pages opens it).
 * `active` = which tab is shown; `buyListingType` = hidden listing_type of the Buy form (null = none).
 */
export function SearchModal({
  propertyTypes,
  active,
  buyListingType,
}: {
  propertyTypes: PropertyType[];
  active: "buy" | "rent";
  buyListingType: string | null;
}) {
  return (
    <div className="modal fade" id="search-modal" tabIndex={-1} role="dialog" aria-hidden="true">
      <div className="modal-dialog modal-dialog-centered" role="document">
        <div className="modal-content">
          <div className="modal-body">
            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <div className="search-container">
              <div className="TopTabsBar">
                <ul className="nav nav-tabs" id="modalTab" role="tablist">
                  <li className="nav-item">
                    <a className={`nav-link${active === "buy" ? " active" : ""}`} data-toggle="tab" href="#Buy-two" role="tab">
                      Buy
                    </a>
                  </li>
                  <li className="nav-item">
                    <a className={`nav-link${active === "rent" ? " active" : ""}`} data-toggle="tab" href="#Rent-two" role="tab">
                      Rent
                    </a>
                  </li>
                </ul>
              </div>
              <div className="tab-content">
                <div id="Buy-two" className={`tab-pane fade${active === "buy" ? " show active" : ""}`} role="tabpanel">
                  <ModalForm listingType={buyListingType} propertyTypes={propertyTypes} />
                </div>
                <div id="Rent-two" className={`tab-pane fade${active === "rent" ? " show active" : ""}`} role="tabpanel">
                  <ModalForm listingType="rent" propertyTypes={propertyTypes} />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
