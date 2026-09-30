<?php

// Vercel entry point. The deployed filesystem is read-only except /tmp,
// so Laravel's compiled views and caches are redirected there.

$tmp = '/tmp/laravel';
foreach (['views', 'cache'] as $dir) {
    if (! is_dir("$tmp/$dir")) {
        mkdir("$tmp/$dir", 0755, true);
    }
}

// Fallback when APP_KEY isn't set in Vercel: generate a random key per instance
// so the public site still loads. Set APP_KEY in Vercel to keep sessions/forms
// stable across instances.
if (! getenv('APP_KEY')) {
    $keyFile = "$tmp/app.key";
    if (! file_exists($keyFile)) {
        file_put_contents($keyFile, 'base64:'.base64_encode(random_bytes(32)));
    }
    $key = file_get_contents($keyFile);
    putenv("APP_KEY=$key");
    $_ENV['APP_KEY'] = $_SERVER['APP_KEY'] = $key;
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
