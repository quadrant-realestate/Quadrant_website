@extends('website.layout.app')

@section('title', 'About — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', 'Every life needs a fixed point. Discover the story behind Quadrant Properties and the Four Bearings that guide how we work.')

@section('head')
<style>
    .qp-about-banner {
        background: #11203A;
        padding: 64px 0 48px;
        text-align: center;
    }
    .qp-about-banner h1 {
        font-family: 'Cormorant Garamond', serif;
        color: #fff;
        font-size: 36px;
        margin: 0;
    }

    .qp-about-story {
        max-width: 740px;
        margin: 0 auto;
        padding: 64px 20px;
    }
    .qp-about-lede {
        font-family: 'Cormorant Garamond', serif;
        font-size: 26px;
        font-style: italic;
        color: #11203A;
        text-align: center;
        margin: 0 0 40px;
        line-height: 1.5;
    }
    .qp-about-story p {
        font-size: 15px;
        line-height: 1.9;
        color: #1B2538;
        margin: 0 0 24px;
    }
    .qp-about-sign-off {
        font-family: 'Cormorant Garamond', serif;
        font-size: 20px;
        font-style: italic;
        color: #C8A965;
        text-align: center;
        margin: 40px 0 0;
    }

    /* Four Bearings (full) */
    .qp-bearings-section {
        background: #F4F5F7;
        padding: 64px 0;
    }
    .qp-bearings-heading {
        text-align: center;
        margin-bottom: 44px;
    }
    .qp-bearings-heading .qp-rule {
        width: 56px; height: 2px; background: #C8A965; margin: 0 auto 18px;
    }
    .qp-bearings-heading h2 {
        letter-spacing: 2.5px; text-transform: uppercase;
        color: #11203A; font-weight: 700; margin: 0;
    }
    .qp-bearings-grid {
        max-width: 880px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        padding: 0 20px;
    }
    .qp-bearing-card {
        background: #fff;
        border-radius: 4px;
        padding: 30px 26px;
        box-shadow: 0 2px 14px rgba(11,29,58,.06);
    }
    .qp-bearing-card.qp-consequence {
        border-left: 4px solid #C8A965;
    }
    .qp-bearing-mark {
        width: 50px; height: 50px; border-radius: 50%;
        border: 1px solid #d8e2ef;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cormorant Garamond', serif; font-size: 20px; font-weight: 600;
        color: #11203A; margin-bottom: 16px;
    }
    .qp-bearing-card.qp-consequence .qp-bearing-mark {
        border-color: #C8A965; color: #C8A965; background: #fffdf5;
    }
    .qp-bearing-card h3 {
        font-family: 'Cormorant Garamond', serif; font-size: 19px;
        color: #11203A; margin: 0 0 10px;
    }
    .qp-bearing-card.qp-consequence h3 { color: #C8A965; }
    .qp-bearing-card p {
        font-size: 14px; line-height: 1.7; color: #5B6678; margin: 0;
    }

    Replace your entire previous CSS block for this section with this complete version:

/* Founder & Partner Container section */
./* Container section with generous top & bottom breathing room */
.qp-founder-section {
    background-color: #fcf9f4;
    padding-top: 100px !important;
    padding-bottom: 100px !important;
    width: 100%;
}

/* Grid layout to place both cards side by side */
.qp-founders-grid {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 60px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 40px !important; /* Adds top/bottom safety padding inside the container */
    box-sizing: border-box;
}

/* Individual leadership card */
.qp-founder-card {
    display: flex;
    align-items: flex-start; /* Keeps photo pinned to the top */
    gap: 28px;
    flex: 1;
}

/* Round photo wrapper */
.qp-founder-photo {
    flex-shrink: 0;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    overflow: hidden;
    margin-top: 4px; /* Perfectly aligns the top curve of the photo with the name */
}

.qp-founder-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Text content */
.qp-founder-text {
    flex: 1;
}

.qp-founder-text h3 {
    font-family: Cormorant Garamond', serif;
    font-size: 1.6rem;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #111111;
    margin: 0 0 6px 0;
    line-height: 1.2;
}

.qp-founder-title {
    font-size: 1rem;
    color: #555555;
    margin: 0 0 20px 0;
    font-weight: 400;
}

.qp-founder-bio {
    font-size: 0.95rem;
    line-height: 1.7;
    color: #2b2b2b;
    margin: 0;
}

/* Tablet & Mobile Responsiveness */
@media (max-width: 991px) {
    .qp-founder-section {
        padding: 80px 0; /* Slightly reduced vertical padding for tablets */
    }

    .qp-founders-grid {
        flex-direction: column;
        gap: 50px;
        padding: 0 24px;
    }
}

@media (max-width: 576px) {
    .qp-founder-section {
        padding: 60px 0; /* Compact vertical padding for mobile */
    }

    .qp-founder-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .qp-founder-photo {
        margin-top: 0;
    }
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

      /* Where We Build — 3 region cards */
    .qp-regions-section {
        background: #F4F5F7;
        padding: 64px 0;
    }
    .qp-regions-heading {
        text-align: center;
        margin-bottom: 40px;
    }
    .qp-regions-heading .qp-rule {
        width: 56px; height: 2px; background: #C8A965; margin: 0 auto 18px;
    }
    .qp-regions-heading h2 {
         letter-spacing: 2.5px; text-transform: uppercase;
        color: #11203A; font-weight: 700; margin: 0;
    }
    .qp-regions-grid {
        max-width: 1000px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        padding: 0 20px;
    }
    .qp-region-card {
        background: #fff;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 2px 14px rgba(11,29,58,.07);
    }
    .qp-region-img { height: 180px; overflow: hidden; background: #e3e8ef; }
    .qp-region-img img { width: 100%; height: 100%; object-fit: cover; }
    .qp-region-body { padding: 22px; }
    .qp-region-body h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 19px; color: #11203A; margin: 0 0 8px;
    }
    .qp-region-body p {
        font-size: 13px; line-height: 1.6; color: #5B6678; margin: 0;
    }

      /* Off-Market / Giving Banner (shared style) */
    .qp-offmarket { position: relative; min-height: 320px; display: flex; align-items: center; overflow: hidden; }
    .qp-offmarket img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; }
    .qp-offmarket::after { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(11,29,58,.92) 0%, rgba(11,29,58,.55) 60%, rgba(11,29,58,.25) 100%); z-index: 1; }
    .qp-offmarket-inner { position: relative; z-index: 2; max-width: 480px; padding: 20px; }
    .qp-offmarket-inner .qp-eyebrow { font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: var(--qp-gold); font-weight: 700; margin-bottom: 12px; }
    .qp-offmarket-inner h2 { font-size: 30px; color: #fff; font-weight: 600; line-height: 1.25; margin: 0 0 14px; }
    .qp-offmarket-inner p { color: #d7e0ee; font-size: 14px; line-height: 1.6; margin: 0 0 24px; }

    .qp-container { max-width: 1180px; margin: 0 auto; padding: 0 20px; }
</style>
@endsection

@section('content')

{{-- PAGE BANNER --}}
<section class="qp-about-banner">
    <div class="container">
        <h1>About Quadrant</h1>
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
                        <li><span>About</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- FULL BRAND STORY --}}
{{-- ============================================================ --}}
<section>
    <div class="qp-about-story">

    <div class="qp-bearings-heading">
        <div class="qp-rule"></div>
        <h2>Every life needs a fixed point.</h2>
    </div>
        <!-- <h2 class="qp-about-lede"></h2> -->

        <p>Long before maps had borders, explorers carried a quadrant — an instrument that read the angle of the stars and told them one thing: where they stood. With it, a person alone on open water could hold a course, and find their way home.</p>

        <p>We took the name because it is the most honest description of what property should be. A home is not a transaction. It is the fixed point of a life — the coordinate from which everything else is measured. Where wealth is anchored. Where a family decides who it intends to be.</p>

        <p>Quadrant Properties exists to help discerning people find that point with precision. We work at the top of Dubai's market, with particular focus on off-plan — the residences and landmark developments still taking shape, where the city's future, and its best value, is being built today.</p>

        <p>And we work in a particular way: we advise, we do not sell. Our loyalty sits with your long-term interest, not with this quarter's commission. If a project is not right, we say so. If the timing is wrong, we say that too. The relationship is the asset; the transaction is one moment within it.</p>

        <p>We also believe that those who deal in homes carry a responsibility to those who have none. So every quarter, Quadrant aims to give minimum ten percent of its profit to building permanent homes for underprivileged families around the world — today in India, Sudan and Yemen. It is not a campaign or an afterthought. It is part of how the business is built.</p>

        <p>In a market crowded with noise, Quadrant offers something quieter and rarer: counsel you can trust, properties worthy of our name, and a clear sense of where you stand.</p>

        <p class="qp-about-sign-off">Know where you stand.</p>

    </div>
