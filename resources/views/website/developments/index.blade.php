@extends('website.layout.app')

@section('title', 'Off-Plan Properties for Sale in Dubai — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', 'Dubai\'s future, available today. Explore landmark off-plan developments — each one assessed, shortlisted, and advised on with precision.')

@section('head')
<style>
    .qp-offplan-banner {
        background: #11203A;
        padding: 64px 0 40px;
        text-align: center;
    }
    .qp-offplan-banner h1 {
        font-family: 'Cormorant Garamond', serif;
        color: #fff;
        font-size: 36px;
        margin: 0 0 14px;
    }
    .qp-offplan-banner p {
        color: #c7d2e2;
        font-size: 15px;
        max-width: 620px;
        margin: 0 auto;
    }

    .qp-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        padding: 28px 0;
        border-bottom: 1px solid #eef1f5;
        margin-bottom: 30px;
    }
    .qp-result-count {
        font-size: 13px;
        color: #5B6678;
    }
    .qp-sort-select {
        border: 1px solid #dfe5ec;
        border-radius: 2px;
        padding: 9px 14px;
        font-size: 13px;
        color: #1B2538;
        background: #fff;
    }
    .qp-filter-toggle {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #C8A965;
        color: #C8A965;
        background: transparent;
        padding: 9px 18px;
        font-size: 12px;
        letter-spacing: .4px;
        text-transform: uppercase;
        font-weight: 600;
        border-radius: 2px;
        cursor: pointer;
        transition: all .2s ease;
    }
    .qp-filter-toggle:hover {
        background: #C8A965;
        color: #11203A;
    }

    /* Filter Panel */
    .qp-filter-panel {
        background: #F4F5F7;
        border-radius: 4px;
        padding: 26px;
        margin-bottom: 36px;
        display: none;
    }
    .qp-filter-panel.qp-open { display: block; }
    .qp-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 18px;
    }
    .qp-filter-field label {
        display: block;
        font-size: 10px;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #5B6678;
        margin-bottom: 6px;
        font-weight: 600;
    }
    .qp-filter-field select,
    .qp-filter-field input {
        width: 100%;
        border: 1px solid #dfe5ec;
        border-radius: 2px;
        padding: 9px 10px;
        font-size: 13px;
        color: #1B2538;
        background: #fff;
    }
    .qp-filter-actions {
        display: flex;
        gap: 10px;
    }
    .qp-btn-navy-sm {
        background: #11203A;
        color: #fff;
        padding: 10px 22px;
        font-size: 12px;
        letter-spacing: .4px;
        text-transform: uppercase;
        font-weight: 600;
        border: none;
        border-radius: 2px;
        cursor: pointer;
    }
    .qp-btn-outline-sm {
        background: transparent;
        color: #5B6678;
        padding: 10px 22px;
        font-size: 12px;
        letter-spacing: .4px;
        text-transform: uppercase;
        font-weight: 600;
        border: 1px solid #d8dee5;
        border-radius: 2px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    /* Project Card Grid */
    .qp-projects-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 26px;
    }
    .qp-project-card {
        background: #fff;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 2px 14px rgba(11,29,58,.07);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .qp-project-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(11,29,58,.12);
    }
    .qp-project-img { position: relative; height: 210px; overflow: hidden; }
    .qp-project-img img { width: 100%; height: 100%; object-fit: cover; }
    .qp-status-tag {
        position: absolute;
        top: 14px; left: 14px;
        background: #C8A965;
        color: #11203A;
        font-size: 10px;
        letter-spacing: .5px;
        text-transform: uppercase;
        padding: 6px 12px;
        border-radius: 2px;
        font-weight: 700;
    }
    .qp-project-body { padding: 20px 22px 22px; }
    .qp-project-body h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 19px;
        font-weight: 700;
        color: #1B2538;
        margin: 0 0 4px;
    }
    .qp-project-developer {
        font-size: 12px;
        color: #5B6678;
        margin: 0 0 8px;
    }
    .qp-project-loc {
        font-size: 12px;
        color: #5B6678;
        margin: 0 0 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .qp-project-loc svg { width: 13px; height: 13px; stroke: #C8A965; flex-shrink: 0; }
    .qp-project-price {
        font-size: 14px;
        font-weight: 700;
        color: #11203A;
        margin: 0 0 14px;
    }
    .qp-project-facts {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        font-size: 11px;
        color: #5B6678;
        border-top: 1px solid #eef1f5;
        padding-top: 14px;
        margin-bottom: 16px;
    }
    .qp-project-rera {
        font-size: 10px;
        color: #8a93a3;
        margin: 0 0 14px;
    }
    .qp-view-btn {
        display: block;
        text-align: center;
        background: #11203A;
        color: #fff;
        padding: 11px;
        font-size: 12px;
        letter-spacing: .4px;
        text-transform: uppercase;
        font-weight: 600;
        border-radius: 2px;
        text-decoration: none;
        transition: background .2s ease;
    }
    .qp-view-btn:hover { background: #0B1D3A; color: #fff; }

    @media (max-width: 991px) {
        .qp-projects-grid { grid-template-columns: repeat(2, 1fr); }
        .qp-filter-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575px) {
        .qp-projects-grid { grid-template-columns: 1fr; }
        .qp-filter-grid { grid-template-columns: 1fr; }
        .qp-toolbar { flex-direction: column; align-items: stretch; }
    }
</style>
@endsection

@section('content')

{{-- PAGE HERO --}}
<section class="qp-offplan-banner">
    <div class="container">
        <h1>Buy</h1>
        <p>Dubai's future, available today. Explore landmark off-plan developments — each one assessed, shortlisted, and advised on with precision.</p>
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
                        <li><span>Buy</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 0 0 60px;">
    <div class="container">

        {{-- TOOLBAR: count, sort, filter toggle --}}
        <div class="qp-toolbar">
            <p class="qp-result-count">{{ $developments->total() }} projects found</p>

            <div style="display:flex; gap:12px; align-items:center;">
                <form method="GET" action="{{ route('developments.index') }}" id="qp-sort-form">
                    {{-- preserve all current filters when sort changes --}}
                    @foreach(request()->except(['sort','page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="sort" class="qp-sort-select" onchange="document.getElementById('qp-sort-form').submit();">
                        <option value="newest" {{ request('sort','newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low–High)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High–Low)</option>
                        <option value="handover_soonest" {{ request('sort') == 'handover_soonest' ? 'selected' : '' }}>Handover (Soonest)</option>
                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                    </select>
                </form>

                <button type="button" class="qp-filter-toggle" onclick="document.getElementById('qp-filter-panel').classList.toggle('qp-open');">
                    Filters
                </button>
            </div>
        </div>

        {{-- FILTER PANEL --}}
        <div class="qp-filter-panel @if(request()->anyFilled(['community_id','developer','property_type','bedrooms','min_price','max_price','handover_year','status'])) qp-open @endif" id="qp-filter-panel">
            <form method="GET" action="{{ route('developments.index') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">

                <div class="qp-filter-grid">

                    {{-- Community --}}
                    <div class="qp-filter-field">
                        <label>Community</label>
                        <select name="community_id">
                            <option value="">All Communities</option>
                            @foreach($communities as $community)
                                <option value="{{ $community->id }}" {{ request('community_id') == $community->id ? 'selected' : '' }}>
                                    {{ $community->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Developer --}}
                    <div class="qp-filter-field">
                        <label>Developer</label>
                        <select name="developer">
                            <option value="">All Developers</option>
                            @foreach($developers as $dev)
                                <option value="{{ $dev }}" {{ request('developer') == $dev ? 'selected' : '' }}>{{ $dev }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Property Type --}}
                    <div class="qp-filter-field">
                        <label>Property Type</label>
                        <select name="property_type">
                            <option value="">All Types</option>
                            <option value="Apartment" {{ request('property_type') == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="Townhouse" {{ request('property_type') == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
                            <option value="Villa" {{ request('property_type') == 'Villa' ? 'selected' : '' }}>Villa</option>
                            <option value="Penthouse" {{ request('property_type') == 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                            <option value="Plot" {{ request('property_type') == 'Plot' ? 'selected' : '' }}>Plot</option>
                        </select>
                    </div>

                    {{-- Bedrooms --}}
                    <div class="qp-filter-field">
                        <label>Bedrooms</label>
                        <select name="bedrooms">
                            <option value="">Any</option>
                            <option value="Studio" {{ request('bedrooms') == 'Studio' ? 'selected' : '' }}>Studio</option>
                            <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1</option>
                            <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2</option>
                            <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3</option>
                            <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4</option>
                            <option value="5" {{ request('bedrooms') == '5' ? 'selected' : '' }}>5+</option>
                        </select>
                    </div>

                    {{-- Price Min --}}
                    <div class="qp-filter-field">
                        <label>Min Price (AED)</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="e.g. 500000">
                    </div>

                    {{-- Price Max --}}
                    <div class="qp-filter-field">
                        <label>Max Price (AED)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="e.g. 5000000">
                    </div>

                    {{-- Handover Year --}}
                    <div class="qp-filter-field">
                        <label>Handover</label>
                        <select name="handover_year">
                            <option value="">Any Year</option>
                            @foreach($handoverYears as $year)
                                <option value="{{ $year }}" {{ request('handover_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="qp-filter-field">
                        <label>Status</label>
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>New Launch</option>
                            <option value="under_construction" {{ request('status') == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Nearing Handover</option>
                        </select>
                    </div>

                </div>

                <div class="qp-filter-actions">
                    <button type="submit" class="qp-btn-navy-sm">Apply Filters</button>
                    <a href="{{ route('developments.index') }}" class="qp-btn-outline-sm">Clear All</a>
                </div>
            </form>
        </div>

        {{-- PROJECT CARDS GRID --}}
        <div class="qp-projects-grid">

            @forelse($developments as $dev)
                @php
                    $statusLabels = [
                        'upcoming'           => 'New Launch',
                        'launched'           => 'New Launch',
                        'under_construction' => 'Under Construction',
                        'completed'          => 'Nearing Handover',
                    ];

                    // Payment plan highlight — pull first line as the "60/40"-style highlight
                    $paymentHighlight = null;
                    if ($dev->payment_plan) {
                        $lines = array_filter(array_map('trim', explode("\n", $dev->payment_plan)));
                        $paymentHighlight = $lines[0] ?? null;
                    }
                @endphp

                <div class="qp-project-card">
                    <div class="qp-project-img">
                        @if($dev->status)
                            <span class="qp-status-tag">{{ $statusLabels[$dev->status] ?? 'New Launch' }}</span>
                        @endif
                        @if($dev->main_image)
                            <img src="{{ URL::to('') }}/public/{{ $dev->main_image }}"
                                 alt="{{ $dev->title }}"
                                 onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
                        @else
                            <img src="{{ URL::to('') }}/public/assets/img/placeholder.jpg" alt="{{ $dev->title }}">
                        @endif
                    </div>

                    <div class="qp-project-body">
                        <h3>{{ $dev->title }}</h3>

                        @if($dev->developer_name)
                            <p class="qp-project-developer">{{ $dev->developer_name }}</p>
                        @endif

                        @if($dev->community_name)
                            <p class="qp-project-loc">
                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                {{ $dev->community_name }}
                            </p>
                        @endif

                        @if($dev->price_from)
                            <p class="qp-project-price">From {{ $dev->price_currency ?? 'AED' }} {{ number_format($dev->price_from) }}</p>
                        @endif

                        <div class="qp-project-facts">
                            @if($dev->bedroom_range)
                                <span>{{ $dev->bedroom_range }}</span>
                            @endif
                            @if($dev->handover_date)
                                <span>&middot; Handover {{ \Carbon\Carbon::parse($dev->handover_date)->format('Y') }}</span>
                            @endif
                            @if($paymentHighlight)
                                <span>&middot; {{ $paymentHighlight }}</span>
                            @endif
                        </div>

                        {{-- RERA compliance slot — populates once rera_permit is added per listing --}}
                        <!--@if($dev->rera_permit)-->
                        <!--    <p class="qp-project-rera">RERA Permit: {{ $dev->rera_permit }}</p>-->
                        <!--@else-->
                        <!--    <p class="qp-project-rera">RERA Permit: to be confirmed</p>-->
                        <!--@endif-->

                        <a href="{{ route('developments.show', $dev->slug) }}" class="qp-view-btn">View Project</a>
                    </div>
                </div>

            @empty
                <p style="grid-column: 1/-1; text-align:center; color:#5B6678; padding:40px 0;">
                    No projects match your filters. <a href="{{ route('developments.index') }}" style="color:#C8A965;">Clear filters</a> to see all projects.
                </p>
            @endforelse

        </div>

        {{-- PAGINATION --}}
        @if($developments->hasPages())
        <div class="pagination-main" style="margin-top:44px;">
            <div class="row">
                <div class="col-12">
                    <div class="pagination-container">
                        <ul>
                            @if($developments->onFirstPage())
                                <li class="prev disabled"><span>Prev</span></li>
                            @else
                                <li class="prev"><a href="{{ $developments->previousPageUrl() }}">Prev</a></li>
                            @endif

                            @foreach($developments->getUrlRange(1, $developments->lastPage()) as $page => $url)
                                @if($page == $developments->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if($developments->hasMorePages())
                                <li class="next"><a href="{{ $developments->nextPageUrl() }}">Next</a></li>
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