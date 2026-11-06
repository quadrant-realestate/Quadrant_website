"use client";

import { useEffect, useState } from "react";
import Link from "@/components/site/Link";

const SEEN_KEY = "qp-current-projects-toast";

/**
 * Notification that slides in once per visit and hides itself after 4 seconds.
 * The 4 seconds is the length of the qp-toast animation in home.css.
 */
export function CurrentProjectsToast({ count }: { count: number }) {
  const [show, setShow] = useState(false);

  useEffect(() => {
    // Storage can be blocked (private windows); the notification then shows on every home page load.
    try {
      if (sessionStorage.getItem(SEEN_KEY)) return;
      sessionStorage.setItem(SEEN_KEY, "1");
    } catch {}
    setShow(true);
  }, []);

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
