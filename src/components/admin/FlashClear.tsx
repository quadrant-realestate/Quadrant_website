"use client";

import { useEffect } from "react";
import { clearFlash } from "@/actions/flash";

export function FlashClear() {
  useEffect(() => {
    void clearFlash();
  }, []);
  return null;
}
