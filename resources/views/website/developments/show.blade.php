@extends('website.layout.app')

@section('title', $development->title . ' — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', $development->short_description ?? 'Discover ' . $development->title . ' in ' . ($development->community_name ?? 'Dubai') . '. ' . setting('site_name') . ' — Off-Plan Real Estate Advisory.')
@section('og_image', URL::to('') . '/public/' . $development->main_image)

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Residence",
    "name": "{{ $development->title }}",
    "description": "{{ Str::limit(strip_tags($development->short_description ?? $development->description), 200) }}",
    "url": "{{ request()->url() }}",
    "image": "{{ URL::to('') }}/public/{{ $development->main_image }}",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "{{ $development->community_name ?? 'Dubai' }}",
        "addressCountry": "AE"
    },
    "offers": {
        "@type": "Offer",
        "price": "{{ $development->price_from }}",
        "priceCurrency": "{{ $development->price_currency ?? 'AED' }}"
    }
}
</script>
@endsection

@section('head')
<style>
    /* ============================================================
       PROJECT DETAIL — brand-aligned, 11-section template
    ============================================================ */
    .qp-pd-breadcrumb { padding: 18px 0; font-size: 12px; color: #5B6678; }
    .qp-pd-breadcrumb a { color: #5B6678; text-decoration: none; }
    .qp-pd-breadcrumb a:hover { color: #C8A965; }

    /* 1. Hero Gallery */
    .qp-pd-hero { position: relative; height: 480px; overflow: hidden; }
    .qp-pd-hero-track { display: flex; height: 100%; transition: transform .4s ease; }
    .qp-pd-hero-slide { min-width: 100%; height: 100%; }
    .qp-pd-hero-slide img { width: 100%; height: 100%; object-fit: cover; }
    .qp-pd-hero::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(11,29,58,.05) 0%, rgba(11,29,58,.75) 100%);
        pointer-events: none;
    }
    .qp-pd-hero-overlay { position: absolute; bottom: 0; left: 0; right: 0; z-index: 2; padding: 30px 0; }
    .qp-pd-status-tag {
        display: inline-block; background: #C8A965; color: #11203A;
        font-size: 10px; letter-spacing: .5px; text-transform: uppercase;
        padding: 6px 14px; border-radius: 2px; font-weight: 700; margin-bottom: 12px;
    }
    .qp-pd-hero-overlay h1 {
        font-family: 'Cormorant Garamond', serif; color: #fff; font-size: 34px; margin: 0; max-width: 760px;
    }
    .qp-pd-hero-nav {
        position: absolute; top: 50%; transform: translateY(-50%); z-index: 3;
        width: 40px; height: 40px; background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.4); border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #fff; font-size: 18px; transition: background .2s ease;
    }
    .qp-pd-hero-nav:hover { background: rgba(255,255,255,.3); }
    .qp-pd-hero-prev { left: 24px; }
    .qp-pd-hero-next { right: 24px; }
    .qp-pd-hero-dots { position: absolute; bottom: 16px; right: 24px; z-index: 3; display: flex; gap: 6px; }
    .qp-pd-hero-dots span {
        width: 7px; height: 7px; border-radius: 50%; background: rgba(255,255,255,.4); cursor: pointer;
    }
    .qp-pd-hero-dots span.active { background: #C8A965; }

    /* 2. Key Facts Strip */
    .qp-pd-facts {
        background: #F4F5F7; border-bottom: 1px solid #eef1f5;
        display: grid; grid-template-columns: repeat(4, 1fr);
    }
    .qp-pd-fact { padding: 22px 16px; text-align: center; border-right: 1px solid #e3e8ef; }
    .qp-pd-fact:last-child { border-right: none; }
    .qp-pd-fact .qp-fact-label {
        font-size: 10px; letter-spacing: .5px; text-transform: uppercase; color: #5B6678; margin: 0 0 6px;
    }
    .qp-pd-fact .qp-fact-value {
        font-family: 'Cormorant Garamond', serif; font-size: 17px; color: #11203A; font-weight: 600; margin: 0;
    }

    /* Layout */
    .qp-pd-main { max-width: 1180px; margin: 0 auto; padding: 0 20px; }
    .qp-pd-grid { display: grid; grid-template-columns: 1fr 380px; gap: 50px; padding: 50px 0; }
    .qp-pd-section { margin-bottom: 48px; }
    .qp-pd-section h2 {
        font-family: 'Cormorant Garamond', serif; font-size: 22px; color: #11203A;
        margin: 0 0 18px; padding-bottom: 12px; border-bottom: 2px solid #C8A965; display: inline-block;
    }
    .qp-pd-section p { font-size: 14px; line-height: 1.8; color: #1B2538; margin: 0 0 16px; }

    /* 4. Highlights / Amenities */
    .qp-amenity-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .qp-amenity-item { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #1B2538; }
    .qp-amenity-item svg { width: 18px; height: 18px; stroke: #C8A965; flex-shrink: 0; }

    /* 5. Floor Plans (tabbed) */
    .qp-fp-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 22px; }
    .qp-fp-tab {
        padding: 9px 18px; font-size: 12px; letter-spacing: .3px; text-transform: uppercase;
        border: 1px solid #d8dee5; border-radius: 2px; cursor: pointer; color: #5B6678;
        background: #fff; transition: all .2s ease;
    }
    .qp-fp-tab.active { background: #11203A; border-color: #11203A; color: #fff; }
    .qp-fp-panel { display: none; }
    .qp-fp-panel.active { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: center; }
    .qp-fp-img { border-radius: 4px; overflow: hidden; }
    .qp-fp-img img { width: 100%; display: block; }
    .qp-fp-meta p { font-size: 13px; color: #5B6678; margin: 0 0 14px; }
    .qp-fp-download {
        display: inline-flex; align-items: center; gap: 8px;
        border: 1px solid #C8A965; color: #C8A965; padding: 9px 18px;
        font-size: 12px; letter-spacing: .3px; text-transform: uppercase; font-weight: 600;
        border-radius: 2px; text-decoration: none; transition: all .2s ease;
    }
    .qp-fp-download:hover { background: #C8A965; color: #11203A; }

    /* 6. Payment Plan Table */
    .qp-pp-table { width: 100%; border-collapse: collapse; }
    .qp-pp-table th {
        text-align: left; font-size: 11px; letter-spacing: .4px; text-transform: uppercase;
        color: #5B6678; padding: 10px 14px; border-bottom: 2px solid #C8A965;
    }
    .qp-pp-table td {
        font-size: 13px; color: #1B2538; padding: 12px 14px; border-bottom: 1px solid #eef1f5;
    }

    /* 7. Location */
    .qp-landmarks { list-style: none; padding: 0; margin: 18px 0 0; }
    .qp-landmarks li {
        display: flex; justify-content: space-between; font-size: 13px;
        padding: 10px 0; border-bottom: 1px solid #eef1f5; color: #1B2538;
    }
    .qp-landmarks li span:last-child { color: #5B6678; }

    /* 8. Quadrant's View — highlighted callout (differentiator) */
    .qp-view-callout {
        background: #11203A; border-left: 4px solid #C8A965;
        border-radius: 4px; padding: 28px 30px; margin: 0 0 48px;
    }
    .qp-view-callout .qp-view-label {
        font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase;
        color: #C8A965; font-weight: 700; margin: 0 0 12px;
    }
    .qp-view-callout p {
        font-family: 'Cormorant Garamond', serif; font-size: 17px; line-height: 1.7;
        color: #fff; margin: 0; font-style: italic;
    }

    /* 9. Register Interest Sidebar */
    .qp-pd-sidebar { position: sticky; top: 100px; align-self: start; }
    .qp-pd-form-card {
        background: #F4F5F7; border-radius: 4px; padding: 28px; margin-bottom: 24px;
    }
    .qp-pd-form-card h3 {
        font-family: 'Cormorant Garamond', serif; font-size: 19px; color: #11203A; margin: 0 0 18px;
    }
    .qp-pd-form-card .form-group { margin-bottom: 14px; }
    .qp-pd-form-card label { font-size: 11px; letter-spacing: .4px; text-transform: uppercase; color: #5B6678; display: block; margin-bottom: 6px; }
    .qp-pd-form-card .form-control { border: 1px solid #dfe5ec; border-radius: 2px; padding: 10px 12px; font-size: 13px; width: 100%; }
    .qp-pd-checkbox { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #5B6678; margin-bottom: 18px; }
    .qp-pd-submit {
        width: 100%; background: #11203A; color: #fff; border: none; padding: 12px;
        font-size: 12px; letter-spacing: .4px; text-transform: uppercase; font-weight: 600;
        border-radius: 2px; cursor: pointer;
    }
    .qp-pd-contact-card { background: #fff; border: 1px solid #eef1f5; border-radius: 4px; padding: 22px; }
    .qp-pd-contact-card a {
        display: flex; align-items: center; gap: 10px; font-size: 13px; color: #1B2538;
        text-decoration: none; padding: 8px 0;
    }

    /* 10. Related Projects */
    .qp-related-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }

    /* 11. RERA Footer */
    .qp-rera-footer {
        background: #F4F5F7; border-top: 1px solid #eef1f5; padding: 24px 0;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
    }
    .qp-rera-footer .qp-rera-text { font-size: 12px; color: #5B6678; }
    .qp-rera-footer .qp-rera-qr img { height: 70px; width: 70px; object-fit: contain; }

    @media (max-width: 991px) {
        .qp-pd-grid { grid-template-columns: 1fr; }
        .qp-pd-facts { grid-template-columns: repeat(2, 1fr); }
        .qp-amenity-grid { grid-template-columns: repeat(2, 1fr); }
        .qp-fp-panel.active { grid-template-columns: 1fr; }
        .qp-related-grid { grid-template-columns: 1fr; }
        .qp-pd-sidebar { position: static; }
    }
    
    /* Location grid — 2 columns desktop, 1 column mobile */
.qp-location-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
    align-items: start;
}
@media (max-width: 767px) {
    .qp-location-grid {
        grid-template-columns: 1fr !important;
    }
    .qp-location-grid > div:first-child {
        margin-bottom: 16px;
    }
}
</style>
@endsection

@section('content')



{{-- ============================================================ --}}
{{-- 1. HERO GALLERY --}}
{{-- ============================================================ --}}
@php
    $heroImages = collect();
    if ($development->main_image) $heroImages->push($development->main_image);
    foreach ($gallery as $img) { $heroImages->push($img->image_path); }
    if ($heroImages->isEmpty()) $heroImages->push('assets/img/placeholder.jpg');

    $statusLabels = [
        'upcoming'           => 'New Launch',
        'launched'           => 'New Launch',
        'under_construction' => 'Under Construction',
        'completed'          => 'Nearing Handover',
    ];
@endphp

<section class="qp-pd-hero" id="qp-pd-hero">
    <div class="qp-pd-hero-track" id="qp-pd-hero-track">
        @foreach($heroImages as $img)
            <div class="qp-pd-hero-slide">
                <img src="{{ URL::to('') }}/public/{{ $img }}" alt="{{ $development->title }}"
                     onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
            </div>
        @endforeach
    </div>

    @if($heroImages->count() > 1)
        <div class="qp-pd-hero-nav qp-pd-hero-prev" onclick="qpHeroMove(-1)">&#8249;</div>
        <div class="qp-pd-hero-nav qp-pd-hero-next" onclick="qpHeroMove(1)">&#8250;</div>
        <div class="qp-pd-hero-dots" id="qp-pd-hero-dots">
            @foreach($heroImages as $i => $img)
                <span class="{{ $i == 0 ? 'active' : '' }}" onclick="qpHeroGoTo({{ $i }})"></span>
            @endforeach
        </div>
    @endif

    <div class="qp-pd-hero-overlay">
        <div class="qp-pd-main">
            @if($development->status)
                <span class="qp-pd-status-tag">{{ $statusLabels[$development->status] ?? 'New Launch' }}</span>
            @endif
            <h1>{{ $development->title }}</h1>
        </div>
    </div>
</section>
{{-- BREADCRUMB --}}
<div class="qp-pd-breadcrumb">
    <div class="qp-pd-main">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('developments.index') }}">Buy</a> /
        <a style="color:#11203A;">{{ $development->title }}</a>
    </div>
</div>
{{-- ============================================================ --}}
{{-- 2. KEY FACTS STRIP --}}
{{-- ============================================================ --}}
<section class="qp-pd-facts">
    <div class="qp-pd-fact">
        <p class="qp-fact-label">Starting Price</p>
        <p class="qp-fact-value">
            @if($development->price_from)
                From {{ $development->price_currency ?? 'AED' }} {{ number_format($development->price_from) }}
            @else
                On Request
            @endif
        </p>
    </div>
    <div class="qp-pd-fact">
        <p class="qp-fact-label">Handover</p>
        <p class="qp-fact-value">
            {{ $development->handover_date ? \Carbon\Carbon::parse($development->handover_date)->format('Q\Q Y') : '—' }}
        </p>
    </div>
    <div class="qp-pd-fact">
        <p class="qp-fact-label">Payment Plan</p>
        <p class="qp-fact-value">
            @php
                $ppHighlight = '—';
                if ($development->payment_plan) {
                    $lines = array_filter(array_map('trim', explode("\n", $development->payment_plan)));
                    $ppHighlight = $lines[0] ?? '—';
                }
            @endphp
            {{ $ppHighlight }}
        </p>
    </div>
    <div class="qp-pd-fact">
        <p class="qp-fact-label">Unit Types</p>
        <p class="qp-fact-value">{{ $development->property_types ?? '—' }}</p>
    </div>
    <!--<div class="qp-pd-fact">-->
    <!--    <p class="qp-fact-label">Size Range</p>-->
    <!--    <p class="qp-fact-value">-->
    <!--        @if($development->size_from && $development->size_to)-->
    <!--            {{ $development->size_from }}–{{ $development->size_to }} sq ft-->
    <!--        @else-->
    <!--            —-->
    <!--        @endif-->
    <!--    </p>-->
    <!--</div>-->
</section>

<div class="qp-pd-main">
    <div class="qp-pd-grid">

        {{-- ============================================================ --}}
        {{-- LEFT COLUMN --}}
        {{-- ============================================================ --}}
        <div>

            {{-- 3. OVERVIEW --}}
            @if($development->description || $development->short_description)
                <div class="qp-pd-section">
                    <h2>Overview</h2>
                    @if($development->description)
                        {!! $development->description !!}
                    @else
                        <p>{{ $development->short_description }}</p>
                    @endif
                    @if($development->community_name)
                        <p style="color:#5B6678; font-size:13px;">
                            <strong style="color:#1B2538;">Community:</strong> {{ $development->community_name }}
                        </p>
                    @endif
                </div>
            @endif

            {{-- 4. HIGHLIGHTS / AMENITIES --}}
            @if($amenities->count() > 0)
                <div class="qp-pd-section">
                    <h2>Highlights &amp; Amenities</h2>
                    <div class="qp-amenity-grid">
                        @foreach($amenities as $amenity)
                            <div class="qp-amenity-item">
                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                {{ $amenity->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 5. FLOOR PLANS (tabbed) --}}
            @if($floorPlans->count() > 0)
                <div class="qp-pd-section">
                    <h2>Floor Plans</h2>
                    <div class="qp-fp-tabs">
                        @foreach($floorPlans as $i => $plan)
                            <div class="qp-fp-tab {{ $i == 0 ? 'active' : '' }}" onclick="qpFpShow({{ $i }})">
                                {{ $plan->unit_type }}
                            </div>
                        @endforeach
                    </div>
                    @foreach($floorPlans as $i => $plan)
                        <div class="qp-fp-panel {{ $i == 0 ? 'active' : '' }}" id="qp-fp-panel-{{ $i }}">
                            <div class="qp-fp-img">
                                @if($plan->image)
                                    <img src="{{ URL::to('') }}/public/{{ $plan->image }}" alt="{{ $plan->unit_type }}">
                                @else
                                    <img src="{{ URL::to('') }}/public/assets/img/placeholder.jpg" alt="{{ $plan->unit_type }}">
                                @endif
                            </div>
                            <div class="qp-fp-meta">
                                <p><strong style="color:#1B2538;">{{ $plan->unit_type }}</strong></p>
                                @if($plan->size_sqft)
                                    <p>{{ $plan->size_sqft }} sq ft</p>
                                @endif
                                @if($plan->pdf_file)
                                    <a href="{{ URL::to('') }}/public/{{ $plan->pdf_file }}" target="_blank" class="qp-fp-download">
                                        Download Floor Plan (PDF)
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- 6. PAYMENT PLAN TABLE --}}
            @if($development->payment_plan)
                <div class="qp-pd-section">
                    <h2>Payment Plan</h2>
                    <table class="qp-pp-table">
                        <thead>
                            <tr>
                                <th>Milestone</th>
                                <th>%</th>
                                <!--<th>Timing</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ppLines = array_filter(array_map('trim', explode("\n", $development->payment_plan)));
                            @endphp
                            @foreach($ppLines as $line)
                                @php
                                    // Parse lines like "20% On Booking" into % + label
                                    preg_match('/(\d+%)/', $line, $matches);
                                    $pct = $matches[1] ?? '—';
                                    $label = trim(str_replace($pct, '', $line));
                                @endphp
                                <tr>
                                    <td>{{ $label ?: $line }}</td>
                                    <td>{{ $pct }}</td>
                                    <!--<td>—</td>-->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

           

        </div>

        {{-- ============================================================ --}}
        {{-- RIGHT COLUMN — SIDEBAR --}}
        {{-- ============================================================ --}}
        <div class="qp-pd-sidebar">

            {{-- 9. REGISTER INTEREST FORM --}}
            <div class="qp-pd-form-card">
                <h3>Register Your Interest</h3>

                @if(session('interest_success'))
                    <div class="alert alert-success">{{ session('interest_success') }}</div>
                @endif

                <form action="{{ route('inquiry.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="development_id" value="{{ $development->id }}">
                    <input type="hidden" name="inquiry_type" value="general">
                    <input type="hidden" name="source" value="project_detail">

                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" name="name" type="text" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" name="email" type="email" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input class="form-control" name="phone" type="text" required>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" name="notes" rows="3">Inquiry about: {{ $development->title }}</textarea>
                    </div>

                    <label class="qp-pd-checkbox">
                        <input type="checkbox" name="request_brochure" value="1">
                        Send me the brochure
                    </label>

                    <button type="submit" class="qp-pd-submit">Submit</button>
                </form>
            </div>

            <div class="qp-pd-contact-card">
                @if(setting('whatsapp_number'))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('whatsapp_number')) }}" target="_blank">
                        WhatsApp an Advisor
                    </a>
                @endif
                @if(setting('contact_phone'))
                    <a href="tel:{{ setting('contact_phone') }}">{{ setting('contact_phone') }}</a>
                @endif
                @if($development->brochure_pdf)
                    <a href="{{ URL::to('') }}/public/{{ $development->brochure_pdf }}" target="_blank">
                        Download the Brochure
                    </a>
                @endif
            </div>

        </div>
        
        
       

    </div>
     {{-- 7. LOCATION --}}
<div class="qp-pd-section">
    <h2>Location</h2>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:28px; align-items:start;" class="qp-location-grid">

        {{-- Left: Map --}}
        <div>
            @if($development->community_latitude && $development->community_longitude)
                <iframe
                    width="100%" height="340" frameborder="0"
                    style="border:0; border-radius:4px; display:block;"
                    src="https://www.google.com/maps?q={{ $development->community_latitude }},{{ $development->community_longitude }}&hl=en&z=14&output=embed"
                    allowfullscreen>
                </iframe>
            @else
                <div style="background:#F4F5F7; height:340px; border-radius:4px; display:flex; align-items:center; justify-content:center;">
                    <p style="color:#8a93a3; font-size:13px; margin:0;">Map coming soon</p>
                </div>
            @endif
        </div>

        {{-- Right: Community + Landmarks --}}
        <div>
            @if($development->community_name)
                <p style="font-family:'Cormorant Garamond',serif; font-size:18px; color:#11203A; font-weight:600; margin:0 0 16px;">
                    {{ $development->community_name }}, Dubai
                </p>
            @endif

            @if($development->nearby_landmarks)
                @php
                    $landmarkLines = array_filter(array_map('trim', explode("\n", $development->nearby_landmarks)));
                    $isLineByLine  = count($landmarkLines) > 1;
                @endphp
                @if($isLineByLine)
                    <ul class="qp-landmarks" style="margin:0;">
                        @foreach($landmarkLines as $landmark)
                            @php
                                $parts    = explode(' — ', $landmark);
                                $name     = $parts[0] ?? $landmark;
                                $distance = $parts[1] ?? '';
                            @endphp
                            <li>
                                <span>{{ $name }}</span>
                                <span style="color:#5B6678;">{{ $distance }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p style="font-size:14px; color:#5B6678; line-height:1.8; margin:0;">
                        {{ $development->nearby_landmarks }}
                    </p>
                @endif
            @else
                <p style="font-size:13px; color:#8a93a3; margin:0;">Nearby landmarks will be added shortly.</p>
            @endif
        </div>

    </div>
</div>
</div>

{{-- ============================================================ --}}
{{-- 10. RELATED PROJECTS --}}
{{-- ============================================================ --}}
@if($relatedDevelopments->count() > 0)
<section style="padding: 0 0 56px;">
    <div class="qp-pd-main">
        <h2 style="font-family:'Cormorant Garamond',serif; font-size:22px; color:#11203A; margin:0 0 26px;">
            Related Projects
        </h2>
        <div class="qp-related-grid">
            @foreach($relatedDevelopments as $related)
                <a href="{{ route('developments.show', $related->slug) }}" style="display:block; background:#fff; border-radius:4px; overflow:hidden; box-shadow:0 2px 14px rgba(11,29,58,.07); text-decoration:none;">
                    <div style="height:160px; overflow:hidden;">
                        @if($related->main_image)
                            <img src="{{ URL::to('') }}/public/{{ $related->main_image }}" alt="{{ $related->title }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <img src="{{ URL::to('') }}/public/assets/img/placeholder.jpg" alt="{{ $related->title }}" style="width:100%; height:100%; object-fit:cover;">
                        @endif
                    </div>
                    <div style="padding:16px 18px;">
                        <h4 style="font-family:'Cormorant Garamond',serif; font-size:16px; color:#1B2538; margin:0 0 6px;">{{ $related->title }}</h4>
                        @if($related->community_name)
                            <p style="font-size:12px; color:#5B6678; margin:0;">{{ $related->community_name }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================ --}}
{{-- 11. RERA COMPLIANCE FOOTER --}}
{{-- ============================================================ --}}
<!--<div class="qp-rera-footer">-->
<!--    <div class="qp-pd-main" style="display:flex; align-items:center; justify-content:space-between; width:100%; flex-wrap:wrap; gap:16px;">-->
<!--        <p class="qp-rera-text">-->
<!--            RERA Permit Number:-->
<!--            <strong style="color:#1B2538;">{{ $development->rera_permit ?: 'To be confirmed' }}</strong>-->
<!--            &nbsp;|&nbsp; Prices shown are indicative and subject to change.-->
<!--        </p>-->
<!--        @if($development->rera_qr_image)-->
<!--            <div class="qp-rera-qr">-->
<!--                <img src="{{ URL::to('') }}/public/{{ $development->rera_qr_image }}" alt="RERA QR Code">-->
<!--            </div>-->
<!--        @endif-->
<!--    </div>-->
<!--</div>-->

@endsection

@section('scripts')
<script>
    // ── Hero gallery carousel ──────────────────────────────
    let qpHeroIndex = 0;
    const qpHeroTrack = document.getElementById('qp-pd-hero-track');
    const qpHeroSlideCount = qpHeroTrack ? qpHeroTrack.children.length : 0;

    function qpHeroGoTo(i) {
        qpHeroIndex = i;
        if (qpHeroTrack) qpHeroTrack.style.transform = `translateX(-${qpHeroIndex * 100}%)`;
        document.querySelectorAll('#qp-pd-hero-dots span').forEach((dot, idx) => {
            dot.classList.toggle('active', idx === qpHeroIndex);
        });
    }
    function qpHeroMove(dir) {
        qpHeroIndex = (qpHeroIndex + dir + qpHeroSlideCount) % qpHeroSlideCount;
        qpHeroGoTo(qpHeroIndex);
    }

    // ── Floor plan tabs ─────────────────────────────────────
    function qpFpShow(i) {
        document.querySelectorAll('.qp-fp-tab').forEach((tab, idx) => tab.classList.toggle('active', idx === i));
        document.querySelectorAll('.qp-fp-panel').forEach((panel, idx) => panel.classList.toggle('active', idx === i));
    }
</script>
@endsection