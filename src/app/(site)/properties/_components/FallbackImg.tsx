"use client";

import { useEffect, useRef } from "react";

/**
 * <img> with the Blade `onerror="this.onerror=null; this.src='…/placeholder.jpg'"` fallback.
 * Also catches images that failed before React hydrated.
 */
export function FallbackImg({
  src,
  fallback = "/public/assets/img/placeholder.jpg",
  ...rest
}: React.ImgHTMLAttributes<HTMLImageElement> & { src: string; fallback?: string }) {
  const ref = useRef<HTMLImageElement>(null);
  const done = useRef(false);

  const swap = () => {
    const img = ref.current;
    if (!img || done.current) return;
    done.current = true;
    img.src = fallback;
  };

  useEffect(() => {
    const img = ref.current;
    if (img && img.complete && img.naturalWidth === 0 && img.getAttribute("src") === src) swap();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [src]);

  return <img ref={ref} src={src} onError={swap} alt={rest.alt ?? ""} {...rest} />;
}
