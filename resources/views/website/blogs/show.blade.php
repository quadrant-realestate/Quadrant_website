@extends('website.layout.app')

@php
    $fallbackImage = URL::to('') . '/public/assets/images/og-image.jpg';
    $heroImage     = \App\Services\Sanity::image($post['image'] ?? null, 1920, 800) ?? $fallbackImage;
@endphp

@section('title', ($post['metaTitle'] ?? null ?: $post['title']) . ' — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', $post['metaDescription'] ?? null ?: Str::limit($post['excerpt'] ?? strip_tags($bodyHtml), 160))
@section('og_image', \App\Services\Sanity::image($post['image'] ?? null, 1200, 630) ?? $fallbackImage)

@section('head')
<style>
    .qp-article-hero {
        position: relative;
        height: 360px;
        overflow: hidden;
        background: #11203A;
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
        color: #c7d2e2; font-size: 13px; margin: 0;
    }
    .qp-article-body {
        max-width: 760px; margin: 0 auto;
        font-size: 15px; line-height: 1.8; color: #1B2538;
        padding: 50px 20px;
    }
    .qp-article-body p { margin: 0 0 20px; }
    .qp-article-body h2, .qp-article-body h3, .qp-article-body h4 {
        font-family: 'Cormorant Garamond', serif;
        color: #11203A; margin: 32px 0 14px;
    }
    .qp-article-body ul, .qp-article-body ol { margin: 0 0 20px; padding-left: 22px; }
    .qp-article-body li { margin: 0 0 8px; }
    .qp-article-body li > ul, .qp-article-body li > ol { margin: 8px 0 0; }
    .qp-article-body a { color: #11203A; text-decoration: underline; text-decoration-color: #C8A965; text-underline-offset: 3px; }
    .qp-article-body blockquote {
        margin: 28px 0; padding: 4px 0 4px 22px;
        border-left: 3px solid #C8A965;
        font-family: 'Cormorant Garamond', serif;
        font-size: 21px; line-height: 1.5; color: #11203A;
    }
    .qp-article-body figure { margin: 32px 0; }
    .qp-article-body figure img { width: 100%; height: auto; border-radius: 4px; }
    .qp-article-body figcaption { font-size: 12px; color: #8a93a3; margin-top: 8px; text-align: center; }
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
    .qp-related-img { height: 150px; overflow: hidden; background: #e9ecf1; }
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

@section('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context'      => 'https://schema.org',
    '@type'         => 'BlogPosting',
    'headline'      => $post['title'],
    'description'   => $post['excerpt'] ?? '',
    'image'         => $heroImage,
    'datePublished' => $post['publishedAt'],
    'author'        => ['@type' => 'Organization', 'name' => $post['author'] ?? setting('site_name', 'Quadrant Properties')],
    'mainEntityOfPage' => url()->current(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endsection

@section('content')

{{-- ARTICLE HERO --}}
<section class="qp-article-hero">
    <img src="{{ $heroImage }}" alt="{{ $post['imageAlt'] ?? $post['title'] }}">
    <div class="qp-article-meta">
        <div class="container">
            @if(!empty($post['category']['title']))
                <p class="qp-cat">{{ $post['category']['title'] }}</p>
            @endif
            <h1>{{ $post['title'] }}</h1>
            <p class="qp-byline">
                @if(!empty($post['author'])){{ $post['author'] }} &middot; @endif
                {{ \Carbon\Carbon::parse($post['publishedAt'])->format('d M Y') }}
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
                        <li><a href="{{ route('blogs.index') }}">Blogs</a></li>
                        <li><span>{{ $post['title'] }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ARTICLE BODY --}}
<article class="qp-article-body">
    {!! $bodyHtml !!}
</article>

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
@if(!empty($post['related']))
<section style="padding:56px 0;">
    <div class="container">
        <h2 style="font-family:'Cormorant Garamond',serif; font-size:20px; color:#11203A; text-align:center; margin:0 0 30px;">
            Related Articles
        </h2>
        <div class="qp-related-grid">
            @foreach($post['related'] as $related)
                <a href="{{ route('blogs.show', $related['slug']) }}" class="qp-related-card">
                    <div class="qp-related-img">
                        <img src="{{ \App\Services\Sanity::image($related['image'] ?? null, 520, 300) ?? $fallbackImage }}"
                             alt="{{ $related['imageAlt'] ?? $related['title'] }}" loading="lazy">
                    </div>
                    <div class="qp-related-body">
                        <h4>{{ $related['title'] }}</h4>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
