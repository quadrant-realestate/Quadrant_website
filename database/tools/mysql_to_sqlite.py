"""Copy the local MariaDB `quadrant` database into database/quadrant.sqlite (used by the Vercel deploy)."""
import os, sqlite3, pymysql, decimal, datetime

OUT = os.path.join(os.path.dirname(__file__), '..', 'quadrant.sqlite')
SKIP_ROWS = {'admins', 'users', 'personal_access_tokens', 'password_reset_tokens', 'failed_jobs'}  # never ship logins/tokens

src = pymysql.connect(host='127.0.0.1', user='root', password='', database='quadrant', charset='utf8mb4')
if os.path.exists(OUT): os.remove(OUT)
dst = sqlite3.connect(OUT)
cur = src.cursor()
cur.execute('SHOW TABLES')
for (table,) in cur.fetchall():
    cur.execute(f'SHOW COLUMNS FROM `{table}`')
    cols = cur.fetchall()
    defs = []
    for name, typ, null, key, default, extra in cols:
        t = typ.lower()
        st = 'INTEGER' if 'int' in t else 'NUMERIC' if ('decimal' in t or 'double' in t or 'float' in t) else 'TEXT'
        if key == 'PRI' and 'auto_increment' in extra:
            defs.append(f'"{name}" INTEGER PRIMARY KEY AUTOINCREMENT')
            continue
        d = f' DEFAULT {default!r}' if default is not None and default.upper() not in ('NULL', 'CURRENT_TIMESTAMP') else ''
        defs.append(f'"{name}" {st}{d}')
    dst.execute(f'CREATE TABLE "{table}" ({", ".join(defs)})')
    if table in SKIP_ROWS: continue
    cur.execute(f'SELECT * FROM `{table}`')
    rows = [[float(v) if isinstance(v, decimal.Decimal) else v.strftime('%Y-%m-%d %H:%M:%S') if isinstance(v, datetime.datetime) else str(v) if isinstance(v, datetime.date) else v for v in r] for r in cur.fetchall()]
    if rows:
        dst.executemany(f'INSERT INTO "{table}" VALUES ({",".join("?" * len(cols))})', rows)
dst.commit(); dst.execute('VACUUM'); dst.close()
print('written', os.path.abspath(OUT), os.path.getsize(OUT) // 1024, 'KB')
