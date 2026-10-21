"use client";

import { useState } from "react";

/**
 * "Show More / Show Less" toggle for long descriptions (the theme's .show_more_content block).
 * Content is clipped to `collapsedHeight` px until expanded.
 */
export function ShowMore({
  html,
  collapsedHeight = 300,
  className = "show_more_content",
  buttonClassName = "link-btn",
}: {
  html: string;
  collapsedHeight?: number;
  className?: string;
  buttonClassName?: string;
}) {
  const [open, setOpen] = useState(false);
  return (
    <>
      <div
        className={className}
        style={{ maxHeight: open ? "none" : collapsedHeight, overflow: "hidden" }}
        dangerouslySetInnerHTML={{ __html: html }}
      />
      <button type="button" className={buttonClassName} onClick={() => setOpen((o) => !o)}>
        {open ? "Show Less" : "Show More"}
      </button>
    </>
  );
}
