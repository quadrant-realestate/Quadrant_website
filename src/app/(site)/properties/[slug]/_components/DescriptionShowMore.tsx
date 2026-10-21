"use client";

import { useState } from "react";

/**
 * .parent-section "Show More / Show Less" for the description (page script in show.blade.php):
 * content clipped to 300px until expanded. Same markup as the Blade view.
 */
export function DescriptionShowMore({ html }: { html: string }) {
  const [open, setOpen] = useState(false);
  return (
    <div className="parent-section">
      <div
        className="show_more_content"
        style={{ maxHeight: open ? "none" : "300px", overflow: "hidden" }}
        dangerouslySetInnerHTML={{ __html: html }}
      />
      <span id="toggleContentBtn" className="link-btn" onClick={() => setOpen((o) => !o)}>
        {open ? "Show Less" : "Show More"}
      </span>
    </div>
  );
}
