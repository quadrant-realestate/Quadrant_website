@extends('website.layout.app')

@section('title', 'How We Work — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', 'One standard of care, across everything we do: advice first, always. Off-Plan Advisory, Luxury Acquisition, Investment Guidance, Portfolio Stewardship.')

@section('head')
<style>
    .qp-services-banner {
        background: #11203A;
        padding: 64px 0 48px;
        text-align: center;
    }
    .qp-services-banner h1 {
        font-family: 'Cormorant Garamond', serif;
        color: #fff;
        font-size: 36px;
        margin: 0 0 14px;
    }
    .qp-services-banner p {
        color: #c7d2e2;
        font-size: 15px;
        max-width: 560px;
        margin: 0 auto;
    }

    .qp-services-list {
        max-width: 860px;
        margin: 0 auto;
        padding: 64px 20px;
    }
    .qp-service-block {
        display: grid;
        grid-template-columns: 60px 1fr;
        gap: 24px;
        padding: 40px 0;
        border-bottom: 1px solid #eef1f5;
    }
    .qp-service-block:last-child { border-bottom: none; }
    .qp-service-number {
        font-family: 'Cormorant Garamond', serif;
        font-size: 36px;
        font-weight: 600;
        color: #C8A965;
        line-height: 1;
    }
    .qp-service-block h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 24px;
        color: #11203A;
        margin: 0 0 14px;
    }
    .qp-service-block p {
        font-size: 15px;
        line-height: 1.8;
        color: #1B2538;
        margin: 0;
    }

    .qp-services-cta {
        background: #F4F5F7;
        padding: 56px 0;
        text-align: center;
        border-top: 2px solid #C8A965;
    }
    .qp-services-cta h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 26px;
        color: #11203A;
        margin: 0 0 12px;
    }
    .qp-services-cta p {
        color: #5B6678;
        font-size: 14px;
        margin: 0 0 26px;
    }
    .qp-services-cta a {
        display: inline-block;
        background: #11203A;
        color: #fff;
        padding: 13px 28px;
        font-size: 13px;
        letter-spacing: .4px;
        text-transform: uppercase;
        font-weight: 600;
        border-radius: 2px;
        text-decoration: none;
    }

    @media (max-width: 575px) {
        .qp-service-block { grid-template-columns: 1fr; gap: 8px; }
    }
</style>
@endsection

@section('content')

{{-- PAGE HERO --}}
<section class="qp-services-banner">
    <div class="container">
        <h1>How We Work</h1>
        <p>One standard of care, across everything we do: advice first, always.</p>
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
                        <li><span>Services</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FOUR SERVICE BLOCKS --}}
<section>
    <div class="qp-services-list">

        <div class="qp-service-block">
            <div class="qp-service-number">01</div>
            <div>
                <h2>Off-Plan Advisory</h2>
                <p>Independent guidance on Dubai's off-plan market — from launch access and developer assessment to payment plans and handover timelines. We help you buy into the right project, at the right time, for the right reasons.</p>
            </div>
        </div>

        <div class="qp-service-block">
            <div class="qp-service-number">02</div>
            <div>
                <h2>Luxury Acquisition</h2>
                <p>Sourcing and securing high-end residences with discretion. We represent your interest through negotiation, due diligence, and closing — calmly and without pressure.</p>
            </div>
        </div>

        <div class="qp-service-block">
            <div class="qp-service-number">03</div>
            <div>
                <h2>Investment Guidance</h2>
                <p>Data-led advice for buyers thinking in years, not months. We assess fundamentals, location and timing so your capital is placed with clarity.</p>
            </div>
        </div>

        <div class="qp-service-block">
            <div class="qp-service-number">04</div>
            <div>
                <h2>Portfolio Stewardship</h2>
                <p>Our relationship continues after the keys change hands — ongoing advice on holding, leasing, and growing a property portfolio over time.</p>
            </div>
        </div>

    </div>
</section>

{{-- CLOSING ENQUIRY CTA --}}
<section class="qp-services-cta">
    <div class="container">
        <h2>Considering a move, or an investment?</h2>
        <p>Begin with a conversation — not a pitch.</p>
        <a href="{{ route('contact') }}">Speak with an Advisor</a>
    </div>
</section>

@endsection