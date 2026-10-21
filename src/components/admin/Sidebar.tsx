"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useState } from "react";
import { signOut } from "@/actions/auth";

type Leaf = { label: string; href: string; isNew?: boolean };
type Group = { label: string; icon: string; children: Leaf[]; badge?: number };
type Single = { label: string; icon: string; href: string };
type Section = { cap: string; items: (Group | Single)[] };

const isGroup = (item: Group | Single): item is Group => "children" in item;

function menu(newInquiries: number): Section[] {
  return [
    { cap: "Main", items: [{ label: "Dashboard", icon: "solar:widget-5-line-duotone", href: "/admin" }] },
    {
      cap: "Properties",
      items: [
        {
          label: "Properties",
          icon: "solar:buildings-line-duotone",
          children: [
            { label: "Add Property", href: "/admin/properties/create", isNew: true },
            { label: "All Properties", href: "/admin/properties" },
            { label: "For Sale", href: "/admin/properties?listing_type=sale" },
            { label: "For Rent", href: "/admin/properties?listing_type=rent" },
            { label: "Private Listings", href: "/admin/properties?listing_type=private" },
            { label: "International", href: "/admin/properties?listing_type=international" },
          ],
        },
        {
          label: "Property Types",
          icon: "solar:tag-line-duotone",
          children: [
            { label: "Add Type", href: "/admin/property-types/create" },
            { label: "All Types", href: "/admin/property-types" },
          ],
        },
        {
          label: "Amenities",
          icon: "solar:star-shine-line-duotone",
          children: [
            { label: "Add Amenity", href: "/admin/amenities/create" },
            { label: "All Amenities", href: "/admin/amenities" },
          ],
        },
      ],
    },
    {
      cap: "Projects",
      items: [
        {
          label: "Developments",
          icon: "solar:city-line-duotone",
          children: [
            { label: "Add Development", href: "/admin/developments/create", isNew: true },
            { label: "All Developments", href: "/admin/developments" },
          ],
        },
        {
          label: "Investments",
          icon: "solar:chart-line-duotone",
          children: [
            { label: "Add Investment", href: "/admin/investments/create" },
            { label: "All Investments", href: "/admin/investments" },
          ],
        },
        {
          label: "Branded Residences",
          icon: "solar:crown-line-duotone",
          children: [
            { label: "Add Residence", href: "/admin/branded-residences/create" },
            { label: "All Residences", href: "/admin/branded-residences" },
          ],
        },
      ],
    },
    {
      cap: "Locations",
      items: [
        {
          label: "Communities",
          icon: "solar:map-point-wave-line-duotone",
          children: [
            { label: "Add Community", href: "/admin/communities/create", isNew: true },
            { label: "All Communities", href: "/admin/communities" },
          ],
        },
      ],
    },
    {
      cap: "Leads & CRM",
      items: [
        {
          label: "Inquiries",
          icon: "solar:inbox-line-line-duotone",
          badge: newInquiries,
          children: [
            { label: "All Inquiries", href: "/admin/inquiries" },
            { label: "New", href: "/admin/inquiries?status=new" },
            { label: "Contacted", href: "/admin/inquiries?status=contacted" },
            { label: "Qualified", href: "/admin/inquiries?status=qualified" },
            { label: "Closed", href: "/admin/inquiries?status=closed" },
          ],
        },
      ],
    },
    {
      cap: "Media & Content",
      items: [
        {
          label: "Recognitions",
          icon: "solar:medal-ribbons-star-line-duotone",
          children: [
            { label: "Add Recognition", href: "/admin/recognitions/create" },
            { label: "All Recognitions", href: "/admin/recognitions" },
          ],
        },
      ],
    },
    {
      cap: "Configuration",
      items: [
        {
          label: "Site Settings",
          icon: "solar:settings-line-duotone",
          children: [
            { label: "General", href: "/admin/settings?group=general" },
            { label: "Contact Info", href: "/admin/settings?group=contact" },
            { label: "Social Links", href: "/admin/settings?group=social" },
            { label: "Homepage", href: "/admin/settings?group=homepage" },
          ],
        },
        { label: "My Profile", icon: "solar:user-circle-line-duotone", href: "/admin/profile" },
      ],
    },
  ];
}