</section>

{{-- ============================================================ --}}
{{-- THE FOUR BEARINGS (FULL) --}}
{{-- ============================================================ --}}
<section class="qp-bearings-section">
    <div class="qp-bearings-heading">
        <div class="qp-rule"></div>
        <h2>The Four Bearings</h2>
    </div>

    <div class="qp-bearings-grid">

        <div class="qp-bearing-card">
            <div class="qp-bearing-mark">N</div>
            <h3>Counsel</h3>
            <p>We advise; we do not sell. Our guidance stays honest even when honesty costs us the deal.</p>
        </div>

        <div class="qp-bearing-card">
            <div class="qp-bearing-mark">E</div>
            <h3>Craft</h3>
            <p>We represent only property of genuine quality. If it would not carry our name, it does not carry our name.</p>
        </div>

        <div class="qp-bearing-card">
            <div class="qp-bearing-mark">S</div>
            <h3>Custody</h3>
            <p>Your interest is held above ours, as a long-term, confidential trust.</p>
        </div>

        <div class="qp-bearing-card qp-consequence">
            <div class="qp-bearing-mark">W</div>
            <h3>Consequence</h3>
            <p>Every year, we give a portion of our profit to building permanent homes for underprivileged families worldwide. Giving is built into how Quadrant operates, not added as an afterthought.</p>
        </div>

    </div>
