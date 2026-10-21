"use client";

// Filter sidebar open/close — same inline styles as openNav()/closeNav() in main43a0.js.

function openNav() {
  const sidebar = document.getElementById("filters-sidebar");
  const overlay = document.getElementById("filter-overlay");
  if (sidebar) sidebar.style.cssText = "width:360px;right:0px;";
  if (overlay) overlay.style.display = "block";
}

function closeNav() {
  const sidebar = document.getElementById("filters-sidebar");
  const overlay = document.getElementById("filter-overlay");
  if (sidebar) sidebar.style.cssText = "width:0;right:-400px;";
  if (overlay) overlay.style.display = "none";
}

export function FilterOpenLink({ className, children }: { className?: string; children: React.ReactNode }) {
  return (
    <a
      href="#"
      className={className}
      onClick={(e) => {
        e.preventDefault();
        openNav();
      }}
    >
      {children}
    </a>
  );
}

export function FilterCloseLink() {
  return (
    <a
      href="#"
      className="closebtn"
      onClick={(e) => {
        e.preventDefault();
        closeNav();
      }}
    >
      <img loading="lazy" decoding="async" src="/public/assets/img/cross.svg" alt="Close" />
    </a>
  );
}

export function FilterOverlay() {
  return <div id="filter-overlay" onClick={closeNav} />;
}
