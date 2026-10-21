"use client";

import { useEffect } from "react";

/** Adds a class to <body> while the page is mounted (Blade's @section('body-class')). */
export function BodyClass({ name }: { name: string }) {
  useEffect(() => {
    document.body.classList.add(name);
    return () => document.body.classList.remove(name);
  }, [name]);
  return null;
}
