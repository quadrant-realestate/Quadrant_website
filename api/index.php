<?php

// Vercel entry point. The deployed filesystem is read-only except /tmp,
// so Laravel's compiled views and caches are redirected there.

$tmp = '/tmp/laravel';
foreach (['views', 'cache'] as $dir) {
    if (! is_dir("$tmp/$dir")) {
        mkdir("$tmp/$dir", 0755, true);
    }
}

// Unless USE_MYSQL=1 is set in Vercel, serve the site from the bundled SQLite
// copy of the content (database/quadrant.sqlite). It is copied to /tmp because
// SQLite needs a writable location; writes there are not permanent.
if (getenv('USE_MYSQL') !== '1') {
    $db = "$tmp/quadrant.sqlite";
    if (! file_exists($db)) {
        copy(__DIR__.'/../database/quadrant.sqlite', $db);
    }
    foreach (['DB_CONNECTION' => 'sqlite', 'DB_DATABASE' => $db, 'DB_FOREIGN_KEYS' => 'false'] as $key => $value) {
        putenv("$key=$value");
        $_ENV[$key] = $_SERVER[$key] = $value;
    }
}

require __DIR__.'/../index.php';
