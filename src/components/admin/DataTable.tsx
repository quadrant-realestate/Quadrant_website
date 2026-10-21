"use client";

import { useMemo, useState } from "react";

export type DataTableRow = {
  key: string | number;
  /** Rendered cells, one per column. */
  cells: React.ReactNode[];
  /** Plain values used for sorting and searching, one per column. */
  values: (string | number | null)[];
};

type Props = {
  columns: { label: string; sortable?: boolean; className?: string }[];
  rows: DataTableRow[];
  tableClassName?: string;
  /** DataTables' default is column 0 ascending. */
  defaultSort?: { column: number; dir: "asc" | "desc" } | null;
};

const LENGTHS = [10, 25, 50, 100];

/**
 * Replacement for jQuery DataTables (`$('#myTable').DataTable()`): search, sort, page length and paging,
 * using DataTables' Bootstrap 5 class names so the existing CSS applies.
 */
export function DataTable({ columns, rows, tableClassName = "table table-hover align-middle mb-0", defaultSort = { column: 0, dir: "asc" } }: Props) {
  const [search, setSearch] = useState("");
  const [length, setLength] = useState(10);
  const [page, setPage] = useState(1);
  const [sort, setSort] = useState(defaultSort);

  const filtered = useMemo(() => {
    const q = search.trim().toLowerCase();
    const list = q ? rows.filter((r) => r.values.some((v) => String(v ?? "").toLowerCase().includes(q))) : [...rows];
    if (sort) {
      list.sort((a, b) => {
        const x = a.values[sort.column];
        const y = b.values[sort.column];
        const cmp =
          typeof x === "number" && typeof y === "number"
            ? x - y
            : String(x ?? "").localeCompare(String(y ?? ""), undefined, { numeric: true, sensitivity: "base" });
        return sort.dir === "asc" ? cmp : -cmp;
      });
    }
    return list;
  }, [rows, search, sort]);

  const pages = Math.max(1, Math.ceil(filtered.length / length));
  const current = Math.min(page, pages);
  const start = (current - 1) * length;
  const visible = filtered.slice(start, start + length);

  const toggleSort = (column: number) =>
    setSort((s) => (s?.column === column ? { column, dir: s.dir === "asc" ? "desc" : "asc" } : { column, dir: "asc" }));

  return (
    <div className="dataTables_wrapper dt-bootstrap5 no-footer">
      <div className="row">
        <div className="col-sm-12 col-md-6">
          <div className="dataTables_length">
            <label>
              Show{" "}
              <select
                className="form-select form-select-sm"
                value={length}
                onChange={(e) => {
                  setLength(Number(e.target.value));
                  setPage(1);
                }}
              >
                {LENGTHS.map((n) => (
                  <option key={n} value={n}>
                    {n}
                  </option>
                ))}
              </select>{" "}
              entries
            </label>
          </div>
        </div>
        <div className="col-sm-12 col-md-6">
          <div className="dataTables_filter">
            <label>
              Search:
              <input
                type="search"
                className="form-control form-control-sm"
                value={search}
                onChange={(e) => {
                  setSearch(e.target.value);
                  setPage(1);
                }}
              />
            </label>
          </div>
        </div>
      </div>

      <div className="row dt-row">
        <div className="col-sm-12">
          <table id="myTable" className={`${tableClassName} dataTable no-footer`}>
            <thead>
              <tr>
                {columns.map((col, i) => {
                  const sortable = col.sortable !== false;
                  const state = sort?.column === i ? (sort.dir === "asc" ? "sorting_asc" : "sorting_desc") : "sorting";
                  return (
                    <th
                      key={i}
                      className={`${col.className ?? ""} ${sortable ? state : ""}`}
                      onClick={sortable ? () => toggleSort(i) : undefined}
                      style={sortable ? { cursor: "pointer" } : undefined}
                    >
                      {col.label}
                    </th>
                  );
                })}
              </tr>
            </thead>
            <tbody>
              {visible.length === 0 ? (
                <tr className="odd">
                  <td colSpan={columns.length} className="dataTables_empty" valign="top">
                    {rows.length === 0 ? "No data available in table" : "No matching records found"}
                  </td>
                </tr>
              ) : (
                visible.map((row, i) => (
                  <tr key={row.key} className={i % 2 === 0 ? "odd" : "even"}>
                    {row.cells.map((cell, j) => (
                      <td key={j}>{cell}</td>
                    ))}
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      <div className="row">
        <div className="col-sm-12 col-md-5">
          <div className="dataTables_info" role="status" aria-live="polite">
            Showing {filtered.length === 0 ? 0 : start + 1} to {Math.min(start + length, filtered.length)} of {filtered.length} entries
            {search && filtered.length !== rows.length ? ` (filtered from ${rows.length} total entries)` : ""}
          </div>
        </div>
        <div className="col-sm-12 col-md-7">
          <div className="dataTables_paginate paging_simple_numbers">
            <ul className="pagination">
              <li className={`paginate_button page-item previous${current <= 1 ? " disabled" : ""}`}>
                <a href="#" className="page-link" onClick={(e) => { e.preventDefault(); setPage(Math.max(1, current - 1)); }}>
                  Previous
                </a>
              </li>
              {Array.from({ length: pages }, (_, i) => i + 1).map((p) => (
                <li key={p} className={`paginate_button page-item${p === current ? " active" : ""}`}>
                  <a href="#" className="page-link" onClick={(e) => { e.preventDefault(); setPage(p); }}>
                    {p}
                  </a>
                </li>
              ))}
              <li className={`paginate_button page-item next${current >= pages ? " disabled" : ""}`}>
                <a href="#" className="page-link" onClick={(e) => { e.preventDefault(); setPage(Math.min(pages, current + 1)); }}>
                  Next
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
}
