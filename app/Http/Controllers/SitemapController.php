<?php

namespace App\Http\Controllers;

use App\Services\Sanity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SitemapController extends Controller
{
    // ============================================================
    // SITEMAP — sitemap.xml for search engines
    // ============================================================
    public function index(Sanity $sanity)
    {
        $urls = [];

        // Static pages
        $static = [
            'home'                     => '1.0',
            'developments.index'       => '0.9',
            'properties.sale'          => '0.9',
            'properties.rent'          => '0.9',
            'properties.private'       => '0.7',
            'properties.international' => '0.7',
            'investments.index'        => '0.8',
            'branded-residences.index' => '0.8',
            'communities.index'        => '0.8',
            'blogs.index'              => '0.8',
            'sell'                     => '0.7',
            'about'                    => '0.7',
            'services'                 => '0.7',
            'giving'                   => '0.5',
            'contact'                  => '0.6',
            'privacy'                  => '0.2',
            'terms'                    => '0.2',
            'cookies'                  => '0.2',
        ];
        // Listing pages that show "No results" when empty — Google treats those as
        // soft 404s, so they're left out until they have at least one item.
        $listings = [
            'properties.sale'          => ['properties',         'listing_type', 'sale'],
            'properties.rent'          => ['properties',         'listing_type', 'rent'],
            'properties.private'       => ['properties',         'listing_type', 'private'],
            'properties.international' => ['properties',         'listing_type', 'international'],
            'investments.index'        => ['investments',        null,           null],
            'branded-residences.index' => ['branded_residences', null,           null],
            'developments.index'       => ['developments',       null,           null],
            'communities.index'        => ['communities',        null,           null],
        ];

        foreach ($static as $name => $priority) {
            if (isset($listings[$name]) && !$this->hasItems(...$listings[$name])) {
                continue;
            }
            $urls[] = ['loc' => route($name), 'lastmod' => null, 'priority' => $priority];
        }

        // Database-driven detail pages (same "active" filters as the front controllers)
        $sources = [
            ['properties',         'status',    'active', 'properties.show',         '0.8'],
            ['developments',       'is_active', 1,        'developments.show',       '0.8'],
            ['investments',        'is_active', 1,        'investments.show',        '0.7'],
            ['branded_residences', 'is_active', 1,        'branded-residences.show', '0.7'],
            ['communities',        'is_active', 1,        'communities.show',        '0.7'],
        ];
        foreach ($sources as [$table, $column, $value, $route, $priority]) {
            try {
                $rows = DB::table($table)
                          ->select('slug', 'updated_at')
                          ->where($column, $value)
                          ->whereNotNull('slug')
                          ->where('slug', '!=', '')
                          ->orderByDesc('updated_at')
                          ->get();
            } catch (\Throwable $e) {
                Log::warning("Sitemap: could not read {$table}: " . $e->getMessage());
                continue;
            }

            foreach ($rows as $row) {
                $urls[] = [
                    'loc'      => route($route, $row->slug),
                    'lastmod'  => $row->updated_at ? Carbon::parse($row->updated_at)->toAtomString() : null,
                    'priority' => $priority,
                ];
            }
        }

        // Blog posts (Sanity)
        $posts = $sanity->query(
            '*[_type == "post" && defined(slug.current) && publishedAt <= now()] | order(publishedAt desc){
                "slug": slug.current, _updatedAt
            }',
            [],
            []
        );
        foreach ($posts as $post) {
            $urls[] = [
                'loc'      => route('blogs.show', $post['slug']),
                'lastmod'  => !empty($post['_updatedAt']) ? Carbon::parse($post['_updatedAt'])->toAtomString() : null,
                'priority' => '0.6',
            ];
        }

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . "</loc>\n";
            if ($url['lastmod']) {
                $xml .= '    <lastmod>' . $url['lastmod'] . "</lastmod>\n";
            }
            $xml .= '    <priority>' . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    // Same "active" rules as the front-end listing pages
    private function hasItems(string $table, ?string $column, $value): bool
    {
        try {
            [$activeColumn, $activeValue] = $table === 'properties' ? ['status', 'active'] : ['is_active', 1];

            $query = DB::table($table)->where($activeColumn, $activeValue);
            if ($column) {
                $query->where($column, $value);
            }
            return $query->exists();
        } catch (\Throwable $e) {
            return true; // if unsure, keep the page listed
        }
    }
}
