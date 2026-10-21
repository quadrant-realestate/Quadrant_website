"use client";

import { useEffect, useRef, useState } from "react";
import { urlencode } from "./helpers";

/** The detail pages' "Share" Bootstrap dropdown, with copyCurrentUrl(). */
export function ShareDropdown({ url, title }: { url: string; title: string }) {
  const [open, setOpen] = useState(false);
  const ref = useRef<HTMLDivElement>(null);

  // Bootstrap closes the dropdown on any click outside it.
  useEffect(() => {
    if (!open) return;
    const onClick = (e: MouseEvent) => {
      if (!ref.current?.contains(e.target as Node)) setOpen(false);
    };
    document.addEventListener("click", onClick);
    return () => document.removeEventListener("click", onClick);
  }, [open]);

  const copyCurrentUrl = (event: React.MouseEvent) => {
    event.preventDefault();
    const currentUrl = window.location.href;
    navigator.clipboard
      .writeText(currentUrl)
      .then(() => alert("URL copied to clipboard!"))
      .catch((err) => alert("Failed to copy URL: " + err));
  };

  const u = urlencode(url);

  return (
    <div className={`dropdown share dropdown social_share${open ? " show" : ""}`} ref={ref}>
      <a
        href="#"
        className="green-btn"
        onClick={(e) => {
          e.preventDefault();
          setOpen((o) => !o);
        }}
        role="button"
        id="shareDropdown"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded={open}
      >
        <img width={14} height={10} src="/public/assets/img/share.svg" alt="Share" />
        Share
      </a>
      <div className={`dropdown-menu${open ? " show" : ""}`} aria-labelledby="shareDropdown">
        <a className="dropdown-item" href="#" onClick={copyCurrentUrl}>
          <i className="fa-solid fa-copy" /> Copy Page URL
        </a>
        <a className="dropdown-item" href={`https://www.facebook.com/sharer/sharer.php?u=${u}`} target="_blank">
          <i className="fa-brands fa-facebook" /> Share on Facebook
        </a>
        <a
          className="dropdown-item"
          href={`https://twitter.com/intent/tweet?url=${u}&text=${urlencode(title)}`}
          target="_blank"
        >
          <i className="fa-brands fa-square-twitter" /> Share on Twitter
        </a>
        <a className="dropdown-item" href={`https://www.linkedin.com/shareArticle?mini=true&url=${u}`} target="_blank">
          <i className="fa-brands fa-linkedin" /> Share on LinkedIn
        </a>
        <a className="dropdown-item" href={`https://wa.me/?text=${urlencode(`${title} ${url}`)}`} target="_blank">
          <i className="fa-brands fa-square-whatsapp" /> Share on WhatsApp
        </a>
      </div>
    </div>
  );
}
