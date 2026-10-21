# Laravel → Next.js conversion guide

This project is a 1:1 conversion of the Laravel site in `../Quadrant-webiste-main/Quadrant-webiste-main`.
Same pages, same URLs, same design, same MySQL database. Blog posts stay in Sanity.

## Project layout

```
src/
  app/
    (site)/                 public website (own root layout: theme CSS, header, footer, GA)
      layout.tsx  site.css  not-found.tsx
      <route>/page.tsx      one folder per public URL (/buy, /buy/[slug], /communities ...)
    admin/                  admin panel (own root layout: admin theme)
      sign-in/              login page
      (panel)/              everything behind the login (sidebar + header layout)
        <section>/page.tsx  /admin/properties, /admin/properties/create, /admin/properties/[id]/edit ...
    public/uploads/[...path]/route.ts   serves uploaded files at their old /public/uploads/... URLs
    sitemap.xml/route.ts
  actions/                  server actions ("use server") — form submissions
    inquiries.ts            public contact + register-interest forms
    auth.ts                 admin sign in / sign out
    admin/<section>.ts      admin create / update / delete / toggle actions
  components/
    site/                   shared public components (Header, Footer, Pagination, Carousel, Lightbox, SpamTrap ...)
    admin/                  shared admin components (AdminShell, Sidebar, DataTable, AdminFlash, ConfirmButton)
  lib/
    db.ts                   MySQL: query, queryOne, execute, insert, update, remove, paginate
    settings.ts             site settings table (Laravel's setting() helper)
    queries/<domain>.ts     read queries used by public pages
    admin/<domain>.ts       read queries used by admin pages
    seo.ts                  pageMetadata() — title / description / OG / canonical like the Blade layout
    url.ts                  asset() → "/public/..." URLs, siteUrl()
    uploads.ts              saveUpload / deleteUpload (files stored as "uploads/<folder>/<time>_<name>")
    format.ts               numberFormat, strLimit, formatDate, ucfirst ... (PHP helper equivalents)
    form.ts                 str / num / int / bool / list helpers for reading FormData
    auth/                   admin session (signed cookie), requireAdmin()
    flash.ts                one-time admin messages (Laravel ->with('success'))
    mail.ts  spam.ts  sanity.ts  portable-text.ts
public/                     static theme files, served at /public/... (see rewrite in next.config.ts)
storage/uploads/            uploaded files (not in git; live copy is on the server)
```

## Rules used for the conversion

- **Markup and CSS stay the same.** Blade HTML becomes JSX with the same class names. Page-specific
  `<style>` blocks move to a CSS file next to the page (`page.css`, imported by the page) with the same selectors.
  Inline `style="..."` attributes become `style={{...}}` objects.
- **URLs stay the same**, including `/public/assets/...` and `/public/uploads/...` file URLs.
- **Server components by default.** Data is loaded with `query()` from `@/lib/db` in the page itself or
  in `src/lib/queries/*`. Only interactive parts (sliders, tabs, forms with feedback) are `"use client"`.
- **Always parameterised SQL** — values go in the `params` array, never into the SQL string.
- **jQuery / Owl Carousel / lightGallery are replaced** by React: `Carousel`, `LightboxGallery`, `ShowMore`.
- **Forms** post to server actions. Public forms use `submitContact` / `submitInquiry` with `useActionState`
  and include `<SpamTrap ts={formTimestamp()} />`. Admin forms call actions in `src/actions/admin/*`,
  which call `requireAdmin()` first, then `flash()` + `redirect()` like the Laravel controllers.
- **Admin delete/toggle links** (GET links in Laravel) became small `<form action={...}>` buttons.
- **SEO**: every page exports `generateMetadata` (or `metadata`) built with `pageMetadata()` and its own path,
  so title, description, Open Graph and canonical match the Blade layout.
- Old leftover code (shop, Stripe, orders, badminton blog templates, `*-old` files) was not converted.
