import type { DetailedHTMLProps, HTMLAttributes } from "react";

// <iconify-icon> web component used by the admin theme.
declare module "react" {
  namespace JSX {
    interface IntrinsicElements {
      "iconify-icon": DetailedHTMLProps<HTMLAttributes<HTMLElement>, HTMLElement> & {
        icon: string;
        class?: string;
        width?: string | number;
        height?: string | number;
      };
    }
  }
}