export function Sidebar({ newInquiries, onToggle }: { newInquiries: number; onToggle: () => void }) {
  const pathname = usePathname();
  const sections = menu(newInquiries);
  const base = (href: string) => href.split("?")[0];

  // Open the group that contains the current page.
  const initial = sections.flatMap((s) => s.items).find(
    (item) => isGroup(item) && item.children.some((c) => base(c.href) !== "/admin" && pathname.startsWith(base(c.href))),
  );
  const [open, setOpen] = useState<string | null>(initial?.label ?? null);

  return (
    <aside className="left-sidebar">
      <div>
        <div className="brand-logo d-flex align-items-center justify-content-between">
          <Link href="/admin" className="text-nowrap logo-img">
            <img src="/public/logo-quadrant.png" alt="" style={{ width: 200, height: "auto" }} />
          </Link>
          <div className="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse" onClick={onToggle}>
            <i className="ti ti-x fs-8" />
          </div>
        </div>

        <nav className="sidebar-nav scroll-sidebar" style={{ overflowY: "auto" }}>
          <ul id="sidebarnav">
            {sections.map((section) => (
              <SectionItems key={section.cap} section={section} open={open} setOpen={setOpen} pathname={pathname} />
            ))}
            <li>
              <center>
                <form action={signOut}>
                  <button type="submit" className="btn btn-primary">
                    Logout
                  </button>
                </form>
              </center>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
  );
}

function SectionItems({
  section,
  open,
  setOpen,
  pathname,
}: {
  section: Section;
  open: string | null;
  setOpen: (label: string | null) => void;
  pathname: string;
}) {
  return (
    <>
      <li className="nav-small-cap">
        <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4" />
        <span className="hide-menu">{section.cap}</span>
      </li>

      {section.items.map((item) =>
        isGroup(item) ? (
          <li className={`sidebar-item${open === item.label ? " selected" : ""}`} key={item.label}>
            <a
              className={`sidebar-link justify-content-between has-arrow${open === item.label ? " active" : ""}`}
              href="#"
              aria-expanded={open === item.label}
              onClick={(e) => {
                e.preventDefault();
                setOpen(open === item.label ? null : item.label);
              }}
            >
              <div className="d-flex align-items-center gap-3">
                <span className="d-flex">
                  <iconify-icon icon={item.icon} />
                </span>
                <span className="hide-menu">{item.label}</span>
              </div>
              {!!item.badge && item.badge > 0 && <span className="badge bg-danger rounded-pill ms-auto">{item.badge}</span>}
            </a>
            <ul className={`collapse first-level${open === item.label ? " in show" : ""}`}>
              {item.children.map((child) => (
                <li className="sidebar-item" key={child.href}>
                  <Link className={`sidebar-link${child.isNew ? " justify-content-between" : ""}`} href={child.href}>
                    <div className="d-flex align-items-center gap-3">
                      <span className="d-flex">
                        <span className="icon-small" />
                      </span>
                      <span className="hide-menu">{child.label}</span>
                    </div>
                    {child.isNew && <span className="hide-menu badge bg-success-subtle text-success fs-1 py-1">New</span>}
                  </Link>
                </li>
              ))}
            </ul>
          </li>
        ) : (
          <li className="sidebar-item" key={item.href}>
            <Link className={`sidebar-link${pathname === item.href ? " active" : ""}`} href={item.href} aria-expanded="false">
              <iconify-icon icon={item.icon} />
              <span className="hide-menu">{item.label}</span>
            </Link>
          </li>
        ),
      )}
    </>
  );
}
