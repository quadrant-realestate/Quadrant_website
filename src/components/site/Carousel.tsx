"use client";

import useEmblaCarousel from "embla-carousel-react";
import { Children, useCallback, useEffect, useState } from "react";

type Props = {
  children: React.ReactNode;
  /** Items visible per breakpoint (min-width in px → items), like Owl's `responsive` option. */
  responsive?: Record<number, number>;
  /** Gap between items in px (Owl `margin`). */
  margin?: number;
  loop?: boolean;
  nav?: boolean;
  dots?: boolean;
  autoplay?: boolean | number;
  id?: string;
  className?: string;
};

/**
 * Replacement for the Owl Carousel sliders. Renders Owl's class names
 * (owl-carousel / owl-nav / owl-dots) so the theme CSS still styles it.
 */
export function Carousel({
  children,
  responsive = { 0: 1 },
  margin = 20,
  loop = false,
  nav = true,
  dots = false,
  autoplay = false,
  id,
  className = "",
}: Props) {
  const [emblaRef, embla] = useEmblaCarousel({ loop, align: "start" });
  const [selected, setSelected] = useState(0);
  const [snaps, setSnaps] = useState<number[]>([]);
  const [width, setWidth] = useState<number | null>(null);

  useEffect(() => {
    const onResize = () => setWidth(window.innerWidth);
    onResize();
    window.addEventListener("resize", onResize);
    return () => window.removeEventListener("resize", onResize);
  }, []);

  const breakpoints = Object.keys(responsive).map(Number).sort((a, b) => a - b);
  const active = breakpoints.filter((bp) => (width ?? 0) >= bp).pop() ?? breakpoints[0];
  const items = responsive[active] ?? 1;

  const onSelect = useCallback(() => {
    if (!embla) return;
    setSelected(embla.selectedScrollSnap());
    setSnaps(embla.scrollSnapList());
  }, [embla]);

  useEffect(() => {
    if (!embla) return;
    onSelect();
    embla.on("select", onSelect).on("reInit", onSelect);
    return () => {
      embla.off("select", onSelect).off("reInit", onSelect);
    };
  }, [embla, onSelect]);

  useEffect(() => {
    embla?.reInit();
  }, [embla, items]);

  useEffect(() => {
    if (!embla || !autoplay) return;
    const timer = setInterval(() => embla.scrollNext(), typeof autoplay === "number" ? autoplay : 5000);
    return () => clearInterval(timer);
  }, [embla, autoplay]);

  const slides = Children.toArray(children);

  return (
    <div id={id} className={`owl-carousel owl-theme owl-loaded owl-drag ${className}`} style={{ display: "block" }}>
      <div className="owl-stage-outer" ref={emblaRef} style={{ overflow: "hidden" }}>
        <div className="owl-stage" style={{ display: "flex", marginLeft: -margin }}>
          {slides.map((slide, i) => (
            <div
              className="owl-item active"
              key={i}
              style={{ flex: `0 0 ${100 / items}%`, minWidth: 0, paddingLeft: margin }}
            >
              {slide}
            </div>
          ))}
        </div>
      </div>
      {nav && (
        <div className="owl-nav">
          <button type="button" role="presentation" className="owl-prev" onClick={() => embla?.scrollPrev()}>
            <span aria-label="Previous">‹</span>
          </button>
          <button type="button" role="presentation" className="owl-next" onClick={() => embla?.scrollNext()}>
            <span aria-label="Next">›</span>
          </button>
        </div>
      )}
      {dots && snaps.length > 1 && (
        <div className="owl-dots">
          {snaps.map((_, i) => (
            <button
              type="button"
              role="button"
              key={i}
              className={`owl-dot${i === selected ? " active" : ""}`}
              onClick={() => embla?.scrollTo(i)}
            >
              <span />
            </button>
          ))}
        </div>
      )}
    </div>
  );
}
