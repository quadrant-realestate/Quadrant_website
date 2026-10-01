<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds loading="lazy" to website images so the browser only downloads
 * them when they scroll into view. The first few images (the two header
 * logos and the page's hero image) stay eager so the page paints fast.
 */
class LazyLoadImages
{
    private const EAGER_IMAGES = 3;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET') || $request->is('admin', 'admin/*')
            || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || $content === '') {
            return $response;
        }

        $seen = 0;
        $content = preg_replace_callback('/<img\b(?![^>]*\bloading=)/i', function ($m) use (&$seen) {
            return ++$seen <= self::EAGER_IMAGES ? $m[0] : '<img loading="lazy" decoding="async"';
        }, $content);

        $response->setContent($content);

        return $response;
    }
}
