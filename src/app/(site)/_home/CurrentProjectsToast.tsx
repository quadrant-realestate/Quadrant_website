"use client";

import { useState } from "react";
import Link from "@/components/site/Link";

/**
 * Notification that slides in on every home page load and hides itself after 10 seconds.
 * The 10 seconds is the length of the qp-toast animation in home.css.
 */
export function CurrentProjectsToast({ count }: { count: number }) {
  const [show, setShow] = useState(true);

  if (!show) return null;

  return (
    <div className="qp-toast" role="status" onAnimationEnd={() => setShow(false)}>
      <span className="qp-toast-icon">
        <i className="fa-solid fa-bell" />
      </span>
      <Link href="/buy#current-projects" className="qp-toast-text">
        <strong>Current Projects</strong>
        <span>
          {count} {count === 1 ? "project" : "projects"} in progress. View now →
        </span>
      </Link>
      <button type="button" className="qp-toast-close" onClick={() => setShow(false)} aria-label="Close">
        <i className="fa-solid fa-xmark" />
      </button>
    </div>
  );
}
