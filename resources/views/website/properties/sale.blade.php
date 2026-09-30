@extends('website.layout.app')

@section('title', 'Buy Luxury Properties in Dubai — ' . setting('site_name', 'Quadrant Properties Dubai'))
@section('meta_description', 'Explore luxury properties for sale in Dubai with ' . setting('site_name', 'Quadrant Properties Dubai') . '. Discover exclusive villas, penthouses, and high-end homes in prime locations.')

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
         style="background-image: url('{{ URL::to('') }}/public/assets/img/buy.png');">
    <div class="container">
        <div class="slider-info">
            <div class="BannerBox">

                <div class="banner-heading text-center">
                    <h1>Buy Luxury Properties in Dubai</h1>
                </div>

                {{-- Banner Search Form --}}
                <div class="banner-form mobile-none">
                    <form action="{{ route('properties.search') }}" method="GET">
                        <input type="hidden" name="listing_type" value="sale">
                        <div class="BookingBox">
                            <div class="BookingLocation">

                                <div class="BookingFrom">
                                    <input type="text" name="search"
                                           class="form-control"
                                           placeholder="Search community, area or property..."
                                           value="{{ request('search') }}">
                                </div>

                                <div class="BookingFrom p-0">
                                    <select class="form-control" name="property_type_id">
                                        <option value="">Property Type</option>
                                        @foreach($propertyTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ request('property_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="BookingFrom p-0">
                                    <select class="form-control" name="bedrooms">
                                        <option value="">Bedrooms</option>
                                        <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ request('bedrooms') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ request('bedrooms') == '6' ? 'selected' : '' }}>6</option>
                                        <option value="7" {{ request('bedrooms') == '7' ? 'selected' : '' }}>7+</option>
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
                        <li><a href="{{ route('properties.sale') }}">Buy</a></li>
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
        <div class="row" style="background-image: url('{{ URL::to('') }}/public/assets/img/buy.png');">
            <div class="col-lg-6">
                <div class="heading-pnel fff m-0">
                    <h2 class="m-0">Luxury Homes for Sale by {{ setting('site_name', 'Quadrant Properties Dubai') }}</h2>
                    <p>Discover the very best apartments, penthouses, townhouses, and villas for sale across Dubai. Located in some of the city's most sought-after communities, these homes are designed to suit every lifestyle. From sleek, modern penthouses with panoramic skyline views to grand villas in exclusive, gated enclaves, our curated selection of luxury properties promises both elegance and comfort.</p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- PROPERTIES LISTING --}}
{{-- ============================================================ --}}
<section class="space position-relative">
    <div class="container">

        {{-- Top Bar: Count + Filter Trigger --}}
        <div class="listing-top-area">
            <div class="row">
                <div class="col-12">
                    <div class="listing-top-area-container">
                        <div class="item-counter">
                            <p>Results: <span>{{ $properties->total() }} Properties</span></p>
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

        {{-- Property Cards Grid --}}
        <div class="cards-main">
            <div class="row">

                @forelse($properties as $property)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card-box">

                            <figure>
                                @if($property->type_name)
                                    <div class="VillaText"><p>{{ $property->type_name }}</p></div>
                                @endif

                                <a href="{{ route('properties.show', $property->slug) }}">
                                    @if($property->main_image)
                                        <img loading="lazy"
                                             src="{{ URL::to('') }}/public/{{ $property->main_image }}"
                                             alt="{{ $property->title }}"
                                             onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
                                    @else
                                        <img loading="lazy"
                                             src="{{ URL::to('') }}/public/assets/img/placeholder.jpg"
                                             alt="{{ $property->title }}">
                                    @endif
                                </a>
                            </figure>

                            <figcaption>
                                <a href="{{ route('properties.show', $property->slug) }}">
                                    <h3>{{ $property->title }}</h3>

                                    @if($property->community_name)
                                        <p>
                                            <img src="{{ URL::to('') }}/public/assets/img/hotel/map.svg" alt="Map Icon">
                                            {{ $property->community_name }}
                                        </p>
                                    @endif

                                    <div class="HotelViews">
                                        <ul>
                                            @if($property->area_sqft)
                                                <li>
                                                    <img src="{{ URL::to('') }}/public/assets/img/hotel/1.svg" alt="Area icon">
                                                    {{ number_format($property->area_sqft) }} SQ FT
                                                </li>
                                            @endif
                                            @if($property->bedrooms !== null)
                                                <li>
                                                    <img src="{{ URL::to('') }}/public/assets/img/hotel/2.svg" alt="Bed icon">
                                                    {{ $property->bedrooms }}
                                                </li>
                                            @endif
                                            @if($property->bathrooms !== null)
                                                <li>
                                                    <img src="{{ URL::to('') }}/public/assets/img/hotel/3.svg" alt="Bath icon">
                                                    {{ $property->bathrooms }}
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    @if($property->price_on_request)
                                        <h6><span>Price on Request</span></h6>
                                    @else
                                        <h6><span>{{ $property->price_currency }}</span> {{ number_format($property->price) }}/-</h6>
                                    @endif

                                </a>
                            </figcaption>

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No properties found matching your search.</p>
                        <a href="{{ route('properties.sale') }}" class="green-btn mt-3">Clear Filters</a>
                    </div>
                @endforelse

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- PAGINATION --}}
        {{-- ============================================================ --}}
        @if($properties->hasPages())
        <div class="pagination-main">
            <div class="row">
                <div class="col-12">
                    <div class="pagination-container">
                        <ul>

                            {{-- Previous --}}
                            @if($properties->onFirstPage())
                                <li class="prev disabled"><span>Prev</span></li>
                            @else
                                <li class="prev"><a href="{{ $properties->previousPageUrl() }}">Prev</a></li>
                            @endif

                            {{-- Page Numbers --}}
                            @foreach($properties->getUrlRange(1, $properties->lastPage()) as $page => $url)
                                @if($page == $properties->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if($properties->hasMorePages())
                                <li class="next"><a href="{{ $properties->nextPageUrl() }}">Next</a></li>
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
{{-- FILTER SIDEBAR (offcanvas) --}}
{{-- ============================================================ --}}
<form method="GET" action="{{ route('properties.sale') }}">

    <div id="filters-sidebar" class="sidenav">

        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
            <img src="{{ URL::to('') }}/public/assets/img/cross.svg" alt="Close" />
        </a>

        <div class="filter-head">
            <h2>Filters</h2>
        </div>

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
                    </ul>
                </div>
            </div>

            {{-- Property Type --}}
            <div class="filter-box">
                <h4>Property Type</h4>
                <div class="filter-checkboxes">
                    <ul>
                        @foreach($propertyTypes as $type)
                            <li>
                                <input class="styled-checkbox"
                                       id="type_{{ $type->id }}"
                                       name="property_type_id"
                                       type="radio"
                                       value="{{ $type->id }}"
                                       {{ request('property_type_id') == $type->id ? 'checked' : '' }}>
                                <label for="type_{{ $type->id }}">{{ $type->name }}</label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Bedrooms --}}
            <div class="filter-box">
                <h4>Bedrooms</h4>
                <div class="filter-checkboxes">
                    <ul>
                        @foreach(['1','2','3','4','5','6','7'] as $bed)
                            <li>
                                <input class="styled-checkbox"
                                       id="bed_{{ $bed }}"
                                       name="bedrooms"
                                       type="radio"
                                       value="{{ $bed }}"
                                       {{ request('bedrooms') == $bed ? 'checked' : '' }}>
                                <label for="bed_{{ $bed }}">{{ $bed == '7' ? '7+' : $bed }}</label>
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
                            <div class="slider">
                                <div class="progress"></div>
                            </div>
                            <div class="range-input">
                                <input type="range" class="range-min" min="0" max="100000000" value="{{ request('min_price', 0) }}" step="100000">
                                <input type="range" class="range-max" min="0" max="100000000" value="{{ request('max_price', 100000000) }}" step="100000">
                            </div>
                            <div class="price-input">
                                <div class="field">
                                    <span>Min</span>
                                    <input type="number" value="{{ request('min_price') }}" class="input-min" name="min_price">
                                </div>
                                <div class="separator">-</div>
                                <div class="field">
                                    <span>Max</span>
                                    <input type="number" value="{{ request('max_price') }}" class="input-max" name="max_price">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Buttons --}}
            <div class="filter-box">
                <div class="menu-btn-grup filter-btn-grp p-0">
                    <a class="btn border-btn" href="{{ route('properties.sale') }}">Clear Filters</a>
                    <button type="submit" class="green-btn">Apply Filters</button>
                </div>
            </div>

        </div>
    </div>

