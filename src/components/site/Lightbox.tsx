"use client";

import { createContext, useContext, useState } from "react";
import YARLightbox from "yet-another-react-lightbox";
import "yet-another-react-lightbox/styles.css";

/**
 * Replacement for lightGallery: wrap a gallery in <LightboxGallery images={[...]}>
 * and use <LightboxLink index={n}> around each thumbnail. Clicking opens a full-screen viewer.
 */
const LightboxContext = createContext<(index: number) => void>(() => undefined);

export function LightboxGallery({
  images,
  children,
  id,
  className,
  style,
}: {
  images: string[];
  children: React.ReactNode;
  id?: string;
  className?: string;
  style?: React.CSSProperties;
}) {
  const [index, setIndex] = useState(-1);

  return (
    <LightboxContext.Provider value={setIndex}>
      <div id={id} className={className} style={style}>
        {children}
      </div>
      <YARLightbox
        open={index >= 0}
        index={Math.max(index, 0)}
        close={() => setIndex(-1)}
        slides={images.map((src) => ({ src }))}
      />
    </LightboxContext.Provider>
  );
}

export function LightboxLink({
  index,
  href,
  children,
  className,
  style,
}: {
  index: number;
  href: string;
  children: React.ReactNode;
  className?: string;
  style?: React.CSSProperties;
}) {
  const open = useContext(LightboxContext);
  return (
    <a
      href={href}
      className={className}
      style={style}
      onClick={(e) => {
        e.preventDefault();
        open(index);
      }}
    >
      {children}
    </a>
  );
}
