@extends('website.layout.app')

@section('title', $community->name . ' — ' . setting('site_name', 'Quadrant Properties Dubai'))
@section('meta_description', Str::limit(strip_tags($community->description), 160) ?: 'Explore properties in ' . $community->name . ' with ' . setting('site_name', 'Quadrant Properties Dubai') . '. Luxury homes, apartments and villas.')
@section('og_image', URL::to('') . '/public/' . $community->image)
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
{{-- INNER BANNER --}}
{{-- ============================================================ --}}
<section class="banner inr-banner"
         style="background-image: url('{{ URL::to('') }}/public/{{ $community->banner_image ?? $community->image }}');">
    <div class="container">
        <div class="slider-info">
            <div class="BannerBox">
                <div class="banner-heading text-center">
                    <h1>{{ $community->name }}</h1>
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
                        <li><a href="{{ route('communities.index') }}">Communities</a></li>
                        <li><span>{{ $community->name }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- ABOUT COMMUNITY --}}
{{-- ============================================================ --}}
@if($community->description)
<section class="space pb-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="content-wrapper">
                    <h2>About {{ $community->name }}</h2>
                    <p>{{ $community->description }}</p>
                    @if($community->city)
                        <p>
                            <img loading="lazy" decoding="async"
                                 src="{{ URL::to('') }}/public/assets/img/hotel/map.svg"
                                 alt="Location">
                            &nbsp; {{ $community->city }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif


{{-- ============================================================ --}}
{{-- PROPERTIES IN THIS COMMUNITY --}}
{{-- ============================================================ --}}
<!--@if($properties->count() > 0)-->
<!--<section class="space position-relative">-->
<!--    <div class="container">-->

<!--        <div class="heading-pnel HeadingMiddleBorder">-->
<!--            <div class="row">-->
<!--                <div class="col-lg-8 col-12">-->
<!--                    <h2 class="m-0">Properties in {{ $community->name }}</h2>-->
<!--                </div>-->
<!--                <div class="col-lg-4 col-12 mobile-none">-->
<!--                    <div class="head-btn">-->
<!--                        <a href="{{ route('properties.search', ['community_id' => $community->id]) }}" class="light-btn ml-auto">-->
<!--                            View All Properties-->
<!--                        </a>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

<!--        <div class="cards-main">-->
<!--            <div class="row">-->

<!--                @foreach($properties as $property)-->
<!--                    <div class="col-lg-3 col-md-6 col-12">-->
<!--                        <div class="card-box">-->
<!--                            <figure>-->
<!--                                @if($property->type_name)-->
<!--                                    <div class="VillaText"><p>{{ $property->type_name }}</p></div>-->
<!--                                @endif-->
<!--                                <a href="{{ route('properties.show', $property->slug) }}">-->
<!--                                    @if($property->main_image)-->
<!--                                        <img loading="lazy"-->
<!--                                             src="{{ URL::to('') }}/public/{{ $property->main_image }}"-->
<!--                                             alt="{{ $property->title }}"-->
<!--                                             onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">-->
<!--                                    @else-->
<!--                                        <img loading="lazy"-->
<!--                                             src="{{ URL::to('') }}/public/assets/img/placeholder.jpg"-->
<!--                                             alt="{{ $property->title }}">-->
<!--                                    @endif-->
<!--                                </a>-->
<!--                            </figure>-->
<!--                            <figcaption>-->
<!--                                <a href="{{ route('properties.show', $property->slug) }}">-->
<!--                                    <h3>{{ $property->title }}</h3>-->
<!--                                    <div class="HotelViews">-->
<!--                                        <ul>-->
<!--                                            @if($property->area_sqft)-->
<!--                                                <li>-->
<!--                                                    <img src="{{ URL::to('') }}/public/assets/img/hotel/1.svg" alt="Area">-->
<!--                                                    {{ number_format($property->area_sqft) }} SQ FT-->
<!--                                                </li>-->
<!--                                            @endif-->
<!--                                            @if($property->bedrooms !== null)-->
<!--                                                <li>-->
<!--                                                    <img src="{{ URL::to('') }}/public/assets/img/hotel/2.svg" alt="Beds">-->
<!--                                                    {{ $property->bedrooms }}-->
<!--                                                </li>-->
<!--                                            @endif-->
<!--                                            @if($property->bathrooms !== null)-->
<!--                                                <li>-->
<!--                                                    <img src="{{ URL::to('') }}/public/assets/img/hotel/3.svg" alt="Baths">-->
<!--                                                    {{ $property->bathrooms }}-->
<!--                                                </li>-->
<!--                                            @endif-->
<!--                                        </ul>-->
<!--                                    </div>-->
<!--                                    @if($property->price_on_request)-->
<!--                                        <h6><span>Price on Request</span></h6>-->
<!--                                    @else-->
<!--                                        <h6><span>{{ $property->price_currency }}</span> {{ number_format($property->price) }}/-</h6>-->
<!--                                    @endif-->
<!--                                </a>-->
<!--                            </figcaption>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                @endforeach-->

<!--            </div>-->
<!--        </div>-->

<!--    </div>-->
<!--</section>-->
<!--@endif-->


{{-- ============================================================ --}}
{{-- DEVELOPMENTS IN THIS COMMUNITY --}}
{{-- ============================================================ --}}
<!--@if($developments->count() > 0)-->
<!--<section class="new-development-sec space pt-0">-->
<!--    <div class="container">-->

<!--        <div class="heading-pnel HeadingMiddleBorder">-->
<!--            <div class="row">-->
<!--                <div class="col-lg-8 col-12">-->
<!--                    <h2 class="m-0">Developments in {{ $community->name }}</h2>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

<!--        <div class="row">-->
<!--            <div class="col-12">-->
<!--                <div class="cards-main">-->
<!--                    <div class="owl-carousel" id="dev-slider">-->

<!--                        @foreach($developments as $dev)-->
<!--                            <div class="item">-->
<!--                                <div class="new-development card-box">-->
<!--                                    <figure>-->
<!--                                        <a href="{{ route('developments.show', $dev->slug) }}">-->
<!--                                            @if($dev->main_image)-->
<!--                                                <img width="300" height="auto" loading="lazy"-->
<!--                                                     src="{{ URL::to('') }}/public/{{ $dev->main_image }}"-->
<!--                                                     alt="{{ $dev->title }}">-->
<!--                                            @else-->
<!--                                                <img width="300" height="auto" loading="lazy"-->
<!--                                                     src="{{ URL::to('') }}/public/assets/img/placeholder.jpg"-->
<!--                                                     alt="{{ $dev->title }}">-->
<!--                                            @endif-->
<!--                                        </a>-->
<!--                                    </figure>-->
<!--                                    <figcaption>-->
<!--                                        <a href="{{ route('developments.show', $dev->slug) }}">-->
<!--                                            <h3>{{ $dev->title }}</h3>-->
<!--                                            @if($dev->price_from)-->
<!--                                                <h6>-->
<!--                                                    <span>From {{ $dev->price_currency ?? 'AED' }}</span>-->
<!--                                                    {{ number_format($dev->price_from) }}/--->
<!--                                                </h6>-->
<!--                                            @endif-->
<!--                                        </a>-->
<!--                                    </figcaption>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        @endforeach-->

<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

<!--    </div>-->
<!--</section>-->
<!--@endif-->


{{-- ============================================================ --}}
{{-- LOCATION MAP --}}
{{-- ============================================================ --}}
@if($community->latitude && $community->longitude)
<section class="space pt-0">
    <div class="container">
        <div class="property-location">
            <h5>{{ $community->name }} Location</h5>
            <iframe
                width="100%"
                height="450"
                frameborder="0"
                style="border:0; margin-top:15px;"
                src="https://www.google.com/maps?q={{ $community->latitude }},{{ $community->longitude }}&hl=en&z=13&output=embed"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>
@endif


{{-- ============================================================ --}}
{{-- OTHER COMMUNITIES --}}
{{-- ============================================================ --}}
@if($otherCommunities->count() > 0)
<section class="space position-relative pt-0">
    <div class="container">

        <div class="heading-pnel HeadingMiddleBorder">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <h2 class="m-0">Explore Other Communities</h2>
                </div>
                <div class="col-lg-4 col-12 mobile-none">
                    <div class="head-btn">
                        <a href="{{ route('communities.index') }}" class="light-btn ml-auto">View All Communities</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="cards-main">
            <div class="row">

                @foreach($otherCommunities as $other)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="new-development card-box">
                            <a href="{{ route('communities.show', $other->slug) }}">
                                <figure>
                                    @if($other->image)
                                        <img src="{{ URL::to('') }}/public/{{ $other->image }}"
                                             onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/thumbnail-placeholder-gallery.png';"
                                             alt="{{ $other->name }}">
                                    @else
                                        <img src="{{ URL::to('') }}/public/assets/img/thumbnail-placeholder-gallery.png"
                                             alt="{{ $other->name }}">
                                    @endif
                                </figure>
                            </a>
                            <figcaption>
                                <a href="{{ route('communities.show', $other->slug) }}">
                                    <h3>{{ $other->name }}</h3>
                                </a>
                            </figcaption>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    </div>
</section>
@endif


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

@section('scripts')
<script>
jQuery(document).ready(function () {
    jQuery('#dev-slider').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        dots: false,
        responsive: {
            0:    { items: 1 },
            768:  { items: 2 },
            1024: { items: 3 }
        }
    });
});
</script>
@endsection
