"use client";

import { useEffect, useRef } from "react";

/**
 * Home hero background video (autoplay, loop, muted, playsinline).
 * React does not write the `muted` attribute into server HTML, which browsers
 * need for autoplay, so it is set on the element and playback started here.
 */
export function HeroVideo() {
  const ref = useRef<HTMLVideoElement>(null);

  useEffect(() => {
    const video = ref.current;
    if (!video) return;
    video.muted = true;
    video.play().catch(() => undefined);
  }, []);

  return (
    <video
      ref={ref}
      className="qp-hero-bg"
      autoPlay
      loop
      muted
      playsInline
      preload="auto"
      poster="/public/assets/images/quadrant-hero-dubai.jpg"
      style={{ position: "absolute", inset: 0, width: "100%", height: "100%", objectFit: "cover", zIndex: 0 }}
    >
      <source src="/public/assets/video/home-video-web.mp4" type="video/mp4" />
      {/* Fallback image if browser doesn't support video */}
      <img src="/public/assets/images/quadrant-hero-dubai.jpg" alt="Dubai skyline" />
    </video>
  );
}
