@extends('website.layout.app')

@section('title', setting('site_name', 'Quadrant Properties Dubai') . ' — Luxury Real Estate in Dubai')
@section('meta_description', 'Quadrant Properties Dubai offers the best luxury real estate options in Dubai. Explore luxury properties, villas, penthouses and mansions in Dubai\'s most prestigious communities.')

@section('head')
<style>
    @media (max-width:767px) {
        .banner { height: 320px !important; }
    }
    section.banner.home_page_banner_parent { position: relative; }
    img.home_page_banner { position: absolute; width: 100%; height: calc(100% + 50px); object-fit: cover; }
    .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
</style>
@endsection

@section('content')

{{-- ============================================================ --}}
{{-- SECTION 1: HERO BANNER --}}
{{-- ============================================================ --}}
<section class="banner" data-class="home_page_banner_parent">

    {{-- Hero Video --}}
    @if($heroVideo)
        <video autoplay muted loop playsinline class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: cover;">
            <source src="{{ URL::to('') }}/public/{{ $heroVideo }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        @else
        <video autoplay muted loop playsinline class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: cover;">
           
            <source src="{{ URL::to('') }}/public/assets/images/image_17543070081.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    @endif

    <div class="container position-relative">
        <div class="slider-info banner-bg">
            <div class="BannerBox">

                {{-- Hero Heading --}}
                <div class="banner-heading">
                    <h1>{!! $heroTitle !!}</h1>
                </div>

                {{-- Search Form — Desktop --}}
                <div class="banner-form mobile-none">
                    <div class="TopTabsBar">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">

                            <li class="nav-item" role="presentation">
                                <a class="nav-link active show"
                                   id="home-tab"
                                   data-toggle="tab"
                                   href="#Buy"
                                   role="tab"
                                   aria-controls="Buy"
                                   aria-selected="true">
                                    Buy
                                </a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link"
                                   id="profile-tab"
                                   data-toggle="tab"
                                   href="#Rent"
                                   role="tab"
                                   aria-controls="Rent"
                                   aria-selected="false">
                                    Rent
                                </a>
                            </li>

                        </ul>
                    </div>

                    <div class="tab-content">

                        {{-- BUY TAB --}}
                        <div id="Buy" role="tabpanel" class="tab-pane fade active show">
                            <form action="{{ route('properties.search') }}" method="GET">
                                <input type="hidden" name="listing_type" value="sale">
                                <div class="BookingBox">
                                    <div class="BookingLocation">

                                        <div class="BookingFrom">
                                            <input type="text" name="search"
                                                   class="form-control"
                                                   placeholder="Search community, area or property...">
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
                                            <select name="bedrooms" class="form-control">
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
                                                <img src="{{ URL::to('') }}/public/assets/img/search.svg"
                                                     width="17" height="17" loading="lazy" alt="Search">
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- RENT TAB --}}
                        <div id="Rent" role="tabpanel" class="tab-pane fade">
                            <form action="{{ route('properties.search') }}" method="GET">
                                <input type="hidden" name="listing_type" value="rent">
                                <div class="BookingBox">
                                    <div class="BookingLocation">

                                        <div class="BookingFrom">
                                            <input type="text" name="search"
                                                   class="form-control"
                                                   placeholder="Search community, area or property...">
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
                                            <select name="bedrooms" class="form-control">
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
                                                <img src="{{ URL::to('') }}/public/assets/img/search.svg"
                                                     width="17" height="17" loading="lazy" alt="Search">
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                {{-- End Search Form --}}

            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SECTION 2: ABOUT PANEL --}}
{{-- ============================================================ --}}
<section class="space panel-sec mobile-none">
    <div class="container">
        <div class="panel-box">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="heading-pnel m-0">
                        <h2>Access to the world's finest homes</h2>
                        <div class="headingBorder"></div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-6 col-12">
                    <div class="panel-sec-content">
                        @if($aboutText)
                            <p>{!! $aboutText !!}</p>
                        @else
                            <p>At Quadrant, we connect discerning clients with exceptional properties across Dubai and the globe. Our advisors combine deep market insight with genuine local expertise, ensuring every transaction is handled with precision, discretion, and care.</p>
                        @endif
                        <a href="{{ route('properties.sale') }}" class="link-btn">
                            Discover Properties
                            <span class="sr-only"> about Quadrant Properties</span>
                            <img width="13" height="auto" alt="arrow"
                                 src="{{ URL::to('') }}/public/assets/img/arrow.svg">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SECTION 3: FEATURED PROPERTIES --}}
{{-- ============================================================ --}}
<section class="space position-relative">
    <div class="container">
        <div class="heading-pnel HeadingMiddleBorder">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <h2 class="m-0">Featured Properties</h2>
                </div>
                <div class="col-lg-4 col-12"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="cards-main">
                    <div class="owl-carousel" id="instructor-slider">

                        @forelse($featuredProperties as $property)
                            <div class="item">
                                <div class="card-box">
                                    <figure>
                                        @if($property->type_name)
                                            <div class="VillaText"><p>{{ $property->type_name }}</p></div>
                                        @endif
                                        <a href="{{ route('properties.show', $property->slug) }}">
                                            @if($property->main_image)
                                                <img loading="lazy" width="300" height="auto"
                                                     src="{{ URL::to('') }}/public/{{ $property->main_image }}"
                                                     alt="{{ $property->title }}">
                                            @else
                                                <img loading="lazy" width="300" height="auto"
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
                                                    <img width="13" height="auto" loading="lazy"
                                                         src="{{ URL::to('') }}/public/assets/img/map.svg"
                                                         alt="Location">
                                                    {{ $property->community_name }}
                                                </p>
                                            @endif
                                            <div class="HotelViews">
                                                <ul>
                                                    @if($property->area_sqft)
                                                        <li>
                                                            <img loading="lazy" width="17" height="19" alt="Area"
                                                                 src="{{ URL::to('') }}/public/assets/img/1.svg">
                                                            {{ number_format($property->area_sqft) }} SQ FT
                                                        </li>
                                                    @endif
                                                    @if($property->bedrooms !== null)
                                                        <li>
                                                            <img loading="lazy" width="17" height="15" alt="Beds"
                                                                 src="{{ URL::to('') }}/public/assets/img/2.svg">
                                                            {{ $property->bedrooms }}
                                                        </li>
                                                    @endif
                                                    @if($property->bathrooms !== null)
                                                        <li>
                                                            <img loading="lazy" width="17" height="13" alt="Baths"
                                                                 src="{{ URL::to('') }}/public/assets/img/3.svg">
                                                            {{ $property->bathrooms }}
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="price">
                                                @if($property->price_on_request)
                                                    <span>Price on Request</span>
                                                @else
                                                    <span>{{ $property->price_currency }}</span>
                                                    {{ number_format($property->price) }}/-
                                                @endif
                                            </div>
                                        </a>
                                    </figcaption>
                                </div>
                            </div>
                        @empty
                            <div class="item">
                                <p class="text-center text-muted py-4">No featured properties at the moment.</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

        <div class="view-all">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('properties.sale') }}" class="green-btn">Explore All Properties</a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SECTION 4: RECOGNITIONS / MEDIA --}}
{{-- ============================================================ --}}
@if($recognitions->count() > 0)
<section class="Brands-sec">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-3 col-md-4 col-12">
                <div class="heading-pnel m-0">
                    <h2 class="m-0"><br>Recognitions</h2>
                    <div class="headingBorder"></div>
                </div>
            </div>

            <div class="col-lg-9 col-md-8 col-12">
                <div class="owl-carousel" id="Brands" aria-label="Media Mentions Carousel">
                    @foreach($recognitions as $recognition)
                        @if($recognition->url)
                            <a href="{{ $recognition->url }}" target="_blank">
                        @endif
                            <div class="brand-box new">
                                @if($recognition->logo)
                                    <img src="{{ URL::to('') }}/public/{{ $recognition->logo }}"
                                         alt="{{ $recognition->media_name }}"
                                         loading="lazy"
                                         decoding="async"
                                         class="img-fluid"
                                         width="200"
                                         height="auto">
                                @else
                                    <span>{{ $recognition->media_name }}</span>
                                @endif
                            </div>
                        @if($recognition->url)
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
@endif


