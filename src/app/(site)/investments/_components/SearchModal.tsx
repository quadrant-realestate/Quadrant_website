"use client";

import { useState } from "react";

const TABS = [
  { id: "Buy-two", label: "Buy", listingType: "sale" },
  { id: "Rent-two", label: "Rent", listingType: "rent" },
];

/**
 * The (hidden) mobile search modal the Blade listing/detail pages include.
 * Bootstrap's data-toggle="tab" tabs are React state.
 */
export function SearchModal() {
  const [active, setActive] = useState("Buy-two");

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
                <ul className="nav nav-tabs" role="tablist">
                  {TABS.map((tab) => (
                    <li className="nav-item" key={tab.id}>
                      <a
                        className={`nav-link${active === tab.id ? " active" : ""}`}
                        data-toggle="tab"
                        href={`#${tab.id}`}
                        role="tab"
                        onClick={(e) => {
                          e.preventDefault();
                          setActive(tab.id);
                        }}
                      >
                        {tab.label}
                      </a>
                    </li>
                  ))}
                </ul>
              </div>
              <div className="tab-content">
                {TABS.map((tab) => (
                  <div
                    id={tab.id}
                    className={`tab-pane fade${active === tab.id ? " show active" : ""}`}
                    role="tabpanel"
                    key={tab.id}
                  >
                    <form action="/properties/search" method="GET">
                      <input type="hidden" name="listing_type" value={tab.listingType} />
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
                              <img
                                loading="lazy"
                                decoding="async"
                                width={17}
                                height={17}
                                src="/public/assets/img/search.svg"
                                alt="Search"
                              />{" "}
                              Search
                            </button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
