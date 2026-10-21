"use client";

import { useEffect, useRef } from "react";

type Props = React.ImgHTMLAttributes<HTMLImageElement> & {
  /** onerror="this.onerror=null; this.src='...'" */
  fallbackSrc: string;
};

/**
 * <img> with the Blade templates' inline onerror fallback. Also checks after hydration,
 * because an image can fail before React has attached its error listener.
 */
export function FallbackImg({ fallbackSrc, ...props }: Props) {
  const ref = useRef<HTMLImageElement>(null);
  const handled = useRef(false);

  useEffect(() => {
    const img = ref.current;
    if (img && !handled.current && img.complete && img.naturalWidth === 0 && img.getAttribute("src")) {
      handled.current = true;
      img.src = fallbackSrc;
    }
  }, [fallbackSrc]);

  return (
    // eslint-disable-next-line jsx-a11y/alt-text
    <img
      ref={ref}
      {...props}
      onError={(e) => {
        if (handled.current) return;
        handled.current = true;
        e.currentTarget.src = fallbackSrc;
      }}
    />
  );
}