{{-- ============================================================ --}}
{{-- SECTION 5: PRIVATE LISTINGS --}}
{{-- ============================================================ --}}
@if($privateListings->count() > 0)
<section class="space private-office-sec bg-black">
    <div class="container">
        <div class="heading-pnel HeadingMiddleBorder fff">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <h2 class="m-0">Private Listings</h2>
                </div>
                <div class="col-lg-4 col-12 mobile-none">
                    <div class="head-btn">
                        <a href="{{ route('properties.private') }}" class="light-btn ml-auto">
                            Explore Quadrant's Private Office
                        </a>
                    </div>
                </div>
                <div class="col-12">
                    <p>Discover a gateway to the most coveted residences and investment opportunities, offered discreetly through Quadrant's Private Office. For discerning clients who demand more than just a property, these listings embody an extraordinary lifestyle crafted to match your ambitions.</p>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($privateListings as $property)
                <div class="col">
                    <div class="office-box">
                        <a href="{{ route('properties.show', $property->slug) }}">
                            <figure>
                                @if($property->main_image)
                                    <img width="300" height="auto"
                                         alt="{{ $property->title }}"
                                         loading="lazy"
                                         src="{{ URL::to('') }}/public/{{ $property->main_image }}"
                                         onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
                                @else
                                    <img width="300" height="auto"
                                         alt="{{ $property->title }}"
                                         loading="lazy"
                                         src="{{ URL::to('') }}/public/assets/img/placeholder.jpg">
                                @endif
                            </figure>
                            <figcaption>
                                <div class="add-grp">
                                    @if($property->type_name)
                                        <div class="VillaText">{{ $property->type_name }}</div>
                                    @endif
                                    @if($property->community_name)
                                        <p class="private_address">
                                            <img loading="lazy" width="13" height="13" alt="Map"
                                                 src="{{ URL::to('') }}/public/assets/img/map.svg">
                                            {{ $property->community_name }}
                                        </p>
                                    @endif
                                </div>
                                <h3>{{ $property->title }}</h3>
                                <div class="HotelViews">
                                    <ul>
                                        @if($property->area_sqft)
                                            <li>
                                                <img loading="lazy" width="17" height="19" alt="Area"
                                                     src="{{ URL::to('') }}/public/assets/img/hotel/1.svg">
                                                {{ number_format($property->area_sqft) }} SQ FT
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="price">
                                    @if($property->price_on_request)
                                        <span>Price on Request</span>
                                    @else
                                        <span>{{ $property->price_currency }}</span>
                                        {{ number_format($property->price) }}/-
                                    @endif
                                </div>
                            </figcaption>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="col-lg-4 col-12 desktop-none">
            <div class="head-btn mt-4">
                <a href="{{ route('properties.private') }}" class="light-btn mx-auto">
                    Explore Quadrant's Private Office
                </a>
            </div>
        </div>

    </div>
