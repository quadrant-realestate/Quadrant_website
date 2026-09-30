<?php

// Vercel entry point. The deployed filesystem is read-only except /tmp,
// so Laravel's compiled views and caches are redirected there.

$tmp = '/tmp/laravel';
foreach (['views', 'cache'] as $dir) {
    if (! is_dir("$tmp/$dir")) {
        mkdir("$tmp/$dir", 0755, true);
    }
}

require __DIR__.'/../index.php';
