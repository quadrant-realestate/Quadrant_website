@extends('website.layout.app')

@section('title', 'Sell Your Property — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', 'Sell your property in Dubai with Quadrant Properties. Honest valuation, discreet marketing, and end-to-end support — advised, never pressured.')

@section('head')
<style>
    /* ============================================================
       SELL PAGE — Quadrant brand system
    ============================================================ */

    /* Hero */
    .qp-sell-hero {
        position: relative;
        min-height: 480px;
        display: flex;
        align-items: center;
        overflow: hidden;
    }
    .qp-sell-hero img.qp-sell-hero-bg {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        object-fit: cover; z-index: 0;
    }
    .qp-sell-hero::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(90deg, rgba(11,29,58,.85) 0%, rgba(11,29,58,.55) 55%, rgba(11,29,58,.15) 100%);
        z-index: 1;
    }
    .qp-sell-hero-inner {
        position: relative; z-index: 2;
        max-width: 600px;
        padding: 60px 20px;
    }
    .qp-sell-hero-inner .qp-rule {
        width: 56px; height: 2px;
        background: #C8A965;
        margin: 0 0 22px;
    }
    .qp-sell-hero-inner h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 40px;
        color: #fff;
        margin: 0 0 18px;
        line-height: 1.2;
    }
    .qp-sell-hero-inner p {
        color: #d7e0ee;
        font-size: 15px;
        line-height: 1.75;
        margin: 0 0 28px;
        max-width: 480px;
    }
    .qp-sell-hero-inner a {
        display: inline-block;
        background: #C8A965;
        color: #11203A;
        padding: 13px 28px;
        font-size: 12px;
        letter-spacing: .5px;
        text-transform: uppercase;
        font-weight: 700;
        border-radius: 2px;
        text-decoration: none;
        transition: background .2s ease;
    }
    .qp-sell-hero-inner a:hover { background: #b8965a; color: #11203A; }

    /* Why Sell section */
    .qp-sell-why {
        padding: 64px 0;
        background: #F4F5F7;
    }
    .qp-sell-why-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
        margin-top: 40px;
    }
    .qp-sell-why-card {
        background: #fff;
        border-radius: 4px;
        padding: 32px 26px;
        box-shadow: 0 2px 14px rgba(11,29,58,.06);
        border-top: 3px solid transparent;
        transition: border-color .2s ease;
    }
    .qp-sell-why-card:hover { border-top-color: #C8A965; }
    .qp-sell-why-card .qp-icon {
        width: 48px; height: 48px;
        border-radius: 50%;
        border: 1px solid #C8A965;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 18px;
    }
    .qp-sell-why-card .qp-icon svg {
        width: 22px; height: 22px;
        stroke: #C8A965;
    }
    .qp-sell-why-card h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 19px;
        color: #11203A;
        margin: 0 0 10px;
    }
    .qp-sell-why-card p {
        font-size: 13px;
        color: #5B6678;
        line-height: 1.7;
        margin: 0;
    }

    /* Step by Step */
    .qp-sell-steps { padding: 64px 0; }
    .qp-sell-steps-heading {
        text-align: center;
        margin-bottom: 44px;
    }
    .qp-sell-steps-heading .qp-rule {
        width: 56px; height: 2px;
        background: #C8A965;
        margin: 0 auto 18px;
    }
    .qp-sell-steps-heading h2 {
        /* font-size: 13px; */
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #11203A;
        font-weight: 700;
        margin: 0;
    }
    .qp-steps-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
    }
    .qp-step-card {
        background: #fff;
        border: 1px solid #e3e8ef;
        border-radius: 4px;
        padding: 28px 22px;
        position: relative;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .qp-step-card:hover {
        border-color: #C8A965;
        box-shadow: 0 6px 20px rgba(11,29,58,.08);
    }
    .qp-step-number {
        font-family: 'Cormorant Garamond', serif;
        font-size: 36px;
        font-weight: 600;
        color: #C8A965;
        line-height: 1;
        margin-bottom: 16px;
        display: block;
    }
    .qp-step-icon {
        width: 52px; height: 52px;
        background: #F4F5F7;
        border-radius: 4px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 18px;
    }
    .qp-step-icon svg {
        width: 26px; height: 26px;
        stroke: #11203A;
    }
    .qp-step-card h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 18px;
        color: #11203A;
        margin: 0 0 10px;
    }
    .qp-step-card p {
        font-size: 13px;
        color: #5B6678;
        line-height: 1.7;
        margin: 0;
    }

    /* Valuation CTA band */
    .qp-sell-cta {
        background: #11203A;
        padding: 64px 0;
        text-align: center;
    }
    .qp-sell-cta h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 28px;
        color: #fff;
        margin: 0 0 12px;
    }
    .qp-sell-cta p {
        color: #c7d2e2;
        font-size: 15px;
        margin: 0 0 28px;
        max-width: 560px;
        margin-left: auto;
        margin-right: auto;
    }
    .qp-sell-cta a {
        display: inline-block;
        background: #C8A965;
        color: #11203A;
        padding: 13px 28px;
        font-size: 12px;
        letter-spacing: .5px;
        text-transform: uppercase;
        font-weight: 700;
        border-radius: 2px;
        text-decoration: none;
        transition: background .2s ease;
    }
    .qp-sell-cta a:hover { background: #b8965a; }

    /* Enquiry Form */
    .qp-sell-form { padding: 64px 0; background: #F4F5F7; }
    .qp-sell-form-inner {
        max-width: 680px;
        margin: 0 auto;
        background: #fff;
        border-radius: 4px;
        padding: 40px;
        box-shadow: 0 2px 20px rgba(11,29,58,.07);
    }
    .qp-sell-form-inner h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 24px;
        color: #11203A;
        margin: 0 0 8px;
    }
    .qp-sell-form-inner p {
        font-size: 14px;
        color: #5B6678;
        margin: 0 0 28px;
    }
    .qp-sf-group { margin-bottom: 16px; }
    .qp-sf-group label {
        display: block;
        font-size: 10px;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #5B6678;
        font-weight: 700;
        margin-bottom: 7px;
    }
    .qp-sf-group input,
    .qp-sf-group select,
    .qp-sf-group textarea {
        width: 100%;
        border: 1px solid #dfe5ec;
        border-radius: 2px;
        padding: 11px 14px;
        font-size: 14px;
        color: #1B2538;
        background: #fff;
        font-family: 'Inter', sans-serif;
        transition: border-color .2s ease;
    }
    .qp-sf-group input:focus,
    .qp-sf-group select:focus,
    .qp-sf-group textarea:focus {
        outline: none;
        border-color: #C8A965;
    }
    .qp-sf-group textarea { resize: vertical; min-height: 100px; }
    .qp-sf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .qp-sf-submit {
        width: 100%;
        background: #11203A;
        color: #fff;
        border: none;
        padding: 13px;
        font-size: 12px;
        letter-spacing: .5px;
        text-transform: uppercase;
        font-weight: 600;
        border-radius: 2px;
        cursor: pointer;
        margin-top: 6px;
        transition: background .2s ease;
    }
    .qp-sf-submit:hover { background: #0B1D3A; }

    /* Responsive */
    @media (max-width: 991px) {
        .qp-sell-why-grid { grid-template-columns: 1fr; }
        .qp-steps-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575px) {
        .qp-sell-hero-inner h1 { font-size: 30px; }
        .qp-steps-grid { grid-template-columns: 1fr; }
        .qp-sf-row { grid-template-columns: 1fr; }
        .qp-sell-form-inner { padding: 28px 20px; }
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



{{-- ============================================================ --}}
{{-- SECTION 1 — HERO --}}
{{-- ============================================================ --}}
<section class="qp-sell-hero">
    <img class="qp-sell-hero-bg"
         src="{{ URL::to('') }}/public/assets/images/sell-banner.jpg"
         alt="Sell your property with Quadrant Properties"
         onerror="this.onerror=null; this.src='{{ URL::to('') }}/public/assets/img/placeholder.jpg';">
    <div class="container">
        <div class="qp-sell-hero-inner">
            <div class="qp-rule"></div>
            <h1>Sell your property<br>with us.</h1>
            <p>
                We combine local market knowledge with a discreet, advice-led approach - 
                so your property reaches the right buyers, at the right time,
                without pressure or compromise.
            </p>
            <a href="{{ route('contact') }}">Request a Valuation</a>
        </div>
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
                        <li><span>Sell Your Property</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- WHY SELL WITH QUADRANT --}}
{{-- ============================================================ --}}
<!-- <section class="qp-sell-why">
    <div class="container">
        <div style="text-align:center;">
            <div style="width:56px; height:2px; background:#C8A965; margin:0 auto 18px;"></div>
            <h2 style=" letter-spacing:2.5px; text-transform:uppercase; color:#11203A; font-weight:700; margin:0;">
                Why Sell with Quadrant
            </h2>
        </div>

        <div class="qp-sell-why-grid">

            <div class="qp-sell-why-card">
                <div class="qp-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.306a11.95 11.95 0 015.814-5.518l2.74-1.22m0 0l-5.94-2.281m5.94 2.28l-2.28 5.941" /></svg>
                </div>
                <h3>Accurate Valuation</h3>
                <p>Our team provides an honest, data-led assessment of your property's market value — no inflated figures to win the listing.</p>
            </div>

            <div class="qp-sell-why-card">
                <div class="qp-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                </div>
                <h3>Discreet Marketing</h3>
                <p>Your property is presented to qualified buyers through our private network — before it reaches the public market, if you prefer.</p>
            </div>

            <div class="qp-sell-why-card">
                <div class="qp-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                </div>
                <h3>End-to-End Support</h3>
                <p>From initial valuation through negotiation, due diligence, and transfer at Dubai Land Department — we stay with you every step.</p>
            </div>

        </div>
    </div>
</section> -->

{{-- ============================================================ --}}
{{-- SECTION 2 — STEP BY STEP --}}
{{-- ============================================================ --}}
<section class="qp-sell-steps">
    <div class="container">
        <div class="qp-sell-steps-heading">
            <div class="qp-rule"></div>
            <h2>Step by Step</h2>
        </div>

        <div class="qp-steps-grid">

            <div class="qp-step-card">
                <span class="qp-step-number">01</span>
                <div class="qp-step-icon">
                    <img src="{{ URL::to('') }}/public/assets/img/1-icon.png" width="35" height="auto">
                </div>
                <h3>Property Valuation</h3>
                <p>Your advisor visits the property and provides a thorough market analysis based on recent comparable transactions and current demand.</p>
            </div>

            <div class="qp-step-card">
                <span class="qp-step-number">02</span>
                <div class="qp-step-icon">
                    <img src="{{ URL::to('') }}/public/assets/img/2-icon.png" width="35" height="auto">
                </div>
                <h3>Marketing</h3>
                <p>Professional photography, targeted digital marketing, and placement across our private buyer network and key property portals.</p>
            </div>

            <div class="qp-step-card">
                <span class="qp-step-number">03</span>
                <div class="qp-step-icon">
                    <img src="{{ URL::to('') }}/public/assets/img/3-icon.png" width="35" height="auto">
                </div>
                <h3>Offer Agreed</h3>
                <p>Your advisor manages viewings with qualified buyers, negotiates on your behalf, and keeps you informed at every stage until the offer is agreed.</p>
            </div>

            <div class="qp-step-card">
                <span class="qp-step-number">04</span>
                <div class="qp-step-icon">
                    <img src="{{ URL::to('') }}/public/assets/img/4-icon.png" width="35" height="auto">
                </div>
                <h3>Transfer of Ownership</h3>
                <p>Your advisor coordinates all documentation and manages the final transfer of ownership at Dubai Land Department — calmly and without pressure.</p>
            </div>

        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- VALUATION CTA BAND --}}
{{-- ============================================================ --}}


{{-- CLOSING ENQUIRY CTA --}}
<section class="qp-services-cta">
    <div class="container">
        <h2>Ready to understand your property's value?</h2>
        <p>Begin with an honest conversation — no obligation, no pressure. We'll tell you what your property is worth and what selling with us looks like.</p>
        <a href="{{ route('contact') }}">Speak with an Advisor</a>
    </div>
</section>

@endsection