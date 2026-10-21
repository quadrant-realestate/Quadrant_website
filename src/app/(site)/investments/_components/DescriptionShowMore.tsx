"use client";

import { useState } from "react";

/**
 * Description block of the detail pages: .parent-section with a 300px "Show More / Show Less" toggle
 * (the page script in investments/show and branded-residences/show).
 */
export function DescriptionShowMore({ html }: { html: string }) {
  const [open, setOpen] = useState(false);
  return (
    <div className="parent-section">
      <div
        className="show_more_content"
        style={{ maxHeight: open ? "none" : "300px", overflow: "hidden", transition: "max-height 0.5s ease-in-out" }}
        dangerouslySetInnerHTML={{ __html: html }}
      />
      <span id="toggleContentBtn" className="link-btn" onClick={() => setOpen((o) => !o)}>
        {open ? "Show Less" : "Show More"}
      </span>
    </div>
  );
}