</section>

{{-- ============================================================ --}}
{{-- WHY WE DO IT --}}
{{-- ============================================================ --}}
<section>
    <div class="qp-giving-section">
        <h2>Why We Do It</h2>
        <p>
            We believe that those who deal in homes carry a responsibility to those who have none.
            A home is the fixed point of a life. For millions of families, that point has been lost
            to conflict, poverty or displacement. Helping rebuild it is, to us, simply part of doing
            this work properly.
        </p>
    </div>
</section>

{{-- SECTION 7 — GIVING (key feature band — given real visual weight, not a footnote) --}}
    <section class="qp-offmarket" style="min-height:380px;">
        <img src="{{ URL::to('') }}/public/assets/images/quadrant-giving.png" alt="Building homes worldwide"
             style="filter: brightness(0.4);">
        <div class="qp-container">
            <div class="qp-offmarket-inner" style="max-width:600px;">
                <div class="qp-eyebrow">Our Giving Commitment</div>
                <h2>Find your place.<br>Help build theirs.</h2>
                <p>
                    Every year, Quadrant aims to give a portion of its profit to building
                    permanent homes for underprivileged families around the world — today in
                    India, Sudan and Yemen. It is part of how we are built, not an afterthought.
                </p>
                <!-- <a href="{{ route('giving') }}" class="qp-btn qp-btn-outline-light">How we give →</a> -->
            </div>
        </div>
    </section>

{{-- ============================================================ --}}
{{-- WHERE WE BUILD --}}
{{-- ============================================================ --}}
<section class="qp-regions-section">
    <div class="qp-regions-heading">
        <div class="qp-rule"></div>
        <h2>Where We Build</h2>
    </div>

    <div class="qp-regions-grid">

        <div class="qp-region-card">
            <div class="qp-region-img">
                <img src="{{ URL::to('') }}/public/assets/images/india.webp"
                     alt="India"
                     onerror="this.onerror=null; this.style.display='none';">
            </div>
            <div class="qp-region-body">
                <h3>India</h3>
                <p>Permanent homes for families without secure shelter.</p>
            </div>
        </div>

        <div class="qp-region-card">
            <div class="qp-region-img">
                <img src="{{ URL::to('') }}/public/assets/images/sudan.webp"
                     alt="Sudan"
                     onerror="this.onerror=null; this.style.display='none';">
            </div>
            <div class="qp-region-body">
                <h3>Sudan</h3>
                <p>Rebuilding homes for families displaced by conflict.</p>
            </div>
        </div>

        <div class="qp-region-card">
            <div class="qp-region-img">
                <img src="{{ URL::to('') }}/public/assets/images/yemen.webp"
                     alt="Yemen"
                     onerror="this.onerror=null; this.style.display='none';">
            </div>
            <div class="qp-region-body">
                <h3>Yemen</h3>
                <p>Safe, lasting shelter for families in need.</p>
            </div>
        </div>

    </div>
</section>

{{-- ============================================================ --}}
{{-- FOUNDER NOTE (PLACEHOLDER — client to supply) --}}
{{-- ============================================================ --}}
<section class="qp-founder-section">
    <div class="qp-founders-grid">

        {{-- LEFT: Founder Card --}}
        <div class="qp-founder-card">
            <div class="qp-founder-photo">
                <img src="{{ URL::to('') }}/public/assets/images/Faisal_dp.png" alt="Faisal Farooqui - Founder & CEO of Quadrant Properties">
            </div>
            <div class="qp-founder-text">
                <h3>Faisal Farooqui</h3>
                <p class="qp-founder-title">Founder &amp; CEO, Quadrant Properties</p>
                <p class="qp-founder-bio">
                    Behind every property is a decision that shapes a family's future, a portfolio's trajectory, the next chapter of someone's life. That conviction is why I founded Quadrant.

We built this firm on a single, uncompromising idea, that clients deserve an advisor, not a salesperson. Counsel you can trust. Guidance shaped around your goals, not ours. And a partner who stays personally invested long after the deal is signed.

Whether you're deploying significant capital or searching for the place you'll call home, our commitment never changes to earn your trust, protect your interests, and turn ambition into outcome.

That isn't simply how we work. It's why we exist.
                </p>
            </div>
        </div>

      

    </div>
</section>

@endsection