@extends('website.layout.app')

@section('title', 'Blogs — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', 'Clear thinking on Dubai property — the off-plan market, the communities, and how to stand on solid ground.')

@section('head')
<style>
    .qp-blogs-banner {
        background: #11203A;
        padding: 64px 0 48px;
        text-align: center;
    }
    .qp-blogs-banner h1 {
        font-family: 'Cormorant Garamond', serif;
        color: #fff;
        font-size: 36px;
        margin: 0 0 14px;
    }
    .qp-blogs-banner p {
        color: #c7d2e2;
        font-size: 15px;
        max-width: 560px;
        margin: 0 auto;
    }
    .qp-blogs-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        margin: 28px 0 0;
    }
    .qp-blogs-filters a {
        font-size: 12px;
        letter-spacing: .4px;
        text-transform: uppercase;
        padding: 8px 16px;
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 2px;
        color: #fff;
        text-decoration: none;
        transition: all .2s ease;
    }
    .qp-blogs-filters a:hover,
    .qp-blogs-filters a.active {
        background: #C8A965;
        border-color: #C8A965;
        color: #11203A;
    }
    .qp-blogs-search {
        display: flex;
        max-width: 420px;
        margin: 22px auto 0;
    }
    .qp-blogs-search input {
        flex: 1;
        min-width: 0;
        padding: 10px 14px;
        border: 1px solid rgba(255,255,255,.3);
        border-right: 0;
        background: transparent;
        color: #fff;
        font-size: 13px;
        border-radius: 2px 0 0 2px;
    }
    .qp-blogs-search input::placeholder { color: #8a9ab5; }
    .qp-blogs-search button {
        padding: 10px 18px;
        background: #C8A965;
        border: 0;
        color: #11203A;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .4px;
        text-transform: uppercase;
        border-radius: 0 2px 2px 0;
    }
    .qp-blogs-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 26px;
    }
    .qp-blog-card {
        background: #fff;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 2px 14px rgba(11,29,58,.07);
        transition: transform .2s ease, box-shadow .2s ease;
        display: block;
    }
    .qp-blog-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(11,29,58,.12);
    }
    .qp-blog-img { height: 190px; overflow: hidden; background: #e9ecf1; }
    .qp-blog-img img { width: 100%; height: 100%; object-fit: cover; }
    .qp-blog-body { padding: 20px; }
    .qp-blog-cat {
        font-size: 10px;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #C8A965;
        font-weight: 700;
        margin: 0 0 8px;
    }
    .qp-blog-body h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 19px;
        color: #1B2538;
        margin: 0 0 10px;
        line-height: 1.3;
    }
    .qp-blog-excerpt {
        font-size: 13px;
        color: #5B6678;
        line-height: 1.6;
        margin: 0 0 14px;
    }
    .qp-blog-date {
        font-size: 11px;
        color: #8a93a3;
        margin: 0;
    }
    @media (max-width: 991px) {
        .qp-blogs-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575px) {
        .qp-blogs-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

{{-- PAGE BANNER --}}
<section class="qp-blogs-banner">
    <div class="container">
        <h1>Blogs</h1>
        <p>Clear thinking on Dubai property — the off-plan market, the communities, and how to stand on solid ground.</p>

        @if(count($categories))
        <div class="qp-blogs-filters">
            <a href="{{ route('blogs.index') }}" class="{{ !request('category') ? 'active' : '' }}">All</a>
            @foreach($categories as $category)
                <a href="{{ route('blogs.index', ['category' => $category['slug']]) }}"
                   class="{{ request('category') == $category['slug'] ? 'active' : '' }}">
                    {{ $category['title'] }}
                </a>
            @endforeach
        </div>
        @endif

        <form class="qp-blogs-search" method="GET" action="{{ route('blogs.index') }}">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search articles" aria-label="Search articles">
            <button type="submit">Search</button>
        </form>
    </div>
</section>

{{-- BREADCRUMB --}}
<section class="breadcrumb-sec">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-container">
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><span>Blogs</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ARTICLE GRID --}}
<section style="padding: 56px 0;">
    <div class="container">

        <div class="qp-blogs-grid">
            @forelse($posts as $post)
                <a href="{{ route('blogs.show', $post['slug']) }}" class="qp-blog-card">
                    <div class="qp-blog-img">
                        <img src="{{ \App\Services\Sanity::image($post['image'] ?? null, 640, 380) ?? URL::to('') . '/public/assets/images/og-image.jpg' }}"
                             alt="{{ $post['imageAlt'] ?? $post['title'] }}" loading="lazy">
                    </div>
                    <div class="qp-blog-body">
                        @if(!empty($post['category']['title']))
                            <p class="qp-blog-cat">{{ $post['category']['title'] }}</p>
                        @endif
                        <h3>{{ $post['title'] }}</h3>
                        @if(!empty($post['excerpt']))
                            <p class="qp-blog-excerpt">{{ Str::limit($post['excerpt'], 110) }}</p>
                        @endif
                        <p class="qp-blog-date">{{ \Carbon\Carbon::parse($post['publishedAt'])->format('d M Y') }}</p>
                    </div>
                </a>
            @empty
                <p style="grid-column: 1/-1; text-align:center; color:#5B6678;">
                    {{ request('search') || request('category') ? 'No articles match your search.' : 'No articles published yet.' }}
                </p>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if($posts->hasPages())
        <div class="pagination-main" style="margin-top:40px;">
            <div class="row">
                <div class="col-12">
                    <div class="pagination-container">
                        <ul>
                            @if($posts->onFirstPage())
                                <li class="prev disabled"><span>Prev</span></li>
                            @else
                                <li class="prev"><a href="{{ $posts->previousPageUrl() }}">Prev</a></li>
                            @endif

                            @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                                @if($page == $posts->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if($posts->hasMorePages())
                                <li class="next"><a href="{{ $posts->nextPageUrl() }}">Next</a></li>
                            @else
                                <li class="next disabled"><span>Next</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>

@endsection