</section>
@endif


{{-- ============================================================ --}}
{{-- SECTION 6: INTERNATIONAL PROPERTIES --}}
{{-- ============================================================ --}}
<section class="space International-sec">
    <div class="container">
        <div class="heading-pnel HeadingMiddleBorder">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <h2 class="m-0">International Properties</h2>
                </div>
            </div>
        </div>

        <div class="international-main" id="international-main"
             style="background-image: url('{{ URL::to('') }}/public/assets/images/image_17390013322.jpg');">
            <div class="row">
                <div class="col-12">
                    <div class="tabs-grp">
                        <ul class="nav nav-tabs" id="myTab0" role="tablist">

                            <li class="nav-item" role="presentation">
                                <a class="nav-link active"
                                   id="Africa-tab2"
                                   data-toggle="tab"
                                   data-image="{{ URL::to('') }}/public/assets/images/image_17390013600.jpg"
                                   data-target="#Africa2"
                                   type="button"
                                   role="tab"
                                   aria-controls="Africa2"
                                   aria-selected="true">
                                    Europe
                                </a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link"
                                   id="Africa-tab3"
                                   data-toggle="tab"
                                   data-image="{{ URL::to('') }}/public/assets/images/image_17362322940.jpg"
                                   data-target="#Africa3"
                                   type="button"
                                   role="tab"
                                   aria-controls="Africa3"
                                   aria-selected="false">
                                    Middle East
                                </a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link"
                                   id="Africa-tab4"
                                   data-toggle="tab"
                                   data-image="{{ URL::to('') }}/public/assets/images/image_17362324590.jpg"
                                   data-target="#Africa4"
                                   type="button"
                                   role="tab"
                                   aria-controls="Africa4"
                                   aria-selected="false">
                                    North America
                                </a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link"
                                   id="Africa-tab12"
                                   data-toggle="tab"
                                   data-image="{{ URL::to('') }}/public/assets/images/image_17389953520.jpg"
                                   data-target="#Africa12"
                                   type="button"
                                   role="tab"
                                   aria-controls="Africa12"
                                   aria-selected="false">
                                    Asia
                                </a>
                            </li>

                        </ul>

                        <div class="tab-content" id="myTabContent">

                            {{-- Europe --}}
                            <div class="tab-pane fade show active" id="Africa2" role="tabpanel" aria-labelledby="Africa-tab2">
                                <div class="tabs-caption fff">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-8 col-12">
                                            <div class="heading-pnel m-0 fff">
                                                <h2 class="m-0">Europe</h2>
                                                <p>From historic châteaux and picturesque countryside estates to cosmopolitan penthouses in iconic cities like London, Paris, and Milan, the continent offers a wealth of opportunities for discerning buyers and investors.</p>
                                                <div class="btn-grp">
                                                    <a href="{{ route('properties.international') }}?region=europe" class="border-btn fff">
                                                        View Listings All Around the World
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Middle East --}}
                            <div class="tab-pane fade" id="Africa3" role="tabpanel" aria-labelledby="Africa-tab3">
                                <div class="tabs-caption fff">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-8 col-12">
                                            <div class="heading-pnel m-0 fff">
                                                <h2 class="m-0">Middle East</h2>
                                                <p>The Middle East stands as a beacon of luxury, innovation, and timeless allure, offering some of the world's most iconic real estate opportunities. From the dazzling skyscrapers of Dubai and Abu Dhabi to serene coastal retreats and culturally rich heritage sites.</p>
                                                <div class="btn-grp">
                                                    <a href="{{ route('properties.international') }}?region=middle-east" class="border-btn fff">
                                                        View Listings All Around the World
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- North America --}}
                            <div class="tab-pane fade" id="Africa4" role="tabpanel" aria-labelledby="Africa-tab4">
                                <div class="tabs-caption fff">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-8 col-12">
                                            <div class="heading-pnel m-0 fff">
                                                <h2 class="m-0">North America</h2>
                                                <p>From sleek, modern condos in bustling cities like New York, Los Angeles, and Toronto to sprawling estates nestled in serene countryside or coastal enclaves, the region is defined by its boundless opportunities.</p>
                                                <div class="btn-grp">
                                                    <a href="{{ route('properties.international') }}?region=north-america" class="border-btn fff">
                                                        View Listings All Around the World
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Asia --}}
                            <div class="tab-pane fade" id="Africa12" role="tabpanel" aria-labelledby="Africa-tab12">
                                <div class="tabs-caption fff">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-8 col-12">
                                            <div class="heading-pnel m-0 fff">
                                                <h2 class="m-0">Asia</h2>
                                                <p>Asia's real estate landscape is as diverse and dynamic as the continent itself. From the soaring skylines of global financial capitals to tranquil seaside retreats and culturally rich historic enclaves, each property reflects the region's burgeoning economic clout.</p>
                                                <div class="btn-grp">
                                                    <a href="{{ route('properties.international') }}?region=asia" class="border-btn fff">
                                                        View Listings All Around the World
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SECTION 7: NEW DEVELOPMENTS --}}
{{-- ============================================================ --}}
@if($developments->count() > 0)
<section class="new-development-sec space pt-0">
    <div class="container">
        <div class="heading-pnel HeadingMiddleBorder">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <h2 class="m-0">New Developments</h2>
                </div>
                <div class="col-lg-6 col-12"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="cards-main">
                    <div class="owl-carousel" id="NewDevelopment">

                        @foreach($developments as $dev)
                            <div class="item">
                                <div class="new-development card-box">
                                    <figure>
                                        <a href="{{ route('developments.show', $dev->slug) }}">
                                            @if($dev->main_image)
                                                <img width="300" height="auto" loading="lazy"
                                                     src="{{ URL::to('') }}/public/{{ $dev->main_image }}"
                                                     alt="{{ $dev->title }}">
                                            @else
                                                <img width="300" height="auto" loading="lazy"
                                                     src="{{ URL::to('') }}/public/assets/img/placeholder.jpg"
                                                     alt="{{ $dev->title }}">
                                            @endif
                                        </a>
                                    </figure>
                                    <figcaption>
                                        <a href="{{ route('developments.show', $dev->slug) }}">
                                            <h3>{{ $dev->title }}</h3>
                                            @if($dev->community_name)
                                                <p>
                                                    <img width="17" height="17" loading="lazy" alt="Map"
                                                         src="{{ URL::to('') }}/public/assets/img/map.svg">
                                                    {{ $dev->community_name }}
                                                </p>
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

        <div class="view-all">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('developments.index') }}" class="green-btn">Browse More Projects</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


