import type { AnchorHTMLAttributes } from "react";

/**
 * Plain <a> link for the public site. Like the Laravel site, every click is a full page load,
 * so each page only ever has its own stylesheet (page CSS files reuse the theme's class names
 * and would otherwise leak into the next page after client-side navigation).
 */
export default function Link({ href, ...props }: AnchorHTMLAttributes<HTMLAnchorElement> & { href: string }) {
  return <a href={href} {...props} />;
}
