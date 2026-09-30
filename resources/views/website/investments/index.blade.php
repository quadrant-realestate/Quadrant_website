@extends('website.layout.app')

@section('title', 'Exclusive Investment Opportunities in Dubai — ' . setting('site_name', 'Quadrant Properties Dubai'))
@section('meta_description', 'Discover exclusive investment opportunities in Dubai with ' . setting('site_name', 'Quadrant Properties Dubai') . '. Prime plots, full buildings, hotels, offices and high-potential assets.')

@section('head')
<style>
    .banner.inr-banner {
        min-height: 300px;
        padding-top: 72px;
        padding-bottom: 72px;
        border-radius: 0;
        position: relative;
        overflow: unset;
        height: auto;
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')

{{-- ============================================================ --}}
{{-- INNER BANNER WITH SEARCH FORM --}}
{{-- ============================================================ --}}
<section class="banner inr-banner"
         style="background-image: url('{{ URL::to('') }}/public/assets/images/image_17507627170.jpg');">
    <div class="container">
        <div class="slider-info">
            <div class="BannerBox">

                <div class="banner-heading text-center">
                    <h1>Investments</h1>
                </div>

                <div class="banner-form mobile-none">
                    <form action="{{ route('investments.index') }}" method="GET">
                        <div class="BookingBox">
                            <div class="BookingLocation">

                                <div class="BookingFrom">
                                    <input type="text" name="search"
                                           class="form-control"
                                           placeholder="Search country and city..."
                                           value="{{ request('search') }}">
                                </div>

                                <div class="BookingFrom p-0">
                                    <select class="form-control" name="investment_type">
                                        <option value="">Property Type</option>
                                        @foreach($investmentTypes as $type)
                                            <option value="{{ $type }}"
                                                {{ request('investment_type') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="BookingFrom p-0">
                                    <select class="form-control" name="community_id">
                                        <option value="">Community</option>
                                        @foreach($communities as $community)
                                            <option value="{{ $community->id }}"
                                                {{ request('community_id') == $community->id ? 'selected' : '' }}>
                                                {{ $community->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="BookingFromBtn">
                                    <button type="submit">
                                        <img src="{{ URL::to('') }}/public/assets/img/search.svg" alt="Search">
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- BREADCRUMB --}}
{{-- ============================================================ --}}
<section class="breadcrumb-sec">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-container">
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('investments.index') }}">Investments</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- CTA STRIP --}}
{{-- ============================================================ --}}
<section class="CTA-strip space pb-0">
    <div class="container">
        <div class="row" style="background-image: url('{{ URL::to('') }}/public/assets/images/image_17507627170.jpg');">
            <div class="col-lg-6">
                <div class="heading-pnel fff m-0">
                    <h2 class="m-0">Exclusive Investment Opportunities in Dubai</h2>
                    <p>Dubai's dynamic landscape stands at the crossroads of ambition and innovation, offering a wealth of opportunities for forward-thinking investors and developers. Our curated selection showcases an impressive breadth of high-potential assets — from prime plots and full buildings to exclusive hotels, offices, and entire floors. Each listing is meticulously chosen to reflect the region's rapid growth, strategic value, and enduring appeal.</p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- INVESTMENTS LISTING --}}
{{-- ============================================================ --}}
<section class="space position-relative">
    <div class="container">

        {{-- Top Bar --}}
        <div class="listing-top-area">
            <div class="row">
                <div class="col-12">
                    <div class="listing-top-area-container">
                        <div class="item-counter">
                            <p>Results: <span>{{ $investments->total() }} Properties</span></p>
                        </div>
                        <div class="filter-trigger">
                            <a href="javascript:void(0)" onclick="openNav()" class="green-btn">
                                <img src="{{ URL::to('') }}/public/assets/img/filter.svg" alt="Filter"> Filters
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Investment Cards --}}
        <div class="cards-main">
            <div class="row">

                @forelse($investments as $investment)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card-box">

                            <figure>
                                @if($investment->investment_type)
                                    <div class="VillaText"><p>{{ $investment->investment_type }}</p></div>
                                @endif
                                <a href="{{ route('investments.show', $investment->slug) }}">
                                    @if($investment->main_image)
                                        <img loading="lazy"
                                             src="{{ URL::to('') }}/public/{{ $investment->main_image }}"
                                             alt="{{ $investment->title }}"
                                             onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
                                    @else
                                        <img loading="lazy"
                                             src="{{ URL::to('') }}/public/assets/img/placeholder.jpg"
                                             alt="{{ $investment->title }}">
                                    @endif
                                </a>
                            </figure>

                            <figcaption>
                                <a href="{{ route('investments.show', $investment->slug) }}">
                                    <h3>{{ $investment->title }}</h3>

                                    @if($investment->community_name)
                                        <p>
                                            <img src="{{ URL::to('') }}/public/assets/img/hotel/map.svg" alt="Map Icon">
                                            {{ $investment->community_name }}
                                        </p>
                                    @endif

                                    @if($investment->property_area_sqft || $investment->roi_percentage)
                                        <div class="HotelViews">
                                            <ul>
                                                @if($investment->property_area_sqft)
                                                    <li>
                                                        <img src="{{ URL::to('') }}/public/assets/img/hotel/1.svg" alt="Area icon">
                                                        {{ number_format($investment->property_area_sqft) }} SQ FT
                                                    </li>
                                                @endif
                                                @if($investment->roi_percentage)
                                                    <li>
                                                        <img src="{{ URL::to('') }}/public/assets/img/hotel/2.svg" alt="ROI icon">
                                                        {{ $investment->roi_percentage }}% ROI
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif

                                    @if($investment->price)
                                        <h6>
                                            <span>{{ $investment->price_currency ?? 'AED' }}</span>
                                            {{ number_format($investment->price) }}/-
                                        </h6>
                                    @endif

                                </a>
                            </figcaption>

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No investment opportunities found matching your search.</p>
                        <a href="{{ route('investments.index') }}" class="green-btn mt-3">Clear Filters</a>
                    </div>
                @endforelse

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- PAGINATION --}}
        {{-- ============================================================ --}}
        @if($investments->hasPages())
        <div class="pagination-main">
            <div class="row">
                <div class="col-12">
                    <div class="pagination-container">
                        <ul>

                            @if($investments->onFirstPage())
                                <li class="prev disabled"><span>Prev</span></li>
                            @else
                                <li class="prev"><a href="{{ $investments->previousPageUrl() }}">Prev</a></li>
                            @endif

                            @foreach($investments->getUrlRange(1, $investments->lastPage()) as $page => $url)
                                @if($page == $investments->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if($investments->hasMorePages())
                                <li class="next"><a href="{{ $investments->nextPageUrl() }}">Next</a></li>
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


{{-- ============================================================ --}}
{{-- REGISTER INTEREST SECTION --}}
{{-- ============================================================ --}}
<section class="list-us-sec space bg-grey pt-0">
    <div class="container">
        <div class="row align-items-end">

            <div class="col-lg-6">
                <div class="list-us-content">
                    <h2>Let Us Find Your Perfect Investment</h2>
                    <p>Transform your aspirations into tangible gains through our bespoke property sourcing service. Entrust us with your investment criteria — from growth targets and geographic preferences to nuanced asset class considerations — and allow our seasoned experts to orchestrate a global search for premier opportunities. With a profound understanding of market dynamics, an eye for hidden gems, and access to exclusive listings, we're poised to curate a shortlist tailored precisely to your vision. Our commitment extends beyond mere introductions: we provide incisive analysis, strategic guidance, and diligent support at every step.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="list-from">
                    <h2 class="mb-2">Register your interest!</h2>

                    @if(session('interest_success'))
                        <div class="alert alert-success">{{ session('interest_success') }}</div>
                    @endif

                    <form id="contactForm" action="{{ route('inquiry.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="inquiry_type" value="general">
                        <input type="hidden" name="source" value="investments_page">

                        <div class="form-group">
                            <label>Full Name</label>
                            <input class="form-control" name="name" type="text"
                                   placeholder="John Doe" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" name="email" type="email"
                                   placeholder="example@gmail.com" required>
                        </div>

                        <div class="form-group">
                            <label>Contact Number</label>
                            <input class="form-control" name="phone" type="text"
                                   placeholder="+971 50 000 0000" required>
                        </div>

                        <button type="submit" class="green-btn submit-btn">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- FILTER SIDEBAR --}}
{{-- ============================================================ --}}
<form method="GET" action="{{ route('investments.index') }}">

    <div id="filters-sidebar" class="sidenav">

        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
            <img src="{{ URL::to('') }}/public/assets/img/cross.svg" alt="Close" />
        </a>

        <div class="filter-head"><h2>Filters</h2></div>

        <div class="filter-boxes">

            {{-- Sort By --}}
            <div class="filter-box">
                <h4>Sort By</h4>
                <div class="filter-checkboxes">
                    <ul>
                        <li>
                            <input class="styled-checkbox" id="sort_asc"
                                   name="sort" type="radio" value="price_asc"
                                   {{ request('sort') == 'price_asc' ? 'checked' : '' }}>
                            <label for="sort_asc">Price: Low to High</label>
                        </li>
                        <li>
                            <input class="styled-checkbox" id="sort_desc"
                                   name="sort" type="radio" value="price_desc"
                                   {{ request('sort') == 'price_desc' ? 'checked' : '' }}>
                            <label for="sort_desc">Price: High to Low</label>
                        </li>
                        <li>
                            <input class="styled-checkbox" id="sort_roi"
                                   name="sort" type="radio" value="roi_desc"
                                   {{ request('sort') == 'roi_desc' ? 'checked' : '' }}>
                            <label for="sort_roi">Highest ROI</label>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Property Type --}}
            <div class="filter-box">
                <h4>Property Type</h4>
                <div class="filter-checkboxes">
                    <ul>
                        @foreach($investmentTypes as $type)
                            <li>
                                <input class="styled-checkbox"
                                       id="type_{{ Str::slug($type) }}"
                                       name="investment_type"
                                       type="radio"
                                       value="{{ $type }}"
                                       {{ request('investment_type') == $type ? 'checked' : '' }}>
                                <label for="type_{{ Str::slug($type) }}">{{ $type }}</label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Community --}}
            <div class="filter-box">
                <h4>Community</h4>
                <div class="filter-checkboxes">
                    <ul>
                        @foreach($communities as $community)
                            <li>
                                <input class="styled-checkbox"
                                       id="comm_{{ $community->id }}"
                                       name="community_id"
                                       type="radio"
                                       value="{{ $community->id }}"
                                       {{ request('community_id') == $community->id ? 'checked' : '' }}>
                                <label for="comm_{{ $community->id }}">{{ $community->name }}</label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Price Range --}}
            <div class="filter-box">
                <h4>Price Range</h4>
                <div class="filter-checkboxes">
                    <div class="d-flex">
                        <div class="wrapper">
                            <div class="slider"><div class="progress"></div></div>
                            <div class="range-input">
                                <input type="range" class="range-min" min="0" max="1000000000"
                                       value="{{ request('min_price', 0) }}" step="1000000">
                                <input type="range" class="range-max" min="0" max="1000000000"
                                       value="{{ request('max_price', 1000000000) }}" step="1000000">
                            </div>
                            <div class="price-input">
                                <div class="field">
                                    <span>Min</span>
                                    <input type="number" value="{{ request('min_price') }}"
                                           class="input-min" name="min_price">
                                </div>
                                <div class="separator">-</div>
                                <div class="field">
                                    <span>Max</span>
                                    <input type="number" value="{{ request('max_price') }}"
                                           class="input-max" name="max_price">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Buttons --}}
            <div class="filter-box">
                <div class="menu-btn-grup filter-btn-grp p-0">
                    <a class="btn border-btn" href="{{ route('investments.index') }}">Clear Filters</a>
                    <button type="submit" class="green-btn">Apply Filters</button>
                </div>
            </div>

        </div>
    </div>

