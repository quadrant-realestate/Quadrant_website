"use client";

import { useState } from "react";
import { FallbackImg } from "../../_components/FallbackImg";

const PLACEHOLDER = "/public/assets/img/placeholder.jpg";

/** 1. Hero gallery carousel (was qpHeroGoTo / qpHeroMove inline script). */
export function HeroGallery({
  images,
  alt,
  children,
}: {
  images: string[];
  alt: string;
  /** The overlay with status tag + title. */
  children: React.ReactNode;
}) {
  const [index, setIndex] = useState(0);
  const [moved, setMoved] = useState(false);
  const count = images.length;

  const goTo = (i: number) => {
    setIndex(i);
    setMoved(true);
  };
  const move = (dir: number) => goTo((index + dir + count) % count);

  return (
    <section className="qp-pd-hero" id="qp-pd-hero">
      <div
        className="qp-pd-hero-track"
        id="qp-pd-hero-track"
        style={moved ? { transform: `translateX(-${index * 100}%)` } : undefined}
      >
        {images.map((src, i) => (
          <div className="qp-pd-hero-slide" key={i}>
            <FallbackImg
              {...(i >= 3 ? { loading: "lazy", decoding: "async" } : {})}
              src={src}
              alt={alt}
              fallback={PLACEHOLDER}
            />
          </div>
        ))}
      </div>

      {count > 1 && (
        <>
          <div className="qp-pd-hero-nav qp-pd-hero-prev" onClick={() => move(-1)}>
            &#8249;
          </div>
          <div className="qp-pd-hero-nav qp-pd-hero-next" onClick={() => move(1)}>
            &#8250;
          </div>
          <div className="qp-pd-hero-dots" id="qp-pd-hero-dots">
            {images.map((_, i) => (
              <span key={i} className={i === index ? "active" : ""} onClick={() => goTo(i)} />
            ))}
          </div>
        </>
      )}

      {children}
    </section>
  );
}
