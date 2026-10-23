"use client";

import { useRef } from "react";

// Filter sidebar + price range slider — the openNav()/closeNav() and price-range code from main43a0.js.

function openNav() {
  const sidebar = document.getElementById("filters-sidebar");
  const overlay = document.getElementById("filter-overlay");
  if (sidebar) sidebar.style.cssText = "width:360px;right:0px;";
  if (overlay) overlay.style.display = "block";
}

function closeNav() {
  const sidebar = document.getElementById("filters-sidebar");
  const overlay = document.getElementById("filter-overlay");
  if (sidebar) sidebar.style.cssText = "width:0;right:-400px;";
  if (overlay) overlay.style.display = "none";
}

/** "Filters" button in the listing top bar. */
export function FilterTrigger() {
  return (
    <a
      href="#"
      role="button"
      onClick={(e) => {
        e.preventDefault();
        openNav();
      }}
      className="green-btn"
    >
      <img src="/public/assets/img/filter.svg" alt="Filter" /> Filters
    </a>
  );
}

/** Close (×) link at the top of the sidebar. */
export function FilterClose() {
  return (
    <a
      href="#"
      role="button"
      className="closebtn"
      onClick={(e) => {
        e.preventDefault();
        closeNav();
      }}
    >
      <img src="/public/assets/img/cross.svg" alt="Close" loading="lazy" decoding="async" />
    </a>
  );
}

/** Dark overlay behind the open sidebar; clicking it closes the sidebar. */
export function FilterOverlay() {
  return <div id="filter-overlay" onClick={closeNav} />;
}

const GAP = 1000;

/** Two-handle price range (.range-input) synced with the Min / Max number inputs (.price-input). */
export function PriceRange({
  max,
  step,
  rangeMin,
  rangeMax,
  minPrice,
  maxPrice,
}: {
  max: number;
  step: number;
  /** value attributes of the range inputs (request('min_price', 0) / request('max_price', max)) */
  rangeMin: string;
  rangeMax: string;
  /** value attributes of the number inputs (request('min_price') / request('max_price')) */
  minPrice: string;
  maxPrice: string;
}) {
  const rangeMinRef = useRef<HTMLInputElement>(null);
  const rangeMaxRef = useRef<HTMLInputElement>(null);
  const inputMinRef = useRef<HTMLInputElement>(null);
  const inputMaxRef = useRef<HTMLInputElement>(null);
  const progressRef = useRef<HTMLDivElement>(null);

  const syncRange = (min: number, maxValue: number) => {
    if (!inputMinRef.current || !inputMaxRef.current || !progressRef.current) return;
    inputMinRef.current.value = String(min);
    inputMaxRef.current.value = String(maxValue);
    progressRef.current.style.left = `${(min / max) * 100}%`;
    progressRef.current.style.right = `${100 - (maxValue / max) * 100}%`;
  };

  const onPriceInput = (i: 0 | 1) => {
    const min = Number(inputMinRef.current?.value);
    const maxValue = Number(inputMaxRef.current?.value);
    if (maxValue - min >= GAP && maxValue <= max) {
      const range = i === 0 ? rangeMinRef.current : rangeMaxRef.current;
      if (range) range.value = String(i === 0 ? min : maxValue);
      syncRange(min, maxValue);
    }
  };

  const onRangeInput = (i: 0 | 1) => {
    const lo = rangeMinRef.current;
    const hi = rangeMaxRef.current;
    if (!lo || !hi) return;
    const min = Number(lo.value);
    const maxValue = Number(hi.value);
    if (maxValue - min < GAP) {
      if (i === 0) lo.value = String(maxValue - GAP);
      else hi.value = String(min + GAP);
    } else syncRange(min, maxValue);
  };

  return (
    <div className="wrapper">
      <div className="slider">
        <div className="progress" ref={progressRef} />
      </div>
      <div className="range-input">
        <input
          type="range"
          className="range-min"
          min="0"
          max={max}
          defaultValue={rangeMin}
          step={step}
          ref={rangeMinRef}
          onInput={() => onRangeInput(0)}
        />
        <input
          type="range"
          className="range-max"
          min="0"
          max={max}
          defaultValue={rangeMax}
          step={step}
          ref={rangeMaxRef}
          onInput={() => onRangeInput(1)}
        />
      </div>
      <div className="price-input">
        <div className="field">
          <span>Min</span>
          <input
            type="number"
            defaultValue={minPrice}
            className="input-min"
            name="min_price"
            ref={inputMinRef}
            onInput={() => onPriceInput(0)}
          />
        </div>
        <div className="separator">-</div>
        <div className="field">
          <span>Max</span>
          <input
            type="number"
            defaultValue={maxPrice}
            className="input-max"
            name="max_price"
            ref={inputMaxRef}
            onInput={() => onPriceInput(1)}
          />
        </div>
      </div>
    </div>
  );
}