</form>
<div id="filter-overlay"></div>


{{-- ============================================================ --}}
{{-- MOBILE SEARCH MODAL --}}
{{-- ============================================================ --}}
<div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <div class="search-container">
                    <div class="TopTabsBar">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#Buy-two" role="tab">Buy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Rent-two" role="tab">Rent</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="Buy-two" class="tab-pane fade show active" role="tabpanel">
                            <form action="{{ route('properties.search') }}" method="GET">
                                <input type="hidden" name="listing_type" value="sale">
                                <div class="BookingBox">
                                    <div class="BookingLocation">
                                        <div class="BookingFrom">
                                            <input type="text" name="search" class="form-control" placeholder="Search...">
                                        </div>
                                        <div class="BookingFrom p-0">
                                            <select class="form-control" name="bedrooms">
                                                <option value="">Bedrooms</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5+</option>
                                            </select>
                                        </div>
                                        <div class="BookingFromBtn">
                                            <button type="submit">
                                                <img width="17" height="17"
                                                     src="{{ URL::to('') }}/public/assets/img/search.svg"
                                                     alt="Search"> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="Rent-two" class="tab-pane fade" role="tabpanel">
                            <form action="{{ route('properties.search') }}" method="GET">
                                <input type="hidden" name="listing_type" value="rent">
                                <div class="BookingBox">
                                    <div class="BookingLocation">
                                        <div class="BookingFrom">
                                            <input type="text" name="search" class="form-control" placeholder="Search...">
                                        </div>
                                        <div class="BookingFrom p-0">
                                            <select class="form-control" name="bedrooms">
                                                <option value="">Bedrooms</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5+</option>
                                            </select>
                                        </div>
                                        <div class="BookingFromBtn">
                                            <button type="submit">
                                                <img width="17" height="17"
                                                     src="{{ URL::to('') }}/public/assets/img/search.svg"
                                                     alt="Search"> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
