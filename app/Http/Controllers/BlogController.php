<?php

namespace App\Http\Controllers;

use App\Services\PortableText;
use App\Services\Sanity;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogController extends Controller
{
    private const PER_PAGE = 9;

    // Fields shown on a blog card (list page and "related" section).
    private const CARD_FIELDS = '
        title,
        "slug": slug.current,
        excerpt,
        author,
        publishedAt,
        "category": category->{title, "slug": slug.current},
        "image": mainImage.asset->url,
        "imageAlt": mainImage.alt';

    private const CARD = '{' . self::CARD_FIELDS . '}';

    public function __construct(private Sanity $sanity)
    {
    }

    // ============================================================
    // INDEX — Blog listing
    // ============================================================
    public function index(Request $request)
    {
        $page   = max(1, (int) $request->query('page', 1));
        $params = ['start' => ($page - 1) * self::PER_PAGE, 'end' => $page * self::PER_PAGE];

        $filter = '_type == "post" && defined(slug.current) && publishedAt <= now()';
        if ($request->filled('category')) {
            $filter .= ' && category->slug.current == $category';
            $params['category'] = (string) $request->query('category');
        }
        if ($request->filled('search')) {
            $filter .= ' && (title match $search || excerpt match $search)';
            $params['search'] = trim((string) $request->query('search')) . '*';
        }

        $result = $this->sanity->query(
            '{
                "posts": *[' . $filter . '] | order(publishedAt desc) [$start...$end] ' . self::CARD . ',
                "total": count(*[' . $filter . ']),
                "categories": *[_type == "category" && defined(slug.current)] | order(order asc, title asc) {title, "slug": slug.current}
            }',
            $params,
            ['posts' => [], 'total' => 0, 'categories' => []]
        );

        $posts = new LengthAwarePaginator(
            $result['posts'] ?? [],
            $result['total'] ?? 0,
            self::PER_PAGE,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $categories = $result['categories'] ?? [];

        return view('website.blogs.index', compact('posts', 'categories'));
    }

    // ============================================================
    // SHOW — Single article
    // ============================================================
    public function show(string $slug)
    {
        $post = $this->sanity->query(
            '*[_type == "post" && slug.current == $slug && publishedAt <= now()][0]{
                ' . self::CARD_FIELDS . ',
                metaTitle,
                metaDescription,
                body[]{..., _type == "image" => {..., "url": asset->url}},
                "related": *[_type == "post" && category._ref == ^.category._ref && _id != ^._id
                             && defined(slug.current) && publishedAt <= now()]
                           | order(publishedAt desc) [0...3] ' . self::CARD . '
            }',
            ['slug' => $slug]
        );

        if (!$post) {
            abort(404);
        }

        $bodyHtml = PortableText::render($post['body'] ?? []);

        return view('website.blogs.show', compact('post', 'bodyHtml'));
    }
}
