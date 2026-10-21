"use client";

import { useState } from "react";
import YARLightbox from "yet-another-react-lightbox";
import "yet-another-react-lightbox/styles.css";
import { FallbackImg } from "../../_components/FallbackImg";

/**
 * Gallery grid (#aniimated-thumbnials) with the lightGallery behaviour: clicking any image opens
 * a full-screen viewer. Own component instead of LightboxLink because the grid CSS positions the
 * links by id (#gallery-item-1 … #gallery-item-5), which LightboxLink can't render.
 */
export function PropertyGallery({
  title,
  mainImage,
  images,
}: {
  title: string;
  mainImage: string | null;
  images: { id: number; src: string }[];
}) {
  const [index, setIndex] = useState(-1);
  const slides = [...(mainImage ? [mainImage] : []), ...images.map((i) => i.src)];
  const offset = mainImage ? 1 : 0;

  const open = (i: number) => (e: React.MouseEvent) => {
    e.preventDefault();
    setIndex(i);
  };

  return (
    <>
      <div className="gallery-grid" id="aniimated-thumbnials">
        {mainImage && (
          <a href={mainImage} id="gallery-item-1" onClick={open(0)}>
            <div className="Big_Gallery">
              <FallbackImg loading="lazy" decoding="async" alt={title} src={mainImage} className="img-fluid lazy-imgs" />
            </div>
          </a>
        )}

        {images.map((image, i) => (
          <a
            key={image.id}
            href={image.src}
            className={`${i + 2} gallery-item-${i + 2}`}
            id={`gallery-item-${i + 2}`}
            onClick={open(i + offset)}
          >
            <div className="Small_Gallery">
              <FallbackImg loading="lazy" decoding="async" src={image.src} className="img-fluid" alt={`${title}-${i + 1}`} />
            </div>
          </a>
        ))}
      </div>
      <YARLightbox
        open={index >= 0}
        index={Math.max(index, 0)}
        close={() => setIndex(-1)}
        slides={slides.map((src) => ({ src }))}
      />
    </>
  );
}
