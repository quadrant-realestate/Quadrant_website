"use client";

import Link from "@/components/site/Link";
import { usePathname } from "next/navigation";
import { useEffect, useState } from "react";

const NAV = [
  { href: "/buy", label: "Buy", match: ["/buy"] },
  { href: "/sell", label: "Sell", match: ["/sell"] },
  { href: "/communities", label: "Communities", match: ["/communities"] },
  { href: "/services", label: "Services", match: ["/services"] },
  { href: "/about", label: "About", match: ["/about"] },
  { href: "/blogs", label: "Blogs", match: ["/blogs"] },
  { href: "/contact", label: "Contact", match: ["/contact"] },
];

export function Header({ siteName }: { siteName: string | null }) {
  const pathname = usePathname();
  const [scrolled, setScrolled] = useState(false); // transparent-to-navy after 60px
  const [fixed, setFixed] = useState(false); // theme's "header-fixed" class after 50px
  const [menuOpen, setMenuOpen] = useState(false);

  useEffect(() => {
    const onScroll = () => {
      setScrolled(window.scrollY > 60);
      setFixed(window.scrollY >= 50);
    };
    onScroll();
    window.addEventListener("scroll", onScroll);
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  // Close the mobile menu after navigating.
  useEffect(() => setMenuOpen(false), [pathname]);

  const isActive = (match: string[]) => match.some((m) => pathname === m || pathname.startsWith(`${m}/`));
  const alt = siteName ?? "Quadrant Properties";

  return (
    <>
      <header
        className={`header js-header${fixed ? " header-fixed" : ""}${scrolled ? " qp-scrolled" : ""}`}
        id="qp-header"
        style={{
          position: "fixed",
          top: 0,
          left: 0,
          right: 0,
          zIndex: 1000,
          transition: "background .3s ease, box-shadow .3s ease",
        }}
      >
        <div className="container">
          <nav className="navbar navbar-expand-lg navbar-light">
            {/* Logo */}
            <div className="logo-web">
              <Link className="navbar-brand" href="/">
                <div className="logo-box">
                  {/* White/gold logo on the transparent header; navy version once scrolled */}
                  <img
                    src="/public/logo-dark-quadrant.png"
                    width={160}
                    loading="lazy"
                    alt={alt}
                    id="qp-logo-dark"
                    style={{ display: scrolled ? "none" : "block" }}
                  />
                  <img
                    src="/public/logo-light-quadrant.png"
                    width={160}
                    loading="lazy"
                    alt={alt}
                    id="qp-logo-light"
                    style={{ display: scrolled ? "block" : "none" }}
                  />
                </div>
              </Link>
            </div>

            {/* Desktop Nav */}
            <div className="right-head">
              <div className="collapse navbar-collapse" id="navbarSupportedContent">
                <ul className="navbar-nav ml-lg-auto">
                  {NAV.map((item) => (
                    <li className="nav-item" key={item.href}>
                      <Link className={`nav-link ${isActive(item.match) ? "active" : ""}`} href={item.href}>
                        {item.label}
                      </Link>
                    </li>
                  ))}
                </ul>
              </div>

              <div className="EndSide">
                {/* Register Interest CTA */}
                <div className="schedule-call mobile-none">
                  <Link className="btn qp-cta-outline" href="/contact">
                    Register Interest
                  </Link>
                </div>

                {/* Mobile toggle */}
                <div className="mobile-icon">
                  <button
                    className="navbar-toggler nav-btn nav-slider"
                    aria-label="Menu"
                    type="button"
                    onClick={() => setMenuOpen((open) => !open)}
                  >
                    <span className="navbar-toggler-icon" />
                  </button>
                </div>
              </div>
            </div>
          </nav>
        </div>
      </header>

      {/* Mobile Sidebar */}
      <nav className={`sidebar${menuOpen ? " open" : ""}`} id="accordion-menu">
        <div className="authfy-body">
          <div className="menu-logo-box" style={{ backgroundColor: "transparent", marginBottom: 0 }}>
            <img loading="lazy" src="/public/logo-dark-quadrant.png" alt={alt} />
          </div>
          <ul className="navbar-nav">
            {NAV.map((item) => (
              <li className="nav-item" key={item.href}>
                <Link className="nav-link" href={item.href}>
                  {item.label}
                </Link>
              </li>
            ))}
          </ul>
          <div className="menu-btn-grup">
            <Link className="btn green-btn" href="/contact">
              Register Interest
            </Link>
          </div>
        </div>
      </nav>

      {/* Clicking outside the open sidebar closes it */}
      <div
        className="overlay-body"
        style={menuOpen ? { display: "block" } : undefined}
        onClick={() => setMenuOpen(false)}
      />
    </>
  );
}