{{-- ============================================================ --}}
{{-- SECTION 8: BRANDED RESIDENCES BANNER --}}
{{-- ============================================================ --}}
<section class="space full-width-sec"
         style="background-image: url('{{ URL::to('') }}/public/assets/images/image_17389950594.jpg');">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="heading-pnel fff">
                    <h2 class="m-0">Branded Residences</h2>
                    <p><strong>Where Global Brands Meet Real Estate Legacy:</strong>
                        Quadrant Properties is at the forefront of the branded residences movement,
                        connecting world-renowned brands with visionary developers and discerning investors.
                    </p>
                    <div class="btn-grp">
                        <a href="{{ route('branded-residences.index') }}" class="border-btn fff">
                            Explore Branded Residences in Dubai
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SECTION 9: FAQ --}}
{{-- ============================================================ --}}
<div class="mt-4 home_page_old bg-grey" style="padding-bottom: 60px;">

    <section class="faq-sec space bg-grey pr">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 mx-auto">
                    <div class="heading-pnel text-center">
                        <h2>Frequently Asked Questions</h2>
                    </div>
                </div>

                <div class="col-lg-7 mx-auto">
                    <div class="accordion" id="faqAccordion">

                        <div class="card">
                            <div class="card-header" id="faqHead1">
                                <h3 class="btn btn-link collapsed"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#faqCollapse1"
                                    aria-expanded="true"
                                    aria-controls="faqCollapse1">
                                    What sets {{ setting('site_name', 'Quadrant Properties Dubai') }} apart from other luxury real estate firms?
                                </h3>
                            </div>
                            <div id="faqCollapse1" class="collapse" aria-labelledby="faqHead1" data-parent="#faqAccordion">
                                <div class="card-body">
                                    <p>At Quadrant Properties Dubai, we pride ourselves on offering a personalised, bespoke service to each and every one of our clients. Our team of experienced professionals has a deep understanding of the Dubai real estate market and we are committed to helping you find the perfect luxury property that meets your needs and exceeds your expectations.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="faqHead2">
                                <h3 class="btn btn-link collapsed"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#faqCollapse2"
                                    aria-expanded="false"
                                    aria-controls="faqCollapse2">
                                    How do I schedule a viewing of a luxury property for sale?
                                </h3>
                            </div>
                            <div id="faqCollapse2" class="collapse" aria-labelledby="faqHead2" data-parent="#faqAccordion">
                                <div class="card-body">
                                    <p>If you are interested in viewing one of our luxury properties for sale, simply contact us to schedule a viewing. Our experienced team will be happy to arrange a time that is convenient for you and provide you with all the information you need about the property.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="faqHead3">
                                <h3 class="btn btn-link collapsed"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#faqCollapse3"
                                    aria-expanded="false"
                                    aria-controls="faqCollapse3">
                                    How can I search for a luxury property for sale on your website?
                                </h3>
                            </div>
                            <div id="faqCollapse3" class="collapse" aria-labelledby="faqHead3" data-parent="#faqAccordion">
                                <div class="card-body">
                                    <p>Our website features a comprehensive search function that allows you to filter properties by location, price and type. You can also browse our listings by category, including villas, apartments, penthouses and townhouses, to find the perfect luxury property for sale that meets your requirements.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="faqHead4">
                                <h3 class="btn btn-link collapsed"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#faqCollapse4"
                                    aria-expanded="false"
                                    aria-controls="faqCollapse4">
                                    What kind of luxury homes for sale do you offer?
                                </h3>
                            </div>
                            <div id="faqCollapse4" class="collapse" aria-labelledby="faqHead4" data-parent="#faqAccordion">
                                <div class="card-body">
                                    <p>We offer a wide range of luxury homes for sale, including villas, apartments, penthouses and townhouses. Our properties are located in some of the most sought-after neighbourhoods in Dubai and we pride ourselves on offering the finest selection of luxury homes for sale in the city.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SEO Content Section --}}
    <section class="faq-sec bg-grey pr">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 mx-auto">
                    <div class="heading-pnel text-center">
                        <h2>Premium Homes for Sale in Dubai by {{ setting('site_name', 'Quadrant Properties Dubai') }}</h2>
                    </div>
                </div>

                <div class="col-lg-7 mx-auto">
                    <div class="accordion" id="seoAccordion">
                        <div class="card">
                            <div class="card-header" id="seoHead1">
                                <h3 style="text-transform: capitalize;"
                                    class="btn btn-link collapsed"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#seoCollapse1"
                                    aria-expanded="true"
                                    aria-controls="seoCollapse1">
                                    Luxury Real Estate Agency in Dubai
                                </h3>
                            </div>
                            <div id="seoCollapse1" class="collapse" aria-labelledby="seoHead1" data-parent="#seoAccordion">
                                <div class="card-body">
                                    <p>Dubai is recognized for its opulence, luxurious way of life, and cutting-edge infrastructure, which makes it ideal for the world's elites. {{ setting('site_name', 'Quadrant Properties Dubai') }} is one of the top luxury real estate agencies in the area, offering the most exquisite and opulent properties. With the assistance of our committed luxury property specialists in Dubai, we offer complete, end-to-end services throughout the city's real estate market.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>


