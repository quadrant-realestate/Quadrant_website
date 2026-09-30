@extends('website.layout.app')

@section('title', 'Insights — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', 'Clear thinking on Dubai property — the off-plan market, the communities, and how to stand on solid ground.')

@section('head')
<style>
    .qp-insights-banner {
        background: #11203A;
        padding: 64px 0 48px;
        text-align: center;
    }
    .qp-insights-banner h1 {
        font-family: 'Cormorant Garamond', serif;
        color: #fff;
        font-size: 36px;
        margin: 0 0 14px;
    }
    .qp-insights-banner p {
        color: #c7d2e2;
        font-size: 15px;
        max-width: 560px;
        margin: 0 auto;
    }
    .qp-insights-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        margin: 28px 0 0;
    }
    .qp-insights-filters a {
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
    .qp-insights-filters a:hover,
    .qp-insights-filters a.active {
        background: #C8A965;
        border-color: #C8A965;
        color: #11203A;
    }
    .qp-insights-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 26px;
    }
    .qp-insight-card {
        background: #fff;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 2px 14px rgba(11,29,58,.07);
        transition: transform .2s ease, box-shadow .2s ease;
        display: block;
    }
    .qp-insight-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(11,29,58,.12);
    }
    .qp-insight-img { height: 190px; overflow: hidden; }
    .qp-insight-img img { width: 100%; height: 100%; object-fit: cover; }
    .qp-insight-body { padding: 20px; }
    .qp-insight-cat {
        font-size: 10px;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #C8A965;
        font-weight: 700;
        margin: 0 0 8px;
    }
    .qp-insight-body h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 19px;
        color: #1B2538;
        margin: 0 0 10px;
        line-height: 1.3;
    }
    .qp-insight-excerpt {
        font-size: 13px;
        color: #5B6678;
        line-height: 1.6;
        margin: 0 0 14px;
    }
    .qp-insight-date {
        font-size: 11px;
        color: #8a93a3;
    }
    @media (max-width: 991px) {
        .qp-insights-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575px) {
        .qp-insights-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

{{-- PAGE BANNER --}}
<section class="qp-insights-banner">
    <div class="container">
        <h1>Insights</h1>
        <p>Clear thinking on Dubai property — the off-plan market, the communities, and how to stand on solid ground.</p>

        <div class="qp-insights-filters">
            <a href="{{ route('insights.index') }}" class="{{ !request('category') ? 'active' : '' }}">All</a>
            @foreach($categories as $slug => $label)
                <a href="{{ route('insights.index', ['category' => $slug]) }}"
                   class="{{ request('category') == $slug ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
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
                        <li><span>Insights</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ARTICLE GRID --}}
<section style="padding: 56px 0;">
    <div class="container">

        <div class="qp-insights-grid">
            @forelse($posts as $post)
                <a href="{{ route('insights.show', $post->slug) }}" class="qp-insight-card">
                    <div class="qp-insight-img">
                        @if($post->main_image)
                            <img src="{{ URL::to('') }}/public/{{ $post->main_image }}" alt="{{ $post->title }}">
                        @else
                            <img src="{{ URL::to('') }}/public/assets/img/placeholder.jpg" alt="{{ $post->title }}">
                        @endif
                    </div>
                    <div class="qp-insight-body">
                        <p class="qp-insight-cat">{{ $categories[$post->category] ?? $post->category }}</p>
                        <h3>{{ $post->title }}</h3>
                        @if($post->excerpt)
                            <p class="qp-insight-excerpt">{{ Str::limit($post->excerpt, 110) }}</p>
                        @endif
                        <p class="qp-insight-date">{{ \Carbon\Carbon::parse($post->published_at)->format('d M Y') }}</p>
                    </div>
                </a>
            @empty
                <p style="grid-column: 1/-1; text-align:center; color:#5B6678;">No articles published yet.</p>
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