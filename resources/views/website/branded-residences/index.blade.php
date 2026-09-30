@extends('website.layout.app')

@section('title', 'Top Branded Residences in Dubai — ' . setting('site_name', 'Quadrant Properties Dubai'))
@section('meta_description', 'Discover the top branded residences in Dubai with ' . setting('site_name', 'Quadrant Properties Dubai') . '. Luxury homes built in collaboration with world-renowned global brands.')

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
         style="background-image: url('{{ URL::to('') }}/public/assets/images/image_175377051490.png');">
    <div class="container">
        <div class="slider-info">
            <div class="BannerBox">
                <div class="banner-heading text-center">
                    <h1>Top Branded Residences in Dubai</h1>
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
                        <li><span>Branded Residences in Dubai</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- AT A GLANCE / CTA STRIP --}}
{{-- ============================================================ --}}
<section class="CTA-strip space">
    <div class="container">
        <div class="row" style="background-image: url('{{ URL::to('') }}/public/assets/images/image_173434756190.jpg');">
            <div class="col-lg-6">
                <div class="heading-pnel fff m-0">
                    <h2 class="m-0">At a Glance</h2>
                    <p>In 2010, the launch of branded residences in the iconic Burj Khalifa marked a turning point in Dubai's luxury property scene. As the first of its kind, this development introduced the concept of branded residences in Dubai to a global audience. Since then, demand for these prestigious properties has surged, attracting discerning investors, affluent homeowners, and some of the world's most renowned hospitality and lifestyle brands.</p>
                    <a href="{{ route('contact') }}" class="light-btn">Read more</a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- WHAT THEY ARE & EXAMPLES --}}
{{-- ============================================================ --}}
<section class="about-sec after-none space bg-grey">
    <div class="container">
        <div class="heading-pnel text-center">
            <h2>Branded Residences in Dubai: What They Are and Examples</h2>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-left">
                    <div class="about-img about-img-after">
                        <div class="img-item">
                            <img src="{{ URL::to('') }}/public/assets/images/image_173434713991.jpg"
                                 alt="Best branded residences for sale in Dubai" class="w-100">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <p>The market for branded homes in Dubai has expanded significantly over the past ten years. In addition to increasing the value of the city's real estate, this expansion has created a new source of income for developers and global brands. By building a prosperous, branded living ecosystem that benefits all parties — developers, investors, and residents — {{ setting('site_name', 'Quadrant Properties Dubai') }} aims to influence the future of real estate as leaders in this industry.</p>
                    <p>What makes these properties interesting? Branded residences in Dubai are high-end luxury homes built in collaboration with well-known global brands. They offer all of these things under one roof: high-end design, premium services, and a better way of life. Buyers can enjoy the comfort and privacy of a private home while also getting personalized services and high-end amenities that are typically only found in five-star hotels, such as concierge service, private pools, spas, and fitness centers.</p>
                    <p>These changes are the result of strategic partnerships. A brand gives a developer a license to use its name and standards, and then the developer builds, designs, and sells homes under that brand. The developer pays royalties and design fees, while the buyers pay for regular services and management. In return, they get a way of life and an investment that is backed by brand integrity, consistency, and global appeal.</p>
                    <p>This partnership means more than just prestige for its residents. A trusted brand's involvement makes sure that everything, from the quality of the construction to the delivery of the service, is up to standard. This gives buyers more confidence and helps keep property values high, even when the market is fluctuating.</p>
                </div>
            </div>
        </div>
    </div>
    <br><br>
</section>


