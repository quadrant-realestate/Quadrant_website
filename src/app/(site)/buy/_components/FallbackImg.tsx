"use client";

import { useEffect, useRef } from "react";

/**
 * <img> with the Blade `onerror="this.onerror=null; this.src='…placeholder…'"` behaviour.
 * Also checks after hydration, for images that failed before React attached the handler.
 */
export function FallbackImg({ fallback, ...props }: React.ImgHTMLAttributes<HTMLImageElement> & { fallback: string }) {
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
    if (img && img.complete && img.naturalWidth === 0) swap();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // eslint-disable-next-line jsx-a11y/alt-text
  return <img ref={ref} {...props} onError={swap} />;
}
