import "server-only";
import mysql, { type Pool, type ResultSetHeader, type RowDataPacket } from "mysql2/promise";

// One pool per server process (reused across hot reloads in dev).
const globalForDb = globalThis as unknown as { mysqlPool?: Pool };

function createPool(): Pool {
  return mysql.createPool({
    host: process.env.DB_HOST ?? "127.0.0.1",
    port: Number(process.env.DB_PORT ?? 3306),
    database: process.env.DB_DATABASE ?? "quadrant",
    user: process.env.DB_USERNAME ?? "root",
    password: process.env.DB_PASSWORD ?? "",
    connectionLimit: 10,
    charset: "utf8mb4",
    // DECIMAL columns come back as numbers, dates as JS Date objects.
    decimalNumbers: true,
    dateStrings: false,
    // Laravel ran in UTC (config/app.php timezone): timestamps are stored and read as UTC.
    timezone: "Z",
  });
}

export const pool: Pool = globalForDb.mysqlPool ?? createPool();
if (process.env.NODE_ENV !== "production") globalForDb.mysqlPool = pool;

export type Params = (string | number | boolean | null | Date)[];

// ---------------------------------------------------------------------------
// SQLite fallback (same rule as the Laravel site's api/index.php): on Vercel, unless USE_MYSQL=1,
// the site reads the bundled copy database/quadrant.sqlite. It is copied to /tmp because SQLite
// needs a writable location; writes there are not permanent.
// ---------------------------------------------------------------------------

type SqliteStatement = {
  all(...params: unknown[]): Record<string, unknown>[];
  run(...params: unknown[]): { changes: number | bigint; lastInsertRowid: number | bigint };
};
type SqliteDb = {
  prepare(sql: string): SqliteStatement;
  function(name: string, fn: (...args: never[]) => unknown): void;
};

const useSqlite =
  process.env.USE_MYSQL !== "1" && (process.env.VERCEL === "1" || process.env.DB_CONNECTION === "sqlite");

let sqliteDb: SqliteDb | null = null;

function sqlite(): SqliteDb {
  if (sqliteDb) return sqliteDb;
  const fs = process.getBuiltinModule("node:fs") as typeof import("node:fs");
  const path = process.getBuiltinModule("node:path") as typeof import("node:path");
  const os = process.getBuiltinModule("node:os") as typeof import("node:os");
  // Loaded at runtime so the bundler leaves the built-in module alone.
  const { DatabaseSync } = process.getBuiltinModule("node:sqlite") as {
    DatabaseSync: new (file: string) => SqliteDb;
  };
  const file = path.join(os.tmpdir(), "quadrant.sqlite");
  if (!fs.existsSync(file)) fs.copyFileSync(path.join(process.cwd(), "database", "quadrant.sqlite"), file);
  const db = new DatabaseSync(file);
  // MySQL functions used by the queries.
  db.function("YEAR", (value: string | null) => (value ? Number(String(value).slice(0, 4)) : null));
  db.function("DATE_FORMAT", (value: string | null, format: string) => (value ? mysqlDateFormat(value, format) : null));
  sqliteDb = db;
  return db;
}

/** The DATE_FORMAT() specifiers the queries use: %Y %m %d %H %i %s. */
function mysqlDateFormat(value: string, format: string): string {
  const [date = "", time = "00:00:00"] = String(value).split(" ");
  const [Y = "", m = "", d = ""] = date.split("-");
  const [H = "00", i = "00", s = "00"] = time.split(":");
  const parts: Record<string, string> = { Y, m, d, H, i, s: s.slice(0, 2) };
  return format.replace(/%([YmdHis])/g, (_, k: string) => parts[k]);
}

/** SQLite takes no Date or boolean values: store dates as "Y-m-d H:i:s" (UTC), like Laravel did. */
function sqliteParam(value: Params[number] | undefined): string | number | null {
  if (value === undefined || value === null) return null;
  if (value instanceof Date) return value.toISOString().slice(0, 19).replace("T", " ");
  if (typeof value === "boolean") return value ? 1 : 0;
  return value;
}

