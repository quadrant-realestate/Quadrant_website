@extends('website.layout.app')

@section('title', 'Building Homes Worldwide — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', 'Quadrant gives 10% of its profit, every quarter, to building permanent homes for underprivileged families around the world — today in India, Sudan and Yemen.')

@section('head')
<style>
    /* ============================================================
       GIVING PAGE — "Building Homes Worldwide"
       Treated with the same visual weight as Off-Plan Projects
    ============================================================ */

    /* Hero */
    .qp-giving-hero {
        position: relative;
        min-height: 460px;
        display: flex;
        align-items: center;
        overflow: hidden;
    }
    .qp-giving-hero img {
        position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
        filter: brightness(0.5);
    }
    .qp-giving-hero::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(11,29,58,.55) 0%, rgba(11,29,58,.85) 100%);
    }
    .qp-giving-hero-inner {
        position: relative; z-index: 2;
        max-width: 700px;
        margin: 0 auto;
        text-align: center;
        padding: 20px;
    }
    .qp-giving-hero-inner h1 {
        font-family: 'Cormorant Garamond', serif;
        color: #fff;
        font-size: 38px;
        margin: 0 0 16px;
    }
    .qp-giving-hero-inner p {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        color: #C8A965;
        font-size: 20px;
        margin: 0;
    }

    /* Shared section styles */
    .qp-giving-section {
        max-width: 740px;
        margin: 0 auto;
        padding: 56px 20px;
    }
    .qp-giving-section h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 24px;
        color: #11203A;
        margin: 0 0 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid #C8A965;
        display: inline-block;
    }
    .qp-giving-section p {
        font-size: 15px;
        line-height: 1.85;
        color: #1B2538;
        margin: 0;
    }

    /* The Commitment — lead block, extra emphasis */
    .qp-commitment-block {
        background: #11203A;
        padding: 56px 20px;
        text-align: center;
    }
    .qp-commitment-block .qp-commitment-inner {
        max-width: 700px;
        margin: 0 auto;
    }
    .qp-commitment-block p {
        font-family: 'Cormorant Garamond', serif;
        font-size: 21px;
        line-height: 1.7;
        color: #fff;
        margin: 0;
    }

  

    /* How It Works */
    .qp-how-it-works {
        list-style: none;
        padding: 0;
        margin: 18px 0 0;
    }
    .qp-how-it-works li {
        display: flex;
        gap: 14px;
        padding: 18px 0;
        border-bottom: 1px solid #eef1f5;
        font-size: 14px;
        line-height: 1.7;
        color: #1B2538;
    }
    .qp-how-it-works li:last-child { border-bottom: none; }
    .qp-how-it-works .qp-dash {
        color: #C8A965;
        font-weight: 700;
        flex-shrink: 0;
    }
    .qp-how-it-works strong { color: #11203A; }

    /* Closing prompt */
    .qp-giving-closing {
        background: #f4f5f7;
        padding: 56px 20px;
        text-align: center;
    }
    .qp-giving-closing p {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        font-size: 19px;
        line-height: 1.7;
        color: #11203A;
        max-width: 680px;
        margin: 0 auto 28px;
    }
    .qp-giving-closing a {
        display: inline-block;
        background: transparent;
        color: #C8A965;
        border: 1px solid #C8A965;
        padding: 12px 26px;
        font-size: 12px;
        letter-spacing: .4px;
        text-transform: uppercase;
        font-weight: 600;
        border-radius: 2px;
        text-decoration: none;
        transition: all .2s ease;
    }
    .qp-giving-closing a:hover {
        background: #C8A965;
        color: #11203A;
    }

    @media (max-width: 767px) {
        .qp-regions-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')



{{-- ============================================================ --}}
{{-- HERO --}}
{{-- ============================================================ --}}
<section class="qp-giving-hero">
    <img src="{{ URL::to('') }}/public/assets/images/quadrant-hero-dubai.jpg" alt="Building homes worldwide">
    <div class="qp-giving-hero-inner">
        <h1>Building Homes Worldwide</h1>
        <p>Find your place. Help build theirs.</p>
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
                        <li><span>Giving</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- ============================================================ --}}
{{-- THE COMMITMENT (LEAD BLOCK) --}}
{{-- ============================================================ --}}
<section class="qp-commitment-block">
    <div class="qp-commitment-inner">
        <p>
            Quadrant gives 10% of its profit, every quarter, to building permanent homes for
            underprivileged families around the world. Not a one-off donation, and not a
            marketing campaign — a standing commitment, built into how the business runs.
        </p>
    </div>
</section>



{{-- ============================================================ --}}
{{-- HOW IT WORKS --}}
{{-- ============================================================ --}}
<section>
    <div class="qp-giving-section">
        <h2>How It Works</h2>

        <ul class="qp-how-it-works">
            <li>
                <span class="qp-dash">—</span>
                <span><strong>10% of profit, quarterly.</strong> A fixed share, set aside every quarter — not left to year-end.</span>
            </li>
            <li>
                <span class="qp-dash">—</span>
                <span><strong>Delivered through partners.</strong> Funds are channelled through established humanitarian builders already working on the ground (partners to be named).</span>
            </li>
            <li>
                <span class="qp-dash">—</span>
                <span><strong>Permanent homes, not temporary relief.</strong> We fund homes built to last, owned with dignity.</span>
            </li>
            <li>
                <span class="qp-dash">—</span>
                <span><strong>Transparent.</strong> We will report what was given and where, as the programme grows.</span>
            </li>
        </ul>
    </div>
</section>

{{-- ============================================================ --}}
{{-- CLOSING PROMPT --}}
{{-- ============================================================ --}}
<section class="qp-giving-closing">
    <p>
        When you build your future with Quadrant, you help build someone else's.
        That is the standard we hold ourselves to.
    </p>
    <a href="{{ route('contact') }}">Speak with an Advisor</a>
</section>

@endsection