<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsightController extends Controller
{
    // ============================================================
    // INDEX — Insights / Journal Listing
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('blog_posts')
                    ->where('is_published', 1)
                    ->whereNotNull('published_at');

        // Category filter
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->orderBy('published_at', 'desc')
                        ->paginate(9)
                        ->withQueryString();

        $categories = [
            'off-plan'        => 'Off-Plan',
            'market-insights' => 'Market Insights',
            'communities'     => 'Communities',
            'buyer-guides'    => 'Buyer Guides',
            'quadrant-view'   => 'Quadrant View',
        ];

        return view('website.insights.index', compact('posts', 'categories'));
    }

    // ============================================================
    // SHOW — Single Article
    // ============================================================
    public function show($slug)
    {
        $post = DB::table('blog_posts')
                   ->where('slug', $slug)
                   ->where('is_published', 1)
                   ->first();

        if (!$post) {
            abort(404);
        }

        // Related articles — same category, excluding current
        $relatedPosts = DB::table('blog_posts')
                           ->where('is_published', 1)
                           ->where('category', $post->category)
                           ->where('id', '!=', $post->id)
                           ->orderBy('published_at', 'desc')
                           ->limit(3)
                           ->get();

        $categories = [
            'off-plan'        => 'Off-Plan',
            'market-insights' => 'Market Insights',
            'communities'     => 'Communities',
            'buyer-guides'    => 'Buyer Guides',
            'quadrant-view'   => 'Quadrant View',
        ];

        return view('website.insights.show', compact('post', 'relatedPosts', 'categories'));
    }
}