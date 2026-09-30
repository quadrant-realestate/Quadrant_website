@extends('website.layout.app')

@section('title', $property->title . ' — ' . setting('site_name', 'Quadrant Properties Dubai'))
@section('meta_description', Str::limit(strip_tags($property->description ?? $property->short_description), 160) ?: 'Discover ' . $property->title . ' in ' . ($property->community_name ?? 'Dubai') . '. ' . setting('site_name') . ' — Luxury Real Estate.')

@section('head')
<style>
    .content-wrapper h1 {
        font-size: 26px;
        font-weight: 500;
        line-height: 27.06px;
        text-transform: initial;
        letter-spacing: 1px;
        text-align: left;
    }
    span.address_section {
        display: flex;
        overflow: hidden;
        flex-wrap: nowrap;
    }
    span.address_section p {
        margin-bottom: 12px;
    }
</style>
@endsection

@section('content')

<div>

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
                        @php
                            $listingRoutes = [
                                'sale'          => 'properties.sale',
                                'rent'          => 'properties.rent',
                                'private'       => 'properties.private',
                                'international' => 'properties.international',
                            ];
                            $listingLabels = [
                                'sale'          => 'Buy',
                                'rent'          => 'Rent',
                                'private'       => 'Private Office',
                                'international' => 'International',
                            ];
                            $listingRoute = $listingRoutes[$property->listing_type] ?? 'properties.sale';
                            $listingLabel = $listingLabels[$property->listing_type] ?? 'Buy';
                        @endphp
                        <li><a href="{{ route($listingRoute) }}">{{ $listingLabel }}</a></li>
                        <li><span>{{ $property->title }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- GALLERY GRID --}}
{{-- ============================================================ --}}
<section class="property-gallery-sec space pb-0">
    <div class="container">
        <div class="row">

            {{-- Gallery Top Bar --}}
            <div class="col-12">
                <div class="gallery-topbar">

                    <div class="topbar-left">
                        @if($property->type_name)
                            <p class="category-label">{{ $property->type_name }}</p>
                        @endif
                    </div>

                    <div class="topbar-right">
                        <div class="share-blog mb-0">
                            <div class="dropdown share dropdown social_share">
                                <a href="javascript:void(0);" class="green-btn"
                                   onclick="event.preventDefault();"
                                   role="button"
                                   id="shareDropdown"
                                   data-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    <img width="14" height="10"
                                         src="{{ URL::to('') }}/public/assets/img/share.svg"
                                         alt="Share">Share
                                </a>
                                <div class="dropdown-menu" aria-labelledby="shareDropdown">
                                    <a class="dropdown-item" href="#" onclick="copyCurrentUrl(event)">
                                        <i class="fa-solid fa-copy"></i> Copy Page URL
                                    </a>
                                    <a class="dropdown-item"
                                       href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                       target="_blank">
                                        <i class="fa-brands fa-facebook"></i> Share on Facebook
                                    </a>
                                    <a class="dropdown-item"
                                       href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($property->title) }}"
                                       target="_blank">
                                        <i class="fa-brands fa-square-twitter"></i> Share on Twitter
                                    </a>
                                    <a class="dropdown-item"
                                       href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}"
                                       target="_blank">
                                        <i class="fa-brands fa-linkedin"></i> Share on LinkedIn
                                    </a>
                                    <a class="dropdown-item"
                                       href="https://wa.me/?text={{ urlencode($property->title . ' ' . request()->url()) }}"
                                       target="_blank">
                                        <i class="fa-brands fa-square-whatsapp"></i> Share on WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Gallery Grid --}}
            <div class="col-12">
                <div class="gallery-grid" id="aniimated-thumbnials">

                    {{-- Main Image (Big) --}}
                    @if($property->main_image)
                        <a href="{{ URL::to('') }}/public/{{ $property->main_image }}" id="gallery-item-1">
                            <div class="Big_Gallery">
                                <img loading="lazy" decoding="async"
                                     alt="{{ $property->title }}"
                                     src="{{ URL::to('') }}/public/{{ $property->main_image }}"
                                     class="img-fluid lazy-imgs"
                                     onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
                            </div>
                        </a>
                    @endif

                    {{-- Gallery Thumbnails --}}
                    @foreach($gallery as $index => $image)
                        <a href="{{ URL::to('') }}/public/{{ $image->image_path }}"
                           class="{{ $index + 2 }} gallery-item-{{ $index + 2 }}"
                           id="gallery-item-{{ $index + 2 }}">
                            <div class="Small_Gallery">
                                <img loading="lazy" decoding="async"
                                     src="{{ URL::to('') }}/public/{{ $image->image_path }}"
                                     class="img-fluid"
                                     alt="{{ $property->title }}-{{ $index + 1 }}"
                                     onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
                            </div>
                        </a>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- MAIN CONTENT + SIDEBAR --}}
{{-- ============================================================ --}}
<section class="space blog-detail-page">
    <div class="container">
        <div class="row">

            {{-- ============================================================ --}}
            {{-- LEFT: Main Content --}}
            {{-- ============================================================ --}}
            <div class="col-lg-8">
                <div class="content-wrapper">

                    {{-- Title + Location --}}
                    <h1 class="mt-0">{{ $property->title }}</h1>
                    @if($property->community_name || $property->address)
                        <p>
                            <img loading="lazy" decoding="async"
                                 src="{{ URL::to('') }}/public/assets/img/hotel/map.svg"
                                 alt="Location">
                            &nbsp; {{ $property->community_name ?? $property->address }}
                        </p>
                    @endif

                    {{-- Price + Key Metrics --}}
                    <div class="price-amenitity">
                        @if($property->price_on_request)
                            <h3>Price on Request</h3>
                        @elseif($property->price)
                            <h3>
                                {{ $property->price_currency ?? 'AED' }}
                                {{ number_format($property->price) }}/-
                            </h3>
                        @endif

                        <div class="main-amenity">

                            @if($property->area_sqft)
                                <div class="amenity-box">
                                    <div class="amenty-img">
                                        <img loading="lazy" decoding="async"
                                             src="{{ URL::to('') }}/public/assets/img/square.svg"
                                             alt="Area">
                                    </div>
                                    <p>{{ number_format($property->area_sqft) }} SQ FT</p>
                                </div>
                            @endif

                            @if($property->bedrooms !== null)
                                <div class="amenity-box">
                                    <div class="amenty-img">
                                        <img loading="lazy" decoding="async"
                                             src="{{ URL::to('') }}/public/assets/img/bed.svg"
                                             alt="Bedrooms">
                                    </div>
                                    <p>{{ $property->bedrooms }} Beds</p>
                                </div>
                            @endif

                            @if($property->bathrooms !== null)
                                <div class="amenity-box">
                                    <div class="amenty-img">
                                        <img loading="lazy" decoding="async"
                                             src="{{ URL::to('') }}/public/assets/img/bathtub.svg"
                                             alt="Bathrooms">
                                    </div>
                                    <p>{{ $property->bathrooms }} Bathrooms</p>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- Mobile Register Interest CTA --}}
                    <a class="green-btn desktop-none mb-4" href="#interest">
                        Register your Interest
                    </a>

                    <div class="seperator"></div>

                    {{-- Description --}}
                    @if($property->description || $property->short_description)
                        <h5>Description</h5>
                        <div class="parent-section">
                            <div class="show_more_content">
                                @if($property->description)
                                    {!! $property->description !!}
                                @else
                                    <p>{{ $property->short_description }}</p>
                                @endif
                            </div>
                            <span id="toggleContentBtn" class="link-btn">Show More</span>
                        </div>
                        <div class="seperator"></div>
                    @endif

                    {{-- Additional Property Details --}}
                    @if($property->plot_area_sqft || $property->parking_spaces || $property->furnished || $property->floor_number || $property->total_floors || $property->service_charge)
                        <div class="pay-plans">
                            <h5>Property Details</h5>
                            <div class="plan-items">
                                @if($property->plot_area_sqft)
                                    <div class="plan-item">
                                        <h4>
                                            <span>Plot Area</span>
                                            {{ number_format($property->plot_area_sqft) }}
                                            <span>SQ FT</span>
                                        </h4>
                                    </div>
                                @endif
                                @if($property->parking_spaces)
                                    <div class="plan-item">
                                        <h4>
                                            <span>Parking</span>
                                            {{ $property->parking_spaces }}
                                            <span>Spaces</span>
                                        </h4>
                                    </div>
                                @endif
                                @if($property->furnished)
                                    <div class="plan-item">
                                        <h4>
                                            <span>Furnishing</span>
                                            {{ ucfirst(str_replace('_', ' ', $property->furnished)) }}
                                        </h4>
                                    </div>
                                @endif
                                @if($property->floor_number)
                                    <div class="plan-item">
                                        <h4>
                                            <span>Floor</span>
                                            {{ $property->floor_number }}
                                            @if($property->total_floors)
                                                <span>of {{ $property->total_floors }}</span>
                                            @endif
                                        </h4>
                                    </div>
                                @endif
                                @if($property->service_charge)
                                    <div class="plan-item">
                                        <h4>
                                            <span>Service Charge</span>
                                            {{ $property->price_currency ?? 'AED' }} {{ number_format($property->service_charge) }}
                                            <span>Per Year</span>
                                        </h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="seperator"></div>
                    @endif

                    {{-- Amenities --}}
                    @if($amenities->count() > 0)
                        <div class="ameneties-panel">
                            <h5>Amenities</h5>
                            <div class="ameneties-items">
                                @foreach($amenities as $amenity)
                                    <div class="ameneties-item">
                                        <img loading="lazy" decoding="async"
                                             src="{{ URL::to('') }}/public/assets/img/check.svg"
                                             alt="">
                                        <p>{{ $amenity->name }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="seperator"></div>
                    @endif

                    {{-- Floor Plan / Brochure --}}
                    @if($property->floor_plan_image || $property->brochure_pdf)
                        <div class="card floor-card-custom">
                            <div class="card-header">
                                <h5>Floor Plan</h5>
                            </div>
                            <div class="card-body">
                                @if($property->floor_plan_image)
                                    <div class="floor-img">
                                        <img loading="lazy" decoding="async"
                                             src="{{ URL::to('') }}/public/{{ $property->floor_plan_image }}"
                                             class="img-fluid"
                                             alt="{{ $property->title }} Floor Plan">
                                    </div>
                                @endif
                                @if($property->brochure_pdf)
                                    <div class="mt-3">
                                        <a href="{{ URL::to('') }}/public/{{ $property->brochure_pdf }}"
                                           target="_blank"
                                           class="light-btn">
                                            <img src="{{ URL::to('') }}/public/assets/img/download-dark.svg" alt="">
                                            Download Brochure
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="seperator"></div>
                    @endif

                    {{-- Video / Virtual Tour --}}
                    @if($property->video_url || $property->virtual_tour_url)
                        <div class="ameneties-panel">
                            <h5>Media</h5>
                            <div class="d-flex gap-3 mt-3 flex-wrap">
                                @if($property->video_url)
                                    <a href="{{ $property->video_url }}" target="_blank" class="light-btn">
                                        Watch Video
                                    </a>
                                @endif
                                @if($property->virtual_tour_url)
                                    <a href="{{ $property->virtual_tour_url }}" target="_blank" class="light-btn">
                                        Virtual Tour
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="seperator"></div>
                    @endif

                    {{-- Location Map --}}
                    @if($property->latitude && $property->longitude)
                        <div class="property-location">
                            <h5>Property Location</h5>
                            {{ $property->community_name ?? $property->city ?? 'Dubai' }}
                            <iframe
                                width="100%"
                                height="450"
                                frameborder="0"
                                style="border:0; margin-top:15px;"
                                src="https://www.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}&hl=en&z=14&output=embed"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @elseif($property->google_maps_link)
                        <div class="property-location">
                            <h5>Property Location</h5>
                            {{ $property->community_name ?? $property->city ?? 'Dubai' }}
                            <div class="mt-3">
                                <a href="{{ $property->google_maps_link }}" target="_blank" class="light-btn">
                                    View on Google Maps
                                </a>
                            </div>
                        </div>
                    @elseif($property->community_name || $property->city)
                        <div class="property-location">
                            <h5>Property Location</h5>
                            {{ $property->community_name ?? $property->city }}
                        </div>
                    @endif

                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- RIGHT: Sidebar --}}
            {{-- ============================================================ --}}
            <div class="col-lg-4">
                <div class="new-p-detail-right" id="interest">

                    {{-- Register Interest Form --}}
                    <div class="list-from">
                        <h3 class="mb-2">Register Your Interest</h3>

                        @if(session('interest_success'))
                            <div class="alert alert-success">{{ session('interest_success') }}</div>
                        @endif

                        <form id="contactForm" action="{{ route('inquiry.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}">
                            <input type="hidden" name="inquiry_type" value="buy">
                            <input type="hidden" name="source" value="property_detail">

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
                            <div class="form-group">
                                <label>Message</label>
                                <textarea class="form-control" name="notes"
                                          placeholder="Enter your message...">Inquiry about: {{ $property->title }} ({{ $property->reference_no }})</textarea>
                            </div>
                            <button type="submit" class="green-btn submit-btn">Submit</button>
                        </form>
                    </div>

                    {{-- Contact Buttons --}}
                    <div class="agent-panel">
                        <div class="agent-contact-btn">
                            @if(setting('whatsapp_number'))
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('whatsapp_number')) }}"
                                   target="_blank" class="light-btn">
                                    <img loading="lazy"
                                         src="{{ URL::to('') }}/public/assets/img/whatsapp-dark.png"
                                         alt="WhatsApp">
                                    Whatsapp
                                </a>
                            @endif
                            @if(setting('contact_phone'))
                                <a href="tel:{{ setting('contact_phone') }}" class="light-btn">
                                    <img loading="lazy"
                                         src="{{ URL::to('') }}/public/assets/img/call-dark.png"
                                         alt="Call">
                                    Call Us Now!
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Download Brochure --}}
                    @if($property->brochure_pdf)
                        <div class="download-btn-grp">
                            <a href="{{ URL::to('') }}/public/{{ $property->brochure_pdf }}"
                               target="_blank"
                               class="green-btn dark w-100">
                                <img loading="lazy"
                                     src="{{ URL::to('') }}/public/assets/img/download-light.svg"
                                     alt="">
                                Download the Brochure
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- RELATED PROPERTIES --}}
{{-- ============================================================ --}}
@if($relatedProperties->count() > 0)
<section class="space position-relative pt-0">
    <div class="container">

        <div class="heading-pnel HeadingMiddleBorder">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <h2 class="m-0">More Properties</h2>
                </div>
                <div class="col-lg-4 col-12"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="cards-main">
                    <div class="owl-carousel" id="related-slider">

                        @foreach($relatedProperties as $related)
                            <div class="item">
                                <div class="card-box">
                                    <figure>
                                        @if($related->type_name)
                                            <div class="VillaText">
                                                <p>{{ $related->type_name }}</p>
                                            </div>
                                        @endif
                                        <a href="{{ route('properties.show', $related->slug) }}">
                                            @if($related->main_image)
                                                <img loading="lazy" decoding="async"
                                                     src="{{ URL::to('') }}/public/{{ $related->main_image }}"
                                                     alt="{{ $related->title }}"
                                                     onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
                                            @else
                                                <img loading="lazy"
                                                     src="{{ URL::to('') }}/public/assets/img/placeholder.jpg"
                                                     alt="{{ $related->title }}">
                                            @endif
                                        </a>
                                    </figure>
                                    <figcaption>
                                        <a href="{{ route('properties.show', $related->slug) }}">
                                            <h3>{{ $related->title }}</h3>
                                            @if($related->community_name)
                                                <span class="address_section">
                                                    <p>
                                                        <img loading="lazy" decoding="async"
                                                             src="{{ URL::to('') }}/public/assets/img/hotel/map.svg"
                                                             alt="Map">
                                                        {{ $related->community_name }}
                                                    </p>
                                                </span>
                                            @endif
                                            <div class="HotelViews">
                                                <ul>
                                                    @if($related->area_sqft)
                                                        <li>
                                                            <img loading="lazy" decoding="async"
                                                                 src="{{ URL::to('') }}/public/assets/img/hotel/1.svg" alt="Area">
                                                            {{ number_format($related->area_sqft) }} SQ FT
                                                        </li>
                                                    @endif
                                                    @if($related->bedrooms !== null)
                                                        <li>
                                                            <img loading="lazy" decoding="async"
                                                                 src="{{ URL::to('') }}/public/assets/img/hotel/2.svg" alt="Beds">
                                                            {{ $related->bedrooms }}
                                                        </li>
                                                    @endif
                                                    @if($related->bathrooms !== null)
                                                        <li>
                                                            <img loading="lazy" decoding="async"
                                                                 src="{{ URL::to('') }}/public/assets/img/hotel/3.svg" alt="Baths">
                                                            {{ $related->bathrooms }}
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @if($related->price_on_request)
                                                <h6><span>Price on Request</span></h6>
                                            @elseif($related->price)
                                                <h6>
                                                    <span>{{ $related->price_currency ?? 'AED' }}</span>
                                                    {{ number_format($related->price) }}/-
                                                </h6>
                                            @endif
                                        </a>
                                    </figcaption>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endif

