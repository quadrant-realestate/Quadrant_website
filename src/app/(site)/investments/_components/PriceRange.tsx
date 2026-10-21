"use client";

import { useRef } from "react";

const GAP = 1000;
const MAX = 1000000000;

/** The filter sidebar's price range slider — same behaviour as the "Price Range Slider" block in main43a0.js. */
export function PriceRange({
  rangeMin,
  rangeMax,
  inputMin,
  inputMax,
}: {
  rangeMin: string;
  rangeMax: string;
  inputMin: string;
  inputMax: string;
}) {
  const rangeMinRef = useRef<HTMLInputElement>(null);
  const rangeMaxRef = useRef<HTMLInputElement>(null);
  const inputMinRef = useRef<HTMLInputElement>(null);
  const inputMaxRef = useRef<HTMLInputElement>(null);
  const progressRef = useRef<HTMLDivElement>(null);

  const syncRange = (min: number, max: number) => {
    inputMinRef.current!.value = String(min);
    inputMaxRef.current!.value = String(max);
    progressRef.current!.style.left = `${(min / MAX) * 100}%`;
    progressRef.current!.style.right = `${100 - (max / MAX) * 100}%`;
  };

  const onPriceInput = (i: number) => {
    const min = +inputMinRef.current!.value;
    const max = +inputMaxRef.current!.value;
    if (max - min >= GAP && max <= MAX) {
      (i === 0 ? rangeMinRef : rangeMaxRef).current!.value = String(i === 0 ? min : max);
      syncRange(min, max);
    }
  };

  const onRangeInput = (isMin: boolean) => {
    const min = +rangeMinRef.current!.value;
    const max = +rangeMaxRef.current!.value;
    if (max - min < GAP) {
      if (isMin) rangeMinRef.current!.value = String(max - GAP);
      else rangeMaxRef.current!.value = String(min + GAP);
    } else syncRange(min, max);
  };

  return (
    <div className="d-flex">
      <div className="wrapper">
        <div className="slider">
          <div className="progress" ref={progressRef} />
        </div>
        <div className="range-input">
          <input
            type="range"
            className="range-min"
            min="0"
            max={MAX}
            defaultValue={rangeMin}
            step="1000000"
            ref={rangeMinRef}
            onInput={() => onRangeInput(true)}
          />
          <input
            type="range"
            className="range-max"
            min="0"
            max={MAX}
            defaultValue={rangeMax}
            step="1000000"
            ref={rangeMaxRef}
            onInput={() => onRangeInput(false)}
          />
        </div>
        <div className="price-input">
          <div className="field">
            <span>Min</span>
            <input
              type="number"
              defaultValue={inputMin}
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
              defaultValue={inputMax}
              className="input-max"
              name="max_price"
              ref={inputMaxRef}
              onInput={() => onPriceInput(1)}
            />
          </div>
        </div>
      </div>
    </div>
  );
}
