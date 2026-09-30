@extends('website.layout.app')

@section('title', 'Speak with an Advisor — ' . setting('site_name', 'Quadrant Properties'))
@section('meta_description', 'No pitch, no pressure — just a conversation about where you want to stand. Tell us what you\'re looking for, and we\'ll be in touch.')

@section('head')
<style>
    .qp-contact-banner {
        background: #11203A;
        padding: 56px 0 40px;
        text-align: center;
    }
    .qp-contact-banner h1 {
        font-family: 'Cormorant Garamond', serif;
        color: #fff;
        font-size: 34px;
        margin: 0 0 14px;
    }
    .qp-contact-banner p {
        color: #c7d2e2;
        font-size: 15px;
        max-width: 580px;
        margin: 0 auto;
        line-height: 1.7;
    }

    .qp-contact-wrap {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 0;
        min-height: 580px;
    }

    /* Left — contact details */
    .qp-contact-left {
        background: #F4F5F7;
        padding: 48px 36px;
        border-right: 1px solid #eef1f5;
    }
    .qp-contact-left h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 20px;
        color: #11203A;
        margin: 0 0 28px;
        padding-bottom: 14px;
        border-bottom: 2px solid #C8A965;
        display: inline-block;
    }
    .qp-cont-item { margin-bottom: 22px; }
    .qp-cont-label {
        font-size: 10px;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #5B6678;
        font-weight: 700;
        margin: 0 0 5px;
        display: block;
    }
    .qp-cont-item p, .qp-cont-item a {
        font-size: 14px;
        color: #1B2538;
        margin: 0;
        line-height: 1.6;
        text-decoration: none;
    }
    .qp-cont-item a:hover { color: #C8A965; }
    .qp-cont-divider {
        width: 100%;
        height: 1px;
        background: #e3e8ef;
        margin: 22px 0;
    }

    /* Right — form */
    .qp-contact-right {
        padding: 48px 50px;
        background: #fff;
    }
    .qp-contact-right h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 22px;
        color: #11203A;
        margin: 0 0 24px;
    }
    .qp-cf-group { margin-bottom: 18px; }
    .qp-cf-group label {
        display: block;
        font-size: 10px;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #5B6678;
        font-weight: 700;
        margin-bottom: 7px;
    }
    .qp-cf-group input,
    .qp-cf-group select,
    .qp-cf-group textarea {
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
    .qp-cf-group input:focus,
    .qp-cf-group select:focus,
    .qp-cf-group textarea:focus {
        outline: none;
        border-color: #C8A965;
    }
    .qp-cf-group textarea { resize: vertical; min-height: 110px; }
    .qp-cf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .qp-contact-submit {
        background: #11203A;
        color: #fff;
        border: none;
        padding: 13px 32px;
        font-size: 12px;
        letter-spacing: .5px;
        text-transform: uppercase;
        font-weight: 600;
        border-radius: 2px;
        cursor: pointer;
        transition: background .2s ease;
    }
    .qp-contact-submit:hover { background: #0B1D3A; }
    .qp-success-msg {
        background: #f0faf5;
        border: 1px solid #b2dfc9;
        border-radius: 4px;
        padding: 14px 18px;
        font-size: 14px;
        color: #1B2538;
        margin-bottom: 20px;
    }

    @media (max-width: 991px) {
        .qp-contact-wrap { grid-template-columns: 1fr; }
        .qp-contact-left { border-right: none; border-bottom: 1px solid #eef1f5; }
        .qp-contact-right { padding: 36px 24px; }
        .qp-cf-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')



{{-- PAGE BANNER --}}
<section class="qp-contact-banner">
    <div class="container">
        <h1>Speak with an Advisor</h1>
        <p>No pitch, no pressure - just a conversation about where you want to stand. Tell us what you're looking for, and we'll be in touch.</p>
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
                        <li><span>Contact</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CONTACT GRID --}}
<section>
    <div class="qp-contact-wrap">

        {{-- LEFT — Direct Contact Details --}}
        <div class="qp-contact-left">
            <h3>Direct Contact</h3>

            {{-- Office Address --}}
            <div class="qp-cont-item">
                <span class="qp-cont-label">Office</span>
                @if(setting('office_address'))
                    <p>{{ setting('office_address') }}</p>
                @else
                    <p style="color:#8a93a3;">Office 805, Concord Tower, Dubai Media City, Dubai</p>
                @endif
            </div>

            {{-- Phone --}}
            <div class="qp-cont-item">
                <span class="qp-cont-label">Phone</span>
                @if(setting('contact_phone'))
                    <a href="tel:{{ setting('contact_phone') }}">{{ setting('contact_phone') }}</a>
                @else
                    <a href="tel:+971524233010">+971524233010</a>
                @endif
            </div>

            {{-- Email --}}
            <div class="qp-cont-item">
                <span class="qp-cont-label">Email</span>
                @if(setting('contact_email'))
                    <a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a>
                @else
                    <a href="mailto:info@quadrant.ae">info@quadrant.ae</a>
                @endif
            </div>

            {{-- WhatsApp --}}
            <div class="qp-cont-item">
                <span class="qp-cont-label">WhatsApp</span>
                @if(setting('whatsapp_number'))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('whatsapp_number')) }}" target="_blank">
                        {{ setting('whatsapp_number') }}
                    </a>
                @else
                    <a href="https://wa.me/971524233010" target="_blank">+971524233010</a>
                @endif
            </div>

            <div class="qp-cont-divider"></div>

            {{-- Office Hours --}}
            <div class="qp-cont-item">
                <span class="qp-cont-label">Office Hours</span>
                @if(setting('office_hours'))
                    <p>{{ setting('office_hours') }}</p>
                @else
                    <p style="color:#8a93a3;">Monday – Friday, 9:00am – 6:00pm GST</p>
                @endif
            </div>

            <div class="qp-cont-divider"></div>

            {{-- Social Links --}}
           <div class="qp-cont-item">
    <span class="qp-cont-label">Follow Us</span>
    <div style="display:flex; align-items:center; gap:14px; margin-top:8px;">

        <a href="{{ setting('instagram_url', 'https://www.instagram.com/quadrantproperties/') }}"
           target="_blank" title="Instagram"
           style="color:#C8A965; font-size:20px;">
            <i class="fa-brands fa-instagram"></i>
        </a>

        @if(setting('linkedin_url'))
            <a href="{{ setting('linkedin_url') }}"
               target="_blank" title="LinkedIn"
               style="color:#C8A965; font-size:20px;">
                <i class="fa-brands fa-linkedin"></i>
            </a>
        @endif

        @if(setting('facebook_url'))
            <a href="{{ setting('facebook_url') }}"
               target="_blank" title="Facebook"
               style="color:#C8A965; font-size:20px;">
                <i class="fa-brands fa-facebook"></i>
            </a>
        @endif

        

    </div>
