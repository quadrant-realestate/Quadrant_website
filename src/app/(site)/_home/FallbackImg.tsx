"use client";

import { useEffect, useRef } from "react";

type Props = React.ImgHTMLAttributes<HTMLImageElement> & {
  /** onerror="this.onerror=null; this.src='...'" */
  fallbackSrc?: string;
  /** onerror="this.onerror=null; this.style.display='none';" */
  hideOnError?: boolean;
};

/**
 * <img> with the Blade templates' inline onerror handlers. Also checks after hydration,
 * because an image can fail before React has attached its error listener.
 */
export function FallbackImg({ fallbackSrc, hideOnError, ...props }: Props) {
  const ref = useRef<HTMLImageElement>(null);
  const handled = useRef(false);

  const onError = () => {
    const img = ref.current;
    if (!img || handled.current) return;
    handled.current = true;
    if (fallbackSrc) img.src = fallbackSrc;
    if (hideOnError) img.style.display = "none";
  };

  useEffect(() => {
    const img = ref.current;
    if (img && img.complete && img.naturalWidth === 0 && img.getAttribute("src")) onError();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // eslint-disable-next-line jsx-a11y/alt-text
  return <img ref={ref} {...props} onError={onError} />;
}
