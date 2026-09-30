@extends('website.layout.app')

@section('title', 'Communities in Dubai — ' . setting('site_name', 'Quadrant Properties Dubai'))
@section('meta_description', 'Explore Dubai\'s most prestigious communities with ' . setting('site_name', 'Quadrant Properties Dubai') . '. Find luxury properties in Palm Jumeirah, Dubai Marina, Downtown Dubai and more.')

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
</style>
@endsection

@section('content')

{{-- PAGE HERO --}}
<section class="qp-offplan-banner">
    <div class="container">
        <h1>Communities</h1>
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
                        <li><span>Communities</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- COMMUNITIES GRID --}}
{{-- ============================================================ --}}
<section class="space position-relative">
    <div class="container">

        {{-- Top Bar --}}
        <div class="listing-top-area">
            <div class="row">
                <div class="col-12">
                    <div class="listing-top-area-container">
                        <div class="item-counter">
                            <p>Results: <span>{{ $communities->total() }} Communities</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Community Cards --}}
        <div class="cards-main">
            <div class="row">

                @forelse($communities as $community)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="new-development card-box">
                            <a href="{{ route('communities.show', $community->slug) }}">
                                <figure>
                                    @if($community->image)
                                        <img src="{{ URL::to('') }}/public/{{ $community->image }}"
                                             onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/thumbnail-placeholder-gallery.png';"
                                             alt="{{ $community->name }}">
                                    @else
                                        <img src="{{ URL::to('') }}/public/assets/img/thumbnail-placeholder-gallery.png"
                                             alt="{{ $community->name }}">
                                    @endif
                                </figure>
                            </a>
                            <figcaption>
                                <a href="{{ route('communities.show', $community->slug) }}">
                                    <h3>{{ $community->name }}</h3>
                                </a>
                            </figcaption>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No communities found.</p>
                    </div>
                @endforelse

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- PAGINATION --}}
        {{-- ============================================================ --}}
        @if($communities->hasPages())
        <div class="pagination-main">
            <div class="row">
                <div class="col-12">
                    <div class="pagination-container">
                        <ul>

                            @if($communities->onFirstPage())
                                <li class="prev disabled"><span>Prev</span></li>
                            @else
                                <li class="prev"><a href="{{ $communities->previousPageUrl() }}">Prev</a></li>
                            @endif

                            @foreach($communities->getUrlRange(1, $communities->lastPage()) as $page => $url)
                                @if($page == $communities->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if($communities->hasMorePages())
                                <li class="next"><a href="{{ $communities->nextPageUrl() }}">Next</a></li>
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
