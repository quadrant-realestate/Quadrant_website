@extends('website.layout.app')

@section('title', ($post->meta_title ?: $post->title) . ' — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', $post->meta_desc ?: Str::limit(strip_tags($post->excerpt ?? $post->body), 160))

@section('head')
<style>
    .qp-article-hero {
        position: relative;
        height: 360px;
        overflow: hidden;
    }
    .qp-article-hero img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .qp-article-hero::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(11,29,58,.1) 0%, rgba(11,29,58,.75) 100%);
    }
    .qp-article-meta {
        position: absolute; bottom: 0; left: 0; right: 0;
        z-index: 2;
        padding: 30px 0;
    }
    .qp-article-meta .qp-cat {
        font-size: 11px; letter-spacing: .6px; text-transform: uppercase;
        color: #C8A965; font-weight: 700; margin: 0 0 10px;
    }
    .qp-article-meta h1 {
        font-family: 'Cormorant Garamond', serif;
        color: #fff; font-size: 32px; margin: 0 0 10px; max-width: 760px;
    }
    .qp-article-meta .qp-byline {
        color: #c7d2e2; font-size: 13px;
    }
    .qp-article-body {
        max-width: 760px; margin: 0 auto;
        font-size: 15px; line-height: 1.8; color: #1B2538;
        padding: 50px 20px;
    }
    .qp-article-body p { margin: 0 0 20px; }
    .qp-article-body h2, .qp-article-body h3 {
        font-family: 'Cormorant Garamond', serif;
        color: #11203A; margin: 32px 0 14px;
    }
    .qp-related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 26px;
    }
    .qp-related-card {
        background: #fff;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 2px 14px rgba(11,29,58,.07);
        display: block;
    }
    .qp-related-img { height: 150px; overflow: hidden; }
    .qp-related-img img { width: 100%; height: 100%; object-fit: cover; }
    .qp-related-body { padding: 16px; }
    .qp-related-body h4 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 15px; color: #1B2538; margin: 0;
    }
    @media (max-width: 767px) {
        .qp-related-grid { grid-template-columns: 1fr; }
        .qp-article-meta h1 { font-size: 24px; }
    }
</style>
@endsection

@section('content')

{{-- ARTICLE HERO --}}
<section class="qp-article-hero">
    @if($post->main_image)
        <img src="{{ URL::to('') }}/public/{{ $post->main_image }}" alt="{{ $post->title }}">
    @else
        <img src="{{ URL::to('') }}/public/assets/img/placeholder.jpg" alt="{{ $post->title }}">
    @endif
    <div class="qp-article-meta">
        <div class="container">
            <p class="qp-cat">{{ $categories[$post->category] ?? $post->category }}</p>
            <h1>{{ $post->title }}</h1>
            <p class="qp-byline">
                @if($post->author){{ $post->author }} &middot; @endif
                {{ \Carbon\Carbon::parse($post->published_at)->format('d M Y') }}
            </p>
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
                        <li><a href="{{ route('insights.index') }}">Insights</a></li>
                        <li><span>{{ $post->title }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ARTICLE BODY --}}
<div class="qp-article-body">
    {!! $post->body !!}
</div>

{{-- ENQUIRY PROMPT --}}
<section style="background:#F4F5F7; padding:48px 0; border-top:2px solid #C8A965; text-align:center;">
    <div class="container">
        <h2 style="font-family:'Cormorant Garamond',serif; font-size:24px; color:#11203A; margin:0 0 10px;">
            Considering a move, or an investment?
        </h2>
        <p style="color:#5B6678; font-size:14px; margin:0 0 22px;">Begin with a conversation — not a pitch.</p>
        <a href="{{ route('contact') }}"
           style="display:inline-block; background:#11203A; color:#fff; padding:12px 26px; font-size:13px; letter-spacing:.5px; text-transform:uppercase; font-weight:600; border-radius:2px;">
            Speak with an Advisor
        </a>
    </div>
</section>

{{-- RELATED ARTICLES --}}
@if($relatedPosts->count() > 0)
<section style="padding:56px 0;">
    <div class="container">
        <h2 style="font-family:'Cormorant Garamond',serif; font-size:20px; color:#11203A; text-align:center; margin:0 0 30px;">
            Related Insights
        </h2>
        <div class="qp-related-grid">
            @foreach($relatedPosts as $related)
                <a href="{{ route('insights.show', $related->slug) }}" class="qp-related-card">
                    <div class="qp-related-img">
                        @if($related->main_image)
                            <img src="{{ URL::to('') }}/public/{{ $related->main_image }}" alt="{{ $related->title }}">
                        @else
                            <img src="{{ URL::to('') }}/public/assets/img/placeholder.jpg" alt="{{ $related->title }}">
                        @endif
                    </div>
                    <div class="qp-related-body">
                        <h4>{{ $related->title }}</h4>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection