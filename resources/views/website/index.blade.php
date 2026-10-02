@extends('website.layout.app')
@section('body-class', 'qp-home-page')

@section('title', setting('site_name', 'Quadrant Properties') . ' — Luxury & Off-Plan Real Estate in Dubai')
@section('meta_description', 'Dubai\'s finest off-plan and luxury residences — villas, penthouses and apartments in Palm Jumeirah, Downtown and Dubai Hills. Advised, never sold.')

@section('head')
<style>
    /* ===========================================================
       QUADRANT HOMEPAGE — scoped redesign
       Namespaced under .qp- to avoid clashing with theme CSS
    =========================================================== */
    :root {
        --qp-navy:      #11203A;
        --qp-navy-dark: #0B1D3A;
        --qp-gold:      #C8A965;
        --qp-text:      #1B2538;
        --qp-text-muted:#5B6678;
        --qp-grey-bg:   #F4F5F7;
    }
    .qp-home { font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif; color: var(--qp-text); }
    .qp-home a { text-decoration: none; }
    .qp-section { padding: 64px 0; }
    .qp-container { max-width: 1180px; margin: 0 auto; padding: 0 20px; }

    /* Hero */
    .qp-hero { position: relative; min-height: 90vh; display: flex; align-items: center; overflow: hidden; }
    .qp-hero img.qp-hero-bg,
    .qp-hero video.qp-hero-bg {
        position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0;
    }
    .qp-hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(11,29,58,0.65) 0%, rgba(11,29,58,0.45) 60%, rgba(11,29,58,0.7) 100%); z-index: 1; }
    .qp-hero-inner { position: relative; z-index: 2; width: 100%; padding: 100px 0 60px; text-align: center; }
    .qp-hero-text { max-width: 640px; margin: 0 auto; }
    .qp-eyebrow-rule { width: 56px; height: 2px; background: var(--qp-gold); margin: 18px auto 22px; }
    .qp-hero-text h1 { font-size: 46px; line-height: 1.18; font-weight: 600; color: #fff; margin: 0; letter-spacing: .3px; }
.qp-hero-text h1 span { color: #bcd3ef; font-weight: 400; display: block; }

@media (max-width: 575px) {
    .qp-hero-text h1 { font-size: 28px; letter-spacing: .2px; }
    .qp-hero-text h1 span { display: block; }
    .qp-hero-text p { font-size: 14px; margin: 14px 0 22px; }
}
    .qp-hero-text p { color: #e6ecf5; font-size: 16px; line-height: 1.6; margin: 18px 0 28px; max-width: 460px; margin-left:auto; margin-right:auto; }
    .qp-hero-btns { display: flex; gap: 14px; flex-wrap: wrap; justify-content: center; align-items: stretch; }
   .qp-btn { display: inline-flex; align-items: center; justify-content: center; padding: 13px 26px; font-size: 13px; letter-spacing: .5px; text-transform: uppercase; font-weight: 600; border-radius: 2px; transition: all .2s ease; cursor: pointer; border: 1px solid transparent; min-width: 220px; text-align: center; }
    .qp-btn-navy { background: var(--qp-navy); color: #fff; }
    .qp-btn-navy:hover { background: var(--qp-navy-dark); color: #fff; }
    .qp-btn-outline-light { background: transparent; color: #fff; border-color: rgba(255,255,255,.7); }
    .qp-btn-outline-light:hover { background: rgba(255,255,255,.12); color: #fff; }
    .qp-btn-outline-navy { background: transparent; color: var(--qp-navy); border-color: var(--qp-navy); }
    .qp-btn-outline-navy:hover { background: var(--qp-navy); color: #fff; }

    /* Section headings */
    .qp-heading { text-align: center; margin-bottom: 44px; }
    .qp-heading .qp-eyebrow-rule { margin-left: auto; margin-right: auto; }
    .qp-heading h2 {  letter-spacing: 2.5px; text-transform: uppercase; color: var(--qp-navy); font-weight: 700; margin: 0; }
    .qp-heading p.qp-sub { color: var(--qp-text-muted); font-size: 14px; margin: 10px 0 0; }

    /* Curated Collections */
    .qp-collections { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; }
    .qp-collection-card { position: relative; border-radius: 4px; overflow: hidden; background: #fff; box-shadow: 0 4px 18px rgba(11,29,58,.06); }
    .qp-collection-img { height: 230px; overflow: hidden; }
    .qp-collection-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
    .qp-collection-card:hover .qp-collection-img img { transform: scale(1.05); }
    .qp-collection-icon { width: 46px; height: 46px; border-radius: 50%; background: #fff; border: 1px solid #e3e8ef; display: flex; align-items: center; justify-content: center; position: absolute; top: 206px; left: 24px; box-shadow: 0 4px 10px rgba(0,0,0,.08); }
    .qp-collection-icon svg { width: 20px; height: 20px; stroke: var(--qp-navy); }
    .qp-collection-body { padding: 36px 24px 24px; }
    .qp-collection-body h3 { font-size: 13px; letter-spacing: 1px; text-transform: uppercase; color: var(--qp-navy); font-weight: 700; margin: 0 0 12px; }
    .qp-collection-body ul { list-style: none; padding: 0; margin: 0; }
    .qp-collection-body li { font-size: 13px; color: var(--qp-text-muted); padding: 3px 0; }

    /* Why Quadrant */
    .qp-why { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; text-align: center; }
    .qp-why-icon { width: 64px; height: 64px; border-radius: 50%; border: 1px solid #d8e2ef; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; }
    .qp-why h4 { font-size: 12px; letter-spacing: .8px; text-transform: uppercase; font-weight: 700; color: var(--qp-text); margin: 0 0 10px; }
    .qp-why p { font-size: 13px; color: var(--qp-text-muted); line-height: 1.5; margin: 0; }

    /* Market Intelligence */
    .qp-market { background: var(--qp-grey-bg); }
    .qp-market-grid { display: grid; grid-template-columns: 1fr 1.6fr; gap: 40px; align-items: center; }
    .qp-market-left h2 { font-size: 13px; letter-spacing: 2px; text-transform: uppercase; color: var(--qp-navy); font-weight: 700; margin: 0 0 14px; }
    .qp-market-left .qp-eyebrow-rule { margin: 0 0 16px; }
    .qp-market-left p { font-size: 14px; color: var(--qp-text-muted); margin: 0 0 22px; }
    .qp-market-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .qp-market-stat { background: #fff; border-radius: 4px; padding: 20px 16px; text-align: center; box-shadow: 0 2px 10px rgba(11,29,58,.05); }
    .qp-market-stat svg { width: 24px; height: 24px; stroke: var(--qp-navy); margin-bottom: 10px; }
    .qp-market-stat h5 { font-size: 11px; letter-spacing: .4px; text-transform: uppercase; font-weight: 700; color: var(--qp-text); margin: 0 0 6px; line-height: 1.3; }
    .qp-market-stat span { font-size: 11px; color: var(--qp-text-muted); }

    /* Featured Off-Plan Projects / Listings */
    .qp-listings-head { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 4px; }
    .qp-listings-head h2 { font-size: 13px; letter-spacing: 2px; text-transform: uppercase; color: var(--qp-navy); font-weight: 700; margin: 0; }
    .qp-listings-head a { font-size: 12px; letter-spacing: .5px; color: var(--qp-gold); font-weight: 600; }
    .qp-listings-sub { color: var(--qp-text-muted); font-size: 14px; margin: 0 0 28px; }
    .qp-listings-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
    .qp-listing-card { background: #fff; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 14px rgba(11,29,58,.07); transition: transform .2s ease, box-shadow .2s ease; display:block; }
    .qp-listing-card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(11,29,58,.12); }
    .qp-listing-img { position: relative; height: 170px; overflow: hidden; }
    .qp-listing-img img { width: 100%; height: 100%; object-fit: cover; }
    .qp-listing-badge { position: absolute; top: 12px; left: 12px; background: var(--qp-gold); color: #11203A; font-size: 10px; letter-spacing: .5px; text-transform: uppercase; padding: 5px 10px; border-radius: 2px; font-weight: 700; }
    .qp-listing-body { padding: 16px 18px 20px; }
    .qp-listing-body h4 { font-size: 15px; font-weight: 700; color: var(--qp-text); margin: 0 0 4px; font-family: 'Cormorant Garamond', serif; }
    .qp-listing-body .qp-loc { font-size: 12px; color: var(--qp-text-muted); margin: 0 0 2px; }
    .qp-listing-body .qp-developer { font-size: 11px; color: var(--qp-text-muted); margin: 0 0 10px; }
    .qp-listing-body .qp-price { font-size: 14px; font-weight: 700; color: var(--qp-navy); margin: 0 0 12px; }
    .qp-stat-row { display: flex; gap: 14px; font-size: 12px; color: var(--qp-text-muted); border-top: 1px solid #eef1f5; padding-top: 12px; }

    /* Communities Grid */
    .qp-communities-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    .qp-community-tile { position: relative; border-radius: 4px; overflow: hidden; height: 160px; display: block; }
    .qp-community-tile img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
    .qp-community-tile:hover img { transform: scale(1.06); }
    .qp-community-tile::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(11,29,58,0) 40%, rgba(11,29,58,.75) 100%); }
    .qp-community-tile span { position: absolute; bottom: 14px; left: 16px; z-index: 2; color: #fff; font-size: 13px; font-weight: 600; letter-spacing: .3px; }

    /* Services Snapshot */
    .qp-services-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
    .qp-service-card { border: 1px solid #e3e8ef; border-radius: 4px; padding: 28px 22px; text-align: center; transition: border-color .2s ease, box-shadow .2s ease; }
    .qp-service-card:hover { border-color: var(--qp-gold); box-shadow: 0 6px 18px rgba(11,29,58,.06); }
    .qp-service-card h4 { font-size: 14px; font-weight: 700; color: var(--qp-text); margin: 0; font-family: 'Cormorant Garamond', serif; letter-spacing: .3px; }

    /* Off-Market / Giving Banner (shared style) */
    .qp-offmarket { position: relative; min-height: 320px; display: flex; align-items: center; overflow: hidden; }
    .qp-offmarket img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; }
    .qp-offmarket::after { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(11,29,58,.92) 0%, rgba(11,29,58,.55) 60%, rgba(11,29,58,.25) 100%); z-index: 1; }
    .qp-offmarket-inner { position: relative; z-index: 2; max-width: 480px; padding: 20px; }
    .qp-offmarket-inner .qp-eyebrow { font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: var(--qp-gold); font-weight: 700; margin-bottom: 12px; }
    .qp-offmarket-inner h2 { font-size: 30px; color: #fff; font-weight: 600; line-height: 1.25; margin: 0 0 14px; }
    .qp-offmarket-inner p { color: #d7e0ee; font-size: 14px; line-height: 1.6; margin: 0 0 24px; }

    /* Developer Partners */
    .qp-partners-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
    .qp-partners-head h2 { font-size: 13px; letter-spacing: 2px; text-transform: uppercase; color: var(--qp-navy); font-weight: 700; margin: 0; }
    .qp-partners-head a { font-size: 12px; color: var(--qp-gold); font-weight: 600; }
    .qp-partners-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 28px; }
    .qp-partner-logo { font-size: 15px; font-weight: 700; color: #29354a; letter-spacing: .5px; opacity: .75; }
    .qp-partner-logo img { max-height: 32px; width: auto; opacity: .85; }

    /* Founder Section */
    .qp-founder { background: #fff; }
    .qp-founder-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; align-items: stretch; }
    .qp-founder-text { padding-right: 50px; display: flex; flex-direction: column; justify-content: center; }
    .qp-founder-text .qp-eyebrow-rule { margin: 0 0 18px; }
    .qp-founder-text h2 { font-size: 24px; font-weight: 600; color: var(--qp-text); line-height: 1.3; margin: 0 0 18px; }
    .qp-founder-text p { font-size: 14px; color: var(--qp-text-muted); line-height: 1.7; margin: 0 0 22px; }
    .qp-signature { font-family: 'Brush Script MT', cursive; font-size: 26px; color: var(--qp-navy); margin-bottom: 4px; }
    .qp-founder-name { font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--qp-text); }
    .qp-founder-title { font-size: 11px; color: var(--qp-text-muted); letter-spacing: .3px; }
    .qp-founder-imgs { display: grid; grid-template-columns: 1.3fr 1fr; gap: 14px; }
    .qp-founder-imgs img { width: 100%; height: 100%; object-fit: cover; border-radius: 2px; min-height: 360px; }

    /* Responsive */
    @media (max-width: 991px) {
        .qp-collections, .qp-why, .qp-listings-grid, .qp-communities-grid, .qp-services-grid { grid-template-columns: repeat(2, 1fr); }
        .qp-market-grid, .qp-founder-grid { grid-template-columns: 1fr; }
        .qp-founder-text { padding-right: 0; margin-bottom: 30px; }
        .qp-hero-text { max-width: 100%; }
    }
    @media (max-width: 575px) {
        .qp-collections, .qp-why, .qp-listings-grid, .qp-market-stats, .qp-communities-grid, .qp-services-grid { grid-template-columns: 1fr; }
        .qp-hero-text h1 { font-size: 32px; }
        .qp-section { padding: 44px 0; }
    }
    
    @media (max-width: 575px) {
    .qp-btn { min-width: 70%; }
    .qp-hero-btns { flex-direction: column; align-items: center; }
}
</style>
@endsection

@section('content')
<div class="qp-home">

    {{-- SECTION 1 — HERO (no search widget — brief: headline, sub-line, two buttons only) --}}
   <section class="qp-hero">
    {{-- Hero video background — autoplay, loop, muted (required for autoplay), no controls --}}
    <video
        class="qp-hero-bg"
        autoplay
        loop
        muted
        playsinline
        preload="auto"
        poster="{{ URL::to('') }}/public/assets/images/quadrant-hero-dubai.jpg"
        style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:0;">
        <source src="{{ URL::to('') }}/public/assets/video/home-video-3.mp4" type="video/mp4">
        {{-- Fallback image if browser doesn't support video --}}
        <img src="{{ URL::to('') }}/public/assets/images/quadrant-hero-dubai.jpg" alt="Dubai skyline">
    </video>
        <div class="qp-container qp-hero-inner">
            <div class="qp-hero-text">
                <h1>Know where you stand.</h1>
                <div class="qp-eyebrow-rule"></div>
                <p>Dubai's finest off-plan and luxury residences — advised, never sold.</p>
                <div class="qp-hero-btns">
                    <a href="{{ route('developments.index') }}" class="qp-btn qp-btn-navy">Explore Off-Plan Projects</a>
                    <a href="{{ route('contact') }}" class="qp-btn qp-btn-outline-light">Speak with an Advisor</a>
                </div>
            </div>
        </div>
    </section>


    {{-- SECTION 2 — POSITIONING STATEMENT --}}
    <section class="qp-section" style="padding: 48px 0; border-bottom: 1px solid #eef1f5;">
        <div class="qp-container" style="max-width: 760px; text-align: center;">
            <p style="font-family:'Cormorant Garamond',serif; font-size:20px; line-height:1.7; color:#1B2538; font-weight:400; margin:0;">
                Quadrant Properties is a luxury real estate advisory built on two ideas: that the right home is the
                fixed point of a life, and that you deserve counsel you can trust to find it. We specialise in Dubai
                off-plan — the landmark developments shaping the city's future — and we advise rather than sell.
            </p>
        </div>
    </section>


    {{-- SECTION 3 — FEATURED OFF-PLAN PROJECTS (centrepiece, pulls from developments) --}}
    <section class="qp-section">
        <div class="qp-container">

            <div class="qp-listings-head">
                <h2>Featured Off-Plan Projects</h2>
                <a href="{{ route('developments.index') }}">View all projects →</a>
            </div>
            <p class="qp-listings-sub">Hand-selected launches and landmark developments across Dubai.</p>

            <div class="qp-listings-grid">

                @forelse($featuredDevelopments as $development)
                    <a href="{{ route('developments.show', $development->slug) }}" class="qp-listing-card">
                        <div class="qp-listing-img">
                            @if($development->status)
                                @php
                                    $statusLabels = [
                                        'upcoming'           => 'New Launch',
                                        'launched'           => 'New Launch',
                                        'under_construction' => 'Under Construction',
                                        'completed'          => 'Handover Ready',
                                    ];
                                @endphp
                                <span class="qp-listing-badge">{{ $statusLabels[$development->status] ?? 'New Launch' }}</span>
                            @endif
                            @if($development->main_image)
                                <img src="{{ URL::to('') }}/public/{{ $development->main_image }}"
                                     alt="{{ $development->title }}"
                                     onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
                            @else
                                <img src="{{ URL::to('') }}/public/assets/img/placeholder.jpg" alt="{{ $development->title }}">
                            @endif
                        </div>
                        <div class="qp-listing-body">
                            <h4>{{ $development->title }}</h4>
                            @if($development->community_name)
                                <p class="qp-loc">{{ $development->community_name }}</p>
                            @endif
                            @if($development->developer_name)
                                <p class="qp-developer">{{ $development->developer_name }}</p>
                            @endif
                            @if($development->price_from)
                                <p class="qp-price">From {{ $development->price_currency ?? 'AED' }} {{ number_format($development->price_from) }}</p>
                            @endif
                            <div class="qp-stat-row">
                                @if($development->bedroom_range ?? null)
                                    <span>{{ $development->bedroom_range }}</span>
                                @endif
                                @if($development->handover_date)
                                    <span>{{ \Carbon\Carbon::parse($development->handover_date)->format('Y') }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <p style="grid-column: 1/-1; text-align:center; color:var(--qp-text-muted);">No featured projects at the moment.</p>
                @endforelse

            </div>
        </div>
    </section>


    {{-- SECTION 4 — WHY QUADRANT (The Four Bearings) --}}
    <section class="qp-section" style="padding-top:10px;">
        <div class="qp-container">
            <div class="qp-heading">
                <div class="qp-eyebrow-rule"></div>
                <h2>Why Quadrant</h2>
            </div>

            <div class="qp-why">
                <div>
                    <div class="qp-why-icon" style="border-color: #C8A965;">
                        <span style="font-family:'Cormorant Garamond',serif; font-size:22px; font-weight:600; color:#11203A;">N</span>
                    </div>
                    <h4>Counsel</h4>
                    <p>We advise, we do not sell. Honest guidance, even when it costs us the deal.</p>
                </div>

                <div>
                    <div class="qp-why-icon">
                        <span style="font-family:'Cormorant Garamond',serif; font-size:22px; font-weight:600; color:#11203A;">E</span>
                    </div>
                    <h4>Craft</h4>
                    <p>We represent only property worthy of our name. Quality is the entry requirement.</p>
                </div>

                <div>
                    <div class="qp-why-icon">
                        <span style="font-family:'Cormorant Garamond',serif; font-size:22px; font-weight:600; color:#11203A;">S</span>
                    </div>
                    <h4>Custody</h4>
                    <p>Your interest is held above ours — long-term, confidential, protected.</p>
                </div>

                <div>
                    <div class="qp-why-icon" style="border-color:#C8A965; background:#fffdf5;">
                        <span style="font-family:'Cormorant Garamond',serif; font-size:22px; font-weight:600; color:#C8A965;">W</span>
                    </div>
                    <h4 style="color:#C8A965;">Consequence</h4>
                    <p>We pledge a portion of our profit, every year, to building permanent homes for underprivileged families around the world.</p>
                </div>

            </div>
        </div>
    </section>


    {{-- SECTION 5 — FEATURED COMMUNITIES (7 priority, fixed order) --}}
    <section class="qp-section qp-market" style="background:#fff;">
        <div class="qp-container">
            <div class="qp-heading">
                <div class="qp-eyebrow-rule"></div>
                <h2>Featured Communities</h2>
            </div>

            <div class="qp-communities-grid">
                @php
                    // Fixed 7 priority communities per brief — exact order required.
                    $priorityCommunities = [
                        'downtown-dubai'      => 'Downtown Dubai',
                        'dubai-marina'        => 'Dubai Marina',
                        'palm-jebel-ali'      => 'Palm Jebel Ali',
                        'dubai-creek-harbour' => 'Dubai Creek Harbour',
                        'dubai-hills-estate'  => 'Dubai Hills Estate',
                        'jvc'                 => 'JVC',
                        'dubai-south'         => 'Dubai South',
                    ];
                    // Build a lookup of slug => image from any matching DB communities passed in.
                    $communityImages = collect($priorityCommunityRecords ?? [])->keyBy('slug');
                @endphp

                @foreach($priorityCommunities as $slug => $name)
                    @php $record = $communityImages->get($slug); @endphp
                    <a href="{{ route('communities.show', $slug) }}" class="qp-community-tile">
                        @if($record && $record->image)
                            <img src="{{ URL::to('') }}/public/{{ $record->image }}" alt="{{ $name }}">
                        @else
                            <img src="{{ URL::to('') }}/public/assets/img/placeholder.jpg" alt="{{ $name }}">
                        @endif
                        <span>{{ $name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>


    {{-- SECTION 6 — SERVICES SNAPSHOT (titles only — full detail on Services page) --}}
    <section class="qp-section qp-market">
        <div class="qp-container">
            <div class="qp-heading">
                <div class="qp-eyebrow-rule"></div>
                <h2>How We Work</h2>
            </div>

            <div class="qp-services-grid">
                <div class="qp-service-card">
                    <h4>Off-Plan Advisory</h4>
                </div>
                <div class="qp-service-card">
                    <h4>Luxury Acquisition</h4>
                </div>
                <div class="qp-service-card">
                    <h4>Investment Guidance</h4>
                </div>
                <div class="qp-service-card">
                    <h4>Portfolio Stewardship</h4>
                </div>
            </div>
        </div>
    </section>


    


    {{-- SECTION 8 — OUR STORY TEASER --}}
    <section style="background: #11203A; padding: 60px 0; text-align:center;">
        <div class="qp-container">
            <p style="font-family:'Cormorant Garamond',serif; font-size:22px; color:#fff; font-style:italic; margin:0 0 24px; max-width:680px; margin-left:auto; margin-right:auto; line-height:1.6;">
                A quadrant is the instrument explorers used to find where they stood.
                We help you do the same.
            </p>
            <a href="{{ route('about') }}" style="font-size:13px; letter-spacing:.5px; color:#C8A965; font-weight:600;">→ Our Story</a>
        </div>
    </section>


    {{-- SECTION 9 — INSIGHTS TEASER (renders once blog_posts table + InsightController exist) --}}
    <!-- @ if(($latestInsights ?? collect())->count() > 0)
    <section class="qp-section">
        <div class="qp-container">
            <div class="qp-listings-head">
                <h2>Insights</h2>
                <a href="{{ route('blogs.index') }}">Read our Blogs →</a>
            </div>

            <div class="qp-listings-grid" style="grid-template-columns: repeat(3, 1fr);">
                @ foreach($latestInsights as $post)
                    <a href="{ { route('insights.show', $ post->slug) }}" class="qp-listing-card">
                        <div class="qp-listing-img" style="height:190px;">
                            @ if($post->main_image)
                                <img src="{{ URL::to('') }}/public/{ { $post->main_image }}" alt="{ { $post->title }}">
                            @ else
                                <img src="{{ URL::to('') }}/public/assets/img/placeholder.jpg" alt="{ { $post->title }}">
                            @ endif
                        </div>
                        <div class="qp-listing-body">
                            <p class="qp-loc" style="margin-bottom:6px;">{ { \Carbon\Carbon::parse($post->published_at)->format('d M Y') }}</p>
                            <h4>{ { $post->title }}</h4>
                        </div>
                    </a>
                @ endforeach
            </div>
        </div>
    </section>
    @ endif -->


    {{-- DUBAI MARKET INTELLIGENCE --}}
    <!-- <section class="qp-section qp-market">
        <div class="qp-container">
            <div class="qp-market-grid">

                <div class="qp-market-left">
                    <h2>Dubai Market Intelligence</h2>
                    <div class="qp-eyebrow-rule"></div>
                    <p>Actionable insights. Unrivalled perspective.</p>
                    <a href="{{ route('communities.index') }}" class="qp-btn qp-btn-outline-navy">Explore Market Reports</a>
                </div>

                <div class="qp-market-stats">

                    <div class="qp-market-stat">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                        <h5>Capital Appreciation</h5>
                        <span>Market trends and forecasts</span>
                    </div>

                    <div class="qp-market-stat">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182.553-.439 1.278-.659 2.003-.659" /></svg>
                        <h5>Rental Yield Heat Maps</h5>
                        <span>Live data across prime locations</span>
                    </div>

                    <div class="qp-market-stat">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zM12 3v18m9-9H3" /></svg>
                        <h5>Emerging Luxury Districts</h5>
                        <span>Where value is moving next</span>
                    </div>

                    <div class="qp-market-stat">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        <h5>Developer Rankings</h5>
                        <span>Performance and delivery analysis</span>
                    </div>

                </div>
            </div>
        </div>
    </section> -->


    {{-- DEVELOPER PARTNERS --}}
    <!-- <section class="qp-section">
        <div class="qp-container">
            <div class="qp-partners-head">
                <h2>Our Developer Partners</h2>
                <a href="{{ route('developments.index') }}">View All Partners →</a>
            </div>

            <div class="qp-partners-row">
                @forelse($developerPartners as $partner)
                    <div class="qp-partner-logo">
                        @if($partner->developer_logo)
                            <img src="{{ URL::to('') }}/public/{{ $partner->developer_logo }}" alt="{{ $partner->developer_name }}">
                        @else
                            {{ $partner->developer_name }}
                        @endif
                    </div>
                @empty
                    <div class="qp-partner-logo">EMAAR</div>
                    <div class="qp-partner-logo">NAKHEEL</div>
                    <div class="qp-partner-logo">MERAAS</div>
                    <div class="qp-partner-logo">SOBHA</div>
                    <div class="qp-partner-logo">OMNIYAT</div>
                @endforelse
            </div>
        </div>
    </section> -->


    {{-- FOUNDER / BOUTIQUE BY DESIGN --}}
    <!-- <section class="qp-section qp-founder">
        <div class="qp-container">
            <div class="qp-founder-grid">

                <div class="qp-founder-text">
                    <div class="qp-eyebrow-rule"></div>
                    <h2>Boutique by Design.<br>Global by Reach.</h2>
                    <p>At Quadrant Properties, we believe real estate is more than an asset — it's a legacy. Our mission is to help you acquire extraordinary properties that preserve wealth and elevate life.</p>
                    <div class="qp-signature">Ziad</div>
                    <div class="qp-founder-name">Ziad El Chagoury</div>
                    <div class="qp-founder-title">Founder &amp; CEO</div>
                </div>

                <div class="qp-founder-imgs">
                    <img src="{{ URL::to('') }}/public/assets/images/quadrant-skyline-night.jpg" alt="Dubai skyline at night">
                    <img src="{{ URL::to('') }}/public/assets/images/quadrant-founder.jpg" alt="Founder portrait">
                </div>

            </div>
        </div>
    </section> -->


    {{-- SECTION 10 — ENQUIRY / LEAD BAND (before footer) --}}
    <section style="background: #F4F5F7; padding: 64px 0; border-top: 2px solid #C8A965;">
        <div class="qp-container" style="text-align:center;">
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:28px; color:#11203A; margin:0 0 12px;">
                Considering a move, or an investment?
            </h2>
            <p style="color:#5B6678; font-size:15px; margin:0 0 28px;">
                Begin with a conversation — not a pitch.
            </p>
            <a href="{{ route('contact') }}" class="qp-btn qp-btn-navy">
                Speak with an Advisor
            </a>
        </div>
    </section>

</div>
@endsection