</div>
        </div>

        {{-- RIGHT — Enquiry Form --}}
        <div class="qp-contact-right">
            <h3>Send an Enquiry</h3>

            @if(session('success'))
                <div class="qp-success-msg">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom:18px; font-size:13px;">
                    Please fill in all required fields correctly.
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf

                {{-- Name --}}
                <div class="qp-cf-group">
                    <label>Name *</label>
                    <input type="text" name="name" required
                           placeholder="Your full name"
                           value="{{ old('name') }}">
                </div>

                {{-- Email + Phone --}}
                <div class="qp-cf-row">
                    <div class="qp-cf-group">
                        <label>Email *</label>
                        <input type="email" name="email" required
                               placeholder="your@email.com"
                               value="{{ old('email') }}">
                    </div>
                    <div class="qp-cf-group">
                        <label>Phone *</label>
                        <input type="text" name="phone" required
                               placeholder="+971 50 000 0000"
                               value="{{ old('phone') }}">
                    </div>
                </div>

                {{-- Interest Dropdown --}}
                <div class="qp-cf-group">
                    <label>I am interested in</label>
                    <select name="interest">
                        <option value="">Select...</option>
                        <option value="off-plan"   {{ old('interest') == 'off-plan'   ? 'selected' : '' }}>Off-Plan</option>
                        <option value="luxury"     {{ old('interest') == 'luxury'     ? 'selected' : '' }}>Luxury Acquisition</option>
                        <option value="investment" {{ old('interest') == 'investment' ? 'selected' : '' }}>Investment</option>
                        <option value="selling"    {{ old('interest') == 'selling'    ? 'selected' : '' }}>Selling</option>
                        <option value="general"    {{ old('interest') == 'general'    ? 'selected' : '' }}>General</option>
                    </select>
                </div>

                {{-- Message --}}
                <div class="qp-cf-group">
                    <label>Message</label>
                    <textarea name="message"
                              placeholder="Tell us what you're looking for...">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="qp-contact-submit">Send Message</button>

            </form>
        </div>

    </div>
</section>

{{-- MAP --}}
@if(setting('google_maps_embed'))
    <section>
        {!! setting('google_maps_embed') !!}
    </section>
@else
    <section style="background:#F4F5F7; padding:32px 20px; text-align:center;">
        <p style="color:#8a93a3; font-size:13px; margin:0;">
            Office 805, Concord Tower, Dubai Media City, Dubai
            — <a href="https://maps.google.com/?q=Concord+Tower+Dubai+Media+City" target="_blank" style="color:#C8A965;">View on Google Maps</a>
        </p>
    </section>
@endif

@endsection