</div>


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

@section('scripts')
<script>
jQuery(document).ready(function () {

    {{-- LightGallery for gallery grid --}}
    if (typeof lightGallery !== 'undefined') {
        lightGallery(document.getElementById('aniimated-thumbnials'), {
            selector: 'a'
        });
    }

    {{-- Related Properties Carousel --}}
    jQuery('#related-slider').owlCarousel({
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

    {{-- Show More / Show Less for description --}}
    var showMoreBtn = document.getElementById('toggleContentBtn');
    if (showMoreBtn) {
        var content = document.querySelector('.show_more_content');
        content.style.maxHeight = '300px';
        content.style.overflow = 'hidden';

        showMoreBtn.addEventListener('click', function () {
            if (content.style.maxHeight === '300px') {
                content.style.maxHeight = 'none';
                showMoreBtn.textContent = 'Show Less';
            } else {
                content.style.maxHeight = '300px';
                showMoreBtn.textContent = 'Show More';
            }
        });
    }

    {{-- "See more" overlay on 5th gallery image --}}
    var anchorTags = document.querySelectorAll('#aniimated-thumbnials a');
    if (anchorTags.length > 5) {
        var style = document.createElement('style');
        style.innerHTML = `
            #gallery-item-5:after {
                content: "See more";
                display: flex;
                width: 100%;
                height: 100%;
                background-color: rgba(58, 53, 38, 0.84);
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                font-size: 14px;
                letter-spacing: 1px;
                color: #FCF9F2;
                align-items: center;
                justify-content: center;
            }
        `;
        document.head.appendChild(style);
    }

});

function copyCurrentUrl(event) {
    event.preventDefault();
    const currentUrl = window.location.href;
    navigator.clipboard.writeText(currentUrl)
        .then(() => alert('URL copied to clipboard!'))
        .catch(err => alert('Failed to copy URL: ' + err));
}
</script>
@endsection