{{-- ============================================================ --}}
{{-- TRIPLE WIN FORMULA --}}
{{-- ============================================================ --}}
<section class="formula-sec space">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="formula-content">
                    <p>Branded Residences offer a special kind of synergy. What our experts refer to as a Triple Win Formula:</p>
                    <ul>
                        <li>For developers, branded projects provide a competitive advantage and access to a larger, more devoted customer base. Developers can command premium pricing thanks to customized design and marketing strategies, which also increase visibility and demand.</li>
                        <li>For brands, through licensing agreements, these properties open up a profitable revenue stream. They increase their presence in fast-growing real estate markets like Dubai while strengthening brand loyalty.</li>
                        <li>For customers, the advantage is clear: premium interiors, reliable property management, and access to top-notch amenities. Branded residences for sale have great investment potential due to their higher rental yields, greater capital appreciation, and flexibility for short-term rentals.</li>
                    </ul>
                    <p><br>The trust that comes with a well-known worldwide brand is one of the main reasons branded residences are still gaining popularity. Brand familiarity provides comfort in a global market like Dubai, where consumers frequently come from various nations and backgrounds.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="formula-boxes d-flex">
                    <div>
                        <div class="formula-box">
                            <figure>
                                <img src="{{ URL::to('') }}/public/assets/images/image_173642082695.png" alt="Developer" class="">
                            </figure>
                            <figcaption><h3>Developer</h3></figcaption>
                        </div>
                        <div class="formula-box">
                            <figure>
                                <img src="{{ URL::to('') }}/public/assets/images/image_173642082696.png" alt="Brand" class="">
                            </figure>
                            <figcaption><h3>Brand</h3></figcaption>
                        </div>
                    </div>
                    <div class="formula-box center-box1">
                        <figure>
                            <img src="{{ URL::to('') }}/public/assets/images/image_173642082697.png" alt="Client" class="">
                        </figure>
                        <figcaption><h3>Client</h3></figcaption>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- TYPES OF BRANDED RESIDENCES --}}
{{-- ============================================================ --}}
<section class="residences-type-sec after-none">
    <div class="container">
        <div class="heading-pnel HeadingMiddleBorder">
            <div class="row">
                <div class="col-12">
                    <h2 class="m-0">Types of Branded Residences</h2>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-3 col-md-6 col-12">
                <div class="resid-type-box">
                    <div class="type-img">
                        <img src="{{ URL::to('') }}/public/assets/images/image_17325276200.png" alt="Residential Units within a Hotel">
                    </div>
                    <div class="type-content">
                        <h3>Residential Units within a Hotel</h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="resid-type-box">
                    <div class="type-img">
                        <img src="{{ URL::to('') }}/public/assets/images/image_17325276201.png" alt="Residential Development Adjacent to a Hotel">
                    </div>
                    <div class="type-content">
                        <h3>Residential Development Adjacent to a Hotel</h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="resid-type-box">
                    <div class="type-img">
                        <img src="{{ URL::to('') }}/public/assets/images/image_17325276202.png" alt="Residential Development with Hotel Management">
                    </div>
                    <div class="type-content">
                        <h3>Residential Development with Hotel Management</h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="resid-type-box">
                    <div class="type-img">
                        <img src="{{ URL::to('') }}/public/assets/images/image_17325276203.png" alt="Stand-Alone Residential Development with Brand Association">
                    </div>
                    <div class="type-content">
                        <h3>Stand-Alone Residential Development with Brand Association</h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- FEATURED BRANDED RESIDENCES GRID --}}
{{-- ============================================================ --}}
<section class="space position-relative logo-icon">
    <div class="container">

        <div class="heading-pnel HeadingMiddleBorder">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <h2 class="m-0">Featured Branded Residences in Dubai</h2>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="head-btn">
                        <a href="javascript:void(0)" onclick="openNav()" class="green-btn ml-auto">
                            <img src="{{ URL::to('') }}/public/assets/img/filter.svg" alt="Filters">
                            Filters
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="cards-main">
            <div class="row">

                @forelse($residences as $residence)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card-box">

                            <figure>
                                <div class="VillaText">
                                    <p>{{ $residence->brand_name ?? 'Branded Residence' }}</p>
                                </div>
                                <a href="{{ route('branded-residences.show', $residence->slug) }}">
                                    @if($residence->main_image)
                                        <img src="{{ URL::to('') }}/public/{{ $residence->main_image }}"
                                             onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/thumbnail-placeholder-gallery.png';"
                                             alt="{{ $residence->title }}">
                                    @else
                                        <img src="{{ URL::to('') }}/public/assets/img/thumbnail-placeholder-gallery.png"
                                             alt="{{ $residence->title }}">
                                    @endif
                                </a>
                            </figure>

                            <figcaption>
                                <a href="{{ route('branded-residences.show', $residence->slug) }}">
                                    <h3>{{ $residence->title }}</h3>
                                    @if($residence->community_name)
                                        <p>
                                            <img src="{{ URL::to('') }}/public/assets/img/hotel/map.svg" alt="Map">
                                            {{ $residence->community_name }}
                                        </p>
                                    @endif
                                    @if($residence->price_from)
                                        <h6>
                                            <span>From AED</span>
                                            {{ number_format($residence->price_from) }}/-
                                        </h6>
                                    @endif
                                </a>
                            </figcaption>

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No branded residences found.</p>
                        <a href="{{ route('branded-residences.index') }}" class="green-btn mt-3">Clear Filters</a>
                    </div>
                @endforelse

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- PAGINATION --}}
        {{-- ============================================================ --}}
        @if($residences->hasPages())
        <div class="pagination-main">
            <div class="row">
                <div class="col-12">
                    <div class="pagination-container">
                        <ul>

                            @if($residences->onFirstPage())
                                <li class="prev disabled"><span>Prev</span></li>
                            @else
                                <li class="prev"><a href="{{ $residences->previousPageUrl() }}">Prev</a></li>
                            @endif

                            @foreach($residences->getUrlRange(1, $residences->lastPage()) as $page => $url)
                                @if($page == $residences->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if($residences->hasMorePages())
                                <li class="next"><a href="{{ $residences->nextPageUrl() }}">Next</a></li>
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
{{-- FILTER SIDEBAR --}}
{{-- ============================================================ --}}
<form method="GET" action="{{ route('branded-residences.index') }}">

    <div id="filters-sidebar" class="sidenav">

        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
            <img src="{{ URL::to('') }}/public/assets/img/cross.svg" alt="Close" />
        </a>

        <div class="filter-head"><h2>Filters</h2></div>

        <div class="filter-boxes">

            {{-- Search --}}
            <div class="filter-box">
                <h4>Search</h4>
                <div class="filter-checkboxes">
                    <input type="text" name="search" class="form-control"
                           placeholder="Brand or community..."
                           value="{{ request('search') }}">
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

            {{-- Filter Buttons --}}
            <div class="filter-box">
                <div class="menu-btn-grup filter-btn-grp p-0">
                    <a class="btn border-btn" href="{{ route('branded-residences.index') }}">Clear Filters</a>
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
