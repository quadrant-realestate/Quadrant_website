"use client";

import { createContext, useContext, useState } from "react";

// The "Filters" button toggles .qp-open on #qp-filter-panel (was an inline onclick).
const FilterContext = createContext<{ open: boolean; toggle: () => void }>({ open: false, toggle: () => undefined });

export function FilterProvider({ initialOpen, children }: { initialOpen: boolean; children: React.ReactNode }) {
  const [open, setOpen] = useState(initialOpen);
  return <FilterContext.Provider value={{ open, toggle: () => setOpen((o) => !o) }}>{children}</FilterContext.Provider>;
}

export function FilterToggle() {
  const { toggle } = useContext(FilterContext);
  return (
    <button type="button" className="qp-filter-toggle" onClick={toggle}>
      Filters
    </button>
  );
}

export function FilterPanel({ children }: { children: React.ReactNode }) {
  const { open } = useContext(FilterContext);
  return (
    <div className={`qp-filter-panel ${open ? " qp-open " : ""}`} id="qp-filter-panel">
      {children}
    </div>
  );
}

/** Sort dropdown — submits its form on change. */
export function SortSelect({ value }: { value: string }) {
  return (
    <select
      name="sort"
      className="qp-sort-select"
      defaultValue={value}
      onChange={(e) => e.currentTarget.form?.submit()}
    >
      <option value="newest">Newest</option>
      <option value="price_asc">Price (Low–High)</option>
      <option value="price_desc">Price (High–Low)</option>
      <option value="handover_soonest">Handover (Soonest)</option>
      <option value="featured">Featured</option>
    </select>
  );
}
