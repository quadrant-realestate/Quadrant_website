@extends('website.layout.app')

@section('title', $residence->title . ' — ' . setting('site_name', 'Quadrant Properties Dubai'))
@section('meta_description', Str::limit(strip_tags($residence->description), 160) ?: 'Discover ' . $residence->title . ' by ' . ($residence->brand_name ?? '') . ' in ' . ($residence->community_name ?? 'Dubai') . '. ' . setting('site_name') . ' — Luxury Branded Residences.')

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
                        <li><a href="{{ route('branded-residences.index') }}">Branded Residences</a></li>
                        <li><span>{{ $residence->title }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- GALLERY / MAIN IMAGE --}}
{{-- ============================================================ --}}
<section class="property-gallery-sec space pb-0">
    <div class="container">
        <div class="row">

            {{-- Gallery Top Bar --}}
            <div class="col-12">
                <div class="gallery-topbar">

                    <div class="topbar-left">
                        <p class="category-label">{{ $residence->brand_name ?? 'Branded Residence' }}</p>
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
                                       href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($residence->title) }}"
                                       target="_blank">
                                        <i class="fa-brands fa-square-twitter"></i> Share on Twitter
                                    </a>
                                    <a class="dropdown-item"
                                       href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}"
                                       target="_blank">
                                        <i class="fa-brands fa-linkedin"></i> Share on LinkedIn
                                    </a>
                                    <a class="dropdown-item"
                                       href="https://wa.me/?text={{ urlencode($residence->title . ' ' . request()->url()) }}"
                                       target="_blank">
                                        <i class="fa-brands fa-square-whatsapp"></i> Share on WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Main Image --}}
            <div class="col-12">
                <div>
                    @if($residence->main_image)
                        <img loading="lazy" decoding="async" class="w-100"
                             style="border-radius:5px;"
                             src="{{ URL::to('') }}/public/{{ $residence->main_image }}"
                             alt="{{ $residence->title }}"
                             onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
                    @else
                        <img loading="lazy" class="w-100" style="border-radius:5px;"
                             src="{{ URL::to('') }}/public/assets/img/placeholder.jpg"
                             alt="{{ $residence->title }}">
                    @endif
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
                    <h1 class="mt-0">{{ $residence->title }}</h1>
                    @if($residence->community_name)
                        <p>
                            <img loading="lazy" decoding="async"
                                 src="{{ URL::to('') }}/public/assets/img/hotel/map.svg"
                                 alt="Location">
                            &nbsp; {{ $residence->community_name }}
                        </p>
                    @endif

                    {{-- Brand Logo --}}
                    @if($residence->brand_logo)
                        <div class="mb-3">
                            <img src="{{ URL::to('') }}/public/{{ $residence->brand_logo }}"
                                 alt="{{ $residence->brand_name }}"
                                 height="50"
                                 style="object-fit:contain; max-width:180px;">
                        </div>
                    @endif

                    {{-- Price + Key Metrics --}}
                    <div class="price-amenitity">
                        @if($residence->price_from)
                            <h3>
                                Starting Price: AED
                                {{ number_format($residence->price_from) }}/-
                            </h3>
                        @endif

                        <div class="main-amenity">

                            @if($residence->property_area_sqft)
                                <div class="amenity-box">
                                    <div class="amenty-img">
                                        <img loading="lazy" decoding="async"
                                             src="{{ URL::to('') }}/public/assets/img/square.svg"
                                             alt="Area">
                                    </div>
                                    <p>{{ number_format($residence->property_area_sqft) }} SQ FT</p>
                                </div>
                            @endif

                            @if($residence->property_bedrooms !== null)
                                <div class="amenity-box">
                                    <div class="amenty-img">
                                        <img loading="lazy" decoding="async"
                                             src="{{ URL::to('') }}/public/assets/img/hotel/2.svg"
                                             alt="Bedrooms">
                                    </div>
                                    <p>{{ $residence->property_bedrooms }} Bedrooms</p>
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
                    @if($residence->description)
                        <h5>Description</h5>
                        <div class="parent-section">
                            <div class="show_more_content">
                                {!! $residence->description !!}
                            </div>
                            <span id="toggleContentBtn" class="link-btn">Show More</span>
                        </div>
                        <div class="seperator"></div>
                    @endif

                    {{-- Linked Property --}}
                    @if($residence->property_title)
                        <div class="ameneties-panel">
                            <h5>Linked Property</h5>
                            <div class="mt-3">
                                <a href="{{ route('properties.show', $residence->property_slug) }}" class="light-btn">
                                    View {{ $residence->property_title }}
                                </a>
                            </div>
                        </div>
                        <div class="seperator"></div>
                    @endif

                    {{-- Location Map --}}
                    @if($residence->community_latitude && $residence->community_longitude)
                        <div class="property-location">
                            <h5>Property Location</h5>
                            {{ $residence->community_name ?? '' }}
                            <iframe
                                width="100%"
                                height="450"
                                frameborder="0"
                                style="border:0; margin-top:15px;"
                                src="https://www.google.com/maps?q={{ $residence->community_latitude }},{{ $residence->community_longitude }}&hl=en&z=14&output=embed"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @elseif($residence->community_name)
                        <div class="property-location">
                            <h5>Property Location</h5>
                            {{ $residence->community_name }}
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
                            <input type="hidden" name="inquiry_type" value="general">
                            <input type="hidden" name="source" value="branded_residence_detail">

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
                                          placeholder="Enter your message...">Inquiry about: {{ $residence->title }}</textarea>
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

                </div>
            </div>

        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- RELATED BRANDED RESIDENCES --}}
{{-- ============================================================ --}}
@if($relatedResidences->count() > 0)
<section class="space position-relative pt-0">
    <div class="container">

        <div class="heading-pnel HeadingMiddleBorder">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <h2 class="m-0">More Branded Residences</h2>
                </div>
                <div class="col-lg-4 col-12"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="cards-main">
                    <div class="owl-carousel" id="related-slider">

                        @foreach($relatedResidences as $related)
                            <div class="item">
                                <div class="card-box">
                                    <figure>
                                        <div class="VillaText">
                                            <p>Branded Residence</p>
                                        </div>
                                        <a href="{{ route('developments.show', $related->slug) }}">
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
                                        <a href="{{ route('developments.show', $related->slug) }}">
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

    {{-- Related Residences Carousel --}}
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