</form>
<div id="filter-overlay"></div>


{{-- ============================================================ --}}
{{-- SEARCH MODAL (Mobile) --}}
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
                        <ul class="nav nav-tabs" id="modalTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#Buy-two" role="tab">Buy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Rent-two" role="tab">Rent</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">

                        {{-- Modal Buy --}}
                        <div id="Buy-two" class="tab-pane fade show active" role="tabpanel">
                            <form action="{{ route('properties.search') }}" method="GET">
                                <input type="hidden" name="listing_type" value="sale">
                                <div class="BookingBox">
                                    <div class="BookingLocation">
                                        <div class="BookingFrom">
                                            <input type="text" name="search" class="form-control" placeholder="Search...">
                                        </div>
                                        <div class="BookingFrom p-0">
                                            <select name="property_type_id" class="form-control">
                                                <option value="">Property Type</option>
                                                @foreach($propertyTypes as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="BookingFrom p-0">
                                            <select class="form-control" name="bedrooms">
                                                <option value="">Bedrooms</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7+</option>
                                            </select>
                                        </div>
                                        <div class="BookingFromBtn">
                                            <button type="submit">
                                                <img width="17" height="17" loading="lazy"
                                                     src="{{ URL::to('') }}/public/assets/img/search.svg" alt="Search">
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- Modal Rent --}}
                        <div id="Rent-two" class="tab-pane fade" role="tabpanel">
                            <form action="{{ route('properties.search') }}" method="GET">
                                <input type="hidden" name="listing_type" value="rent">
                                <div class="BookingBox">
                                    <div class="BookingLocation">
                                        <div class="BookingFrom">
                                            <input type="text" name="search" class="form-control" placeholder="Search...">
                                        </div>
                                        <div class="BookingFrom p-0">
                                            <select name="property_type_id" class="form-control">
                                                <option value="">Property Type</option>
                                                @foreach($propertyTypes as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="BookingFrom p-0">
                                            <select class="form-control" name="bedrooms">
                                                <option value="">Bedrooms</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7+</option>
                                            </select>
                                        </div>
                                        <div class="BookingFromBtn">
                                            <button type="submit">
                                                <img width="17" height="17" loading="lazy"
                                                     src="{{ URL::to('') }}/public/assets/img/search.svg" alt="Search">
                                                Search
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