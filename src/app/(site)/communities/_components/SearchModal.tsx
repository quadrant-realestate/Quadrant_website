"use client";

import { useState } from "react";

/**
 * "Mobile search modal" block of the community pages. Nothing on the page opens it
 * (it stays hidden by Bootstrap's .modal CSS, like in Laravel); the Buy / Rent tabs work with state.
 */
export function SearchModal({ bedrooms }: { bedrooms: boolean }) {
  const [tab, setTab] = useState<"buy" | "rent">("buy");

  const form = (listingType: "sale" | "rent") => (
    <form action="/properties/search" method="GET">
      <input type="hidden" name="listing_type" value={listingType} />
      <div className="BookingBox">
        <div className="BookingLocation">
          <div className="BookingFrom">
            <input type="text" name="search" className="form-control" placeholder="Search..." />
          </div>
          {bedrooms && (
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
          )}
          <div className="BookingFromBtn">
            <button type="submit">
              <img loading="lazy" decoding="async" width="17" height="17" src="/public/assets/img/search.svg" alt="Search" /> Search
            </button>
          </div>
        </div>
      </div>
    </form>
  );

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
                  <li className="nav-item">
                    <a
                      className={`nav-link ${tab === "buy" ? "active" : ""}`}
                      href="#Buy-two"
                      role="tab"
                      onClick={(e) => {
                        e.preventDefault();
                        setTab("buy");
                      }}
                    >
                      Buy
                    </a>
                  </li>
                  <li className="nav-item">
                    <a
                      className={`nav-link ${tab === "rent" ? "active" : ""}`}
                      href="#Rent-two"
                      role="tab"
                      onClick={(e) => {
                        e.preventDefault();
                        setTab("rent");
                      }}
                    >
                      Rent
                    </a>
                  </li>
                </ul>
              </div>
              <div className="tab-content">
                <div id="Buy-two" className={`tab-pane fade ${tab === "buy" ? "show active" : ""}`} role="tabpanel">
                  {form("sale")}
                </div>
                <div id="Rent-two" className={`tab-pane fade ${tab === "rent" ? "show active" : ""}`} role="tabpanel">
                  {form("rent")}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
