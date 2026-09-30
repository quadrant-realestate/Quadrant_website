# Quadrant Website                  

Quadrant Real Estate website + admin panel, built with **Laravel 10 (PHP 8.1+) and MySQL**.

> Note: the site is served from the **project root** (not `public/`) — asset URLs look like `/public/assets/...`.
> That's why local dev uses `server.php` instead of `php artisan serve`.             

---

## 1. Run it locally (Windows)
                    
### What you need         
| Tool | Easiest way to get it |
|------|-----------------------|
| PHP 8.1+ **and** MySQL | Install **[Laragon](https://laragon.org/download/)** or **[XAMPP](https://www.apachefriends.org/)** (both include PHP + MySQL) |
| Composer | https://getcomposer.org/Composer-Setup.exe |

Check they work: open a new terminal and run `php -v` and `composer -V`.

### The database
The database tables are **not** in this repo (there are no migrations for them).
You need a copy of the live database:

1. Log in to the hosting cPanel → **phpMyAdmin** → select the site database → **Export** → *Quick* → SQL → Go.
2. Start MySQL (Laragon: *Start All*, XAMPP: start *MySQL*).
3. Create an empty database called `quadrant` and **Import** the `.sql` file into it
   (phpMyAdmin at http://localhost/phpmyadmin, or `mysql -u root quadrant < dump.sql`).

**No access to the live database?** Load the empty structure instead — the site then runs with no listings,
and you can add content through the admin panel:
```bash
mysql -u root quadrant < database/local-dev-schema.sql
php artisan migrate
```
Then create an admin login (password is stored as SHA1):
```sql
INSERT INTO admins (name, email, password) VALUES ('Admin', 'admin@quadrant.local', SHA1('admin12345'));
```
Admin panel: http://localhost:8000/admin/sign-in

### First-time setup
Run these in the project folder:

```bash
composer install
copy .env.example .env        # (Git Bash / Mac: cp .env.example .env)
php artisan key:generate
```

Open `.env` and check the `DB_*` lines match your local MySQL (default: user `root`, no password, database `quadrant`).

### Start the site
```bash
php -S localhost:8000 server.php
```
Open **http://localhost:8000** 🎉 (or just double-click `start-local.bat`)

> The PHP dev server handles one request at a time, so while the homepage's big background video
> is loading, other clicks can feel slow. That's normal locally — it's fine on real hosting.

---

## 2. Deploy to Vercel

Vercel doesn't run PHP natively, so this project uses the community
[`vercel-php`](https://github.com/vercel-community/php) runtime. Everything is already configured:

| File | What it does |
|------|--------------|
| `vercel.json` | Runs `api/index.php` with PHP, serves real files (images, CSS, JS) directly, and points Laravel's caches/logs at writable places |
| `api/index.php` | Vercel entry point → loads the normal `index.php` |
| `.vercelignore` | Keeps `.env`, `vendor`, `node_modules` out of the upload |

### Steps
1. Go to https://vercel.com/new → **Import** the `Quadrant-website` GitHub repo.
2. Framework preset: **Other**. Leave build/output settings empty.
3. Add **Environment Variables** (Settings → Environment Variables):

   | Name | Value |
   |------|-------|
   | `APP_KEY` | the `APP_KEY` from your `.env` (starts with `base64:`) |
   | `APP_URL` | your Vercel URL, e.g. `https://quadrant-website.vercel.app` |
   | `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | an **online** MySQL database (see below) |

4. Click **Deploy**.

### ⚠️ Important limits on Vercel
- **Database must be online.** Vercel can't reach `127.0.0.1`. Use a hosted MySQL (e.g. allow *Remote MySQL* on the current cPanel host, or use PlanetScale / Aiven / Railway) and import the same `.sql` dump there.
- **Admin uploads won't be saved.** Vercel's disk is read-only/temporary, so images uploaded through the admin panel disappear. The public site works; for uploads on Vercel the code must be changed to store files in cloud storage (S3 / Vercel Blob / Cloudinary).
- **Big media files.** `public/` is ~2.5 GB (some videos are 40–80 MB). If the deploy is rejected for size, move the large videos to a CDN/YouTube and link them.
- Sessions use cookies and logs go to the Vercel **Logs** tab.
