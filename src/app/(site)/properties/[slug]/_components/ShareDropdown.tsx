"use client";

import { useEffect, useRef, useState } from "react";

/** Share button + Bootstrap dropdown (data-toggle="dropdown") and copyCurrentUrl() from show.blade.php. */
export function ShareDropdown({ links }: { links: { facebook: string; twitter: string; linkedin: string; whatsapp: string } }) {
  const [open, setOpen] = useState(false);
  const ref = useRef<HTMLDivElement>(null);

  // Bootstrap closes an open dropdown on any click outside it.
  useEffect(() => {
    if (!open) return;
    const onClick = (e: MouseEvent) => {
      if (ref.current && !ref.current.contains(e.target as Node)) setOpen(false);
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
        <a className="dropdown-item" href={links.facebook} target="_blank">
          <i className="fa-brands fa-facebook" /> Share on Facebook
        </a>
        <a className="dropdown-item" href={links.twitter} target="_blank">
          <i className="fa-brands fa-square-twitter" /> Share on Twitter
        </a>
        <a className="dropdown-item" href={links.linkedin} target="_blank">
          <i className="fa-brands fa-linkedin" /> Share on LinkedIn
        </a>
        <a className="dropdown-item" href={links.whatsapp} target="_blank">
          <i className="fa-brands fa-square-whatsapp" /> Share on WhatsApp
        </a>
      </div>
    </div>
  );
}
