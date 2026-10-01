<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Minimal read-only client for the Sanity Content Lake (GROQ over HTTP).
 * Content is public, so no token is needed.
 */
class Sanity
{
    /**
     * Run a GROQ query and return its result, or $default if Sanity can't be reached.
     */
    public function query(string $groq, array $params = [], $default = null)
    {
        $config = config('services.sanity');

        $host = $config['use_cdn'] ? 'apicdn.sanity.io' : 'api.sanity.io';
        $url  = "https://{$config['project_id']}.{$host}/v{$config['api_version']}/data/query/{$config['dataset']}";

        $query = ['query' => $groq];
        foreach ($params as $key => $value) {
            $query['$' . $key] = json_encode($value);
        }

        try {
            $response = Http::timeout(8)->acceptJson()->get($url, $query);

            if ($response->failed()) {
                Log::warning('Sanity query failed', ['status' => $response->status(), 'body' => $response->body()]);
                return $default;
            }

            return $response->json('result') ?? $default;
        } catch (\Throwable $e) {
            Log::warning('Sanity unreachable: ' . $e->getMessage());
            return $default;
        }
    }

    /**
     * Resize/crop a Sanity CDN image URL.
     */
    public static function image(?string $url, int $width, ?int $height = null): ?string
    {
        if (!$url) {
            return null;
        }

        $params = ['w' => $width, 'auto' => 'format', 'q' => 80];
        if ($height) {
            $params['h']   = $height;
            $params['fit'] = 'crop';
        }

        return $url . '?' . http_build_query($params);
    }
}
