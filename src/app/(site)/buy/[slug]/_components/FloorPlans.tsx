"use client";

import { useState } from "react";
import type { FloorPlan } from "@/lib/queries/developments";
import { asset } from "@/lib/url";
import { truthy } from "../../_components/php";

/** 5. Floor plans (tabbed) — was qpFpShow() inline script. */
/** `eager`: how many of these images are still among the page's first 3 (LazyLoadImages middleware). */
export function FloorPlans({ plans, eager }: { plans: FloorPlan[]; eager: number }) {
  const [active, setActive] = useState(0);

  return (
    <div className="qp-pd-section">
      <h2>Floor Plans</h2>
      <div className="qp-fp-tabs">
        {plans.map((plan, i) => (
          <div className={`qp-fp-tab ${i === active ? "active" : ""}`} onClick={() => setActive(i)} key={plan.id}>
            {plan.unit_type}
          </div>
        ))}
      </div>
      {plans.map((plan, i) => (
        <div className={`qp-fp-panel ${i === active ? "active" : ""}`} id={`qp-fp-panel-${i}`} key={plan.id}>
          <div className="qp-fp-img">
            <img
              {...(i >= eager ? ({ loading: "lazy", decoding: "async" } as const) : {})}
              src={truthy(plan.image) ? asset(plan.image) : "/public/assets/img/placeholder.jpg"}
              alt={plan.unit_type ?? ""}
            />
          </div>
          <div className="qp-fp-meta">
            <p>
              <strong style={{ color: "#1B2538" }}>{plan.unit_type}</strong>
            </p>
            {truthy(plan.size_sqft) && <p>{plan.size_sqft} sq ft</p>}
            {truthy(plan.pdf_file) && (
              <a href={asset(plan.pdf_file)} target="_blank" className="qp-fp-download">
                Download Floor Plan (PDF)
              </a>
            )}
          </div>
        </div>
      ))}
    </div>
  );
}
