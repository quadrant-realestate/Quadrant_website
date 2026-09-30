<?php

// Local dev router for PHP's built-in server: `php -S localhost:8000 server.php`
// The site is served from the project root (assets live under /public/...),
// so real files are returned as-is and everything else goes to Laravel.

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');

if ($uri !== '/' && is_file(__DIR__.$uri)) {
    return false;
}

require_once __DIR__.'/index.php';
