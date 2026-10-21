"use client";

import { usePathname } from "next/navigation";
import { useEffect, useState } from "react";
import { signOut } from "@/actions/auth";
import { Sidebar } from "./Sidebar";

/** Page wrapper, sidebar and top header of the admin theme (admin/include/header + sidebar + footer). */
export function AdminShell({ newInquiries, children }: { newInquiries: number; children: React.ReactNode }) {
  // Same as the theme's app.min.js: "mini-sidebar" below 1199px wide, and the
  // sidebar togglers flip both mini-sidebar and show-sidebar.
  const [mini, setMini] = useState(false);
  const [showSidebar, setShowSidebar] = useState(false);
  const pathname = usePathname();

  useEffect(() => {
    const setSidebarType = () => setMini(window.innerWidth < 1199);
    setSidebarType();
    window.addEventListener("resize", setSidebarType);
    return () => window.removeEventListener("resize", setSidebarType);
  }, []);

  useEffect(() => setShowSidebar(false), [pathname]);

  const toggle = () => {
    setMini((m) => !m);
    setShowSidebar((s) => !s);
  };

  return (
    <div
      className={`page-wrapper${mini ? " mini-sidebar" : ""}${showSidebar ? " show-sidebar" : ""}`}
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin6"
      data-sidebartype={mini ? "mini-sidebar" : "full"}
      data-sidebar-position="fixed"
      data-header-position="fixed"
    >
      <Sidebar newInquiries={newInquiries} onToggle={toggle} />

      <div className="body-wrapper">
        <header className="app-header">
          <nav className="navbar navbar-expand-lg navbar-light">
            <ul className="navbar-nav">
              <li className="nav-item d-block d-xl-none">
                <a
                  className="nav-link sidebartoggler"
                  id="headerCollapse"
                  href="#"
                  onClick={(e) => {
                    e.preventDefault();
                    toggle();
                  }}
                >
                  <i className="ti ti-menu-2" />
                </a>
              </li>
              <li className="nav-item dropdown" />
            </ul>
            <div className="navbar-collapse justify-content-end px-0" id="navbarNav">
              <ul className="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <a className="btn btn-primary">Hello, Admin</a>
                <li className="nav-item dropdown">
                  <a className="nav-link" href="#" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="/public/adminassets/images/profile/user-1.jpg" alt="" width={35} height={35} className="rounded-circle" />
                  </a>
                  <div className="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                    <div className="message-body">
                      <a href="/admin/profile" className="d-flex align-items-center gap-2 dropdown-item">
                        <i className="ti ti-user fs-6" />
                        <p className="mb-0 fs-3">My Profile</p>
                      </a>
                      <form action={signOut}>
                        <button type="submit" className="btn btn-outline-primary mx-3 mt-2 d-block">
                          Logout
                        </button>
                      </form>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </nav>
        </header>

        {children}
      </div>
    </div>
  );
}