/** SQLite returns dates as text; turn *_at / *_date columns into Date objects like mysql2 does. */
function sqliteRow(row: Record<string, unknown>): Record<string, unknown> {
  const out: Record<string, unknown> = { ...row };
  for (const [key, value] of Object.entries(out)) {
    if (typeof value !== "string" || !/(_at|_date)$/.test(key)) continue;
    const m = value.match(/^(\d{4}-\d{2}-\d{2})(?:[ T](\d{2}:\d{2}:\d{2}))?/);
    if (m) out[key] = new Date(`${m[1]}T${m[2] ?? "00:00:00"}Z`);
  }
  return out;
}

/** Run a SELECT and return all rows. Always pass values through `params` (never concatenate). */
export async function query<T = Record<string, unknown>>(sql: string, params: Params = []): Promise<T[]> {
  if (useSqlite) return sqlite().prepare(sql).all(...params.map(sqliteParam)).map(sqliteRow) as T[];
  const [rows] = await pool.query<RowDataPacket[]>(sql, params);
  return rows as unknown as T[];
}

/** Run a SELECT and return the first row, or null. */
export async function queryOne<T = Record<string, unknown>>(sql: string, params: Params = []): Promise<T | null> {
  const rows = await query<T>(sql, params);
  return rows[0] ?? null;
}

/** Run an INSERT / UPDATE / DELETE. */
export async function execute(sql: string, params: Params = []): Promise<ResultSetHeader> {
  if (useSqlite) {
    const result = sqlite().prepare(sql).run(...params.map(sqliteParam));
    return { insertId: Number(result.lastInsertRowid), affectedRows: Number(result.changes) } as ResultSetHeader;
  }
  const [result] = await pool.execute<ResultSetHeader>(sql, params.map((p) => (p === undefined ? null : p)));
  return result;
}

/** INSERT a row from an object and return the new id. Adds created_at/updated_at like Laravel's query builder did. */
export async function insert(table: string, data: Record<string, unknown>, timestamps = true): Promise<number> {
  const row = timestamps ? { ...data, created_at: new Date(), updated_at: new Date() } : data;
  const cols = Object.keys(row);
  const sql = `INSERT INTO \`${table}\` (${cols.map((c) => `\`${c}\``).join(", ")}) VALUES (${cols.map(() => "?").join(", ")})`;
  const result = await execute(sql, cols.map((c) => normalize(row[c])));
  return result.insertId;
}

/** UPDATE a row by id from an object. Sets updated_at. */
export async function update(table: string, id: number, data: Record<string, unknown>, timestamps = true): Promise<void> {
  const row = timestamps ? { ...data, updated_at: new Date() } : data;
  const cols = Object.keys(row);
  if (cols.length === 0) return;
  const sql = `UPDATE \`${table}\` SET ${cols.map((c) => `\`${c}\` = ?`).join(", ")} WHERE id = ?`;
  await execute(sql, [...cols.map((c) => normalize(row[c])), id]);
}

/** DELETE a row by id. */
export async function remove(table: string, id: number): Promise<void> {
  await execute(`DELETE FROM \`${table}\` WHERE id = ?`, [id]);
}

function normalize(value: unknown): Params[number] {
  if (value === undefined) return null;
  if (typeof value === "boolean") return value ? 1 : 0;
  return value as Params[number];
}

// ---------------------------------------------------------------------------
// Pagination (mirrors Laravel's paginate(n)->withQueryString())
// ---------------------------------------------------------------------------

export type Paginated<T> = {
  data: T[];
  total: number;
  perPage: number;
  currentPage: number;
  lastPage: number;
};

/**
 * Paginate a query. `sql` must be a SELECT without LIMIT; `countSql` must return a single `total` column.
 */
export async function paginate<T>(
  sql: string,
  countSql: string,
  params: Params,
  page: number,
  perPage = 12,
): Promise<Paginated<T>> {
  const current = Math.max(1, Math.floor(page) || 1);
  const [rows, countRow] = await Promise.all([
    query<T>(`${sql} LIMIT ? OFFSET ?`, [...params, perPage, (current - 1) * perPage]),
    queryOne<{ total: number }>(countSql, params),
  ]);
  const total = Number(countRow?.total ?? 0);
  return { data: rows, total, perPage, currentPage: current, lastPage: Math.max(1, Math.ceil(total / perPage)) };
}