{{-- ============================================================ --}}
{{-- SEARCH MODAL (Mobile) --}}
{{-- ============================================================ --}}
<div class="modal fade" id="search-modal" tabindex="-1" role="dialog"
     aria-labelledby="searchModalTitle" aria-hidden="true">
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
                                <a class="nav-link active" id="modal-buy-tab"
                                   data-toggle="tab" href="#Buy-two"
                                   role="tab" aria-selected="true">Buy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="modal-rent-tab"
                                   data-toggle="tab" href="#Rent-two"
                                   role="tab" aria-selected="false">Rent</a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="modalTabContent">

                        {{-- Modal Buy --}}
                        <div id="Buy-two" class="tab-pane fade show active" role="tabpanel">
                            <form action="{{ route('properties.search') }}" method="GET">
                                <input type="hidden" name="listing_type" value="sale">
                                <div class="BookingBox">
                                    <div class="BookingLocation">
                                        <div class="BookingFrom">
                                            <input type="text" name="search"
                                                   class="form-control"
                                                   placeholder="Search country and city...">
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
                                                     src="{{ URL::to('') }}/public/assets/img/search.svg"
                                                     alt="Search">
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
                                            <input type="text" name="search"
                                                   class="form-control"
                                                   placeholder="Search country and city...">
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
                                                     src="{{ URL::to('') }}/public/assets/img/search.svg"
                                                     alt="Search">
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


@section('scripts')
<script>
jQuery(document).ready(function () {

    // Featured Properties Carousel
    jQuery('#instructor-slider').owlCarousel({
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

    // Recognitions / Brands Carousel
    jQuery('#Brands').owlCarousel({
        loop: true,
        margin: 20,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsive: {
            0:    { items: 2 },
            768:  { items: 4 },
            1024: { items: 5 }
        }
    });

    // New Developments Carousel
    jQuery('#NewDevelopment').owlCarousel({
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

    // International section — change background image on tab click
    jQuery('#myTab0 a').on('click', function () {
        var newImage = jQuery(this).data('image');
        jQuery('#international-main').css('background-image', 'url(' + newImage + ')');
    });

});
</script>
@endsection