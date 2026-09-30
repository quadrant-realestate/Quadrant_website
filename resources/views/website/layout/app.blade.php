<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7Q2LFB01BK"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-7Q2LFB01BK');
</script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', setting('site_name', 'Quadrant Properties Dubai'))</title>

    <link rel="icon" type="image/x-icon" href="{{ URL::to('') }}/public/favicon.png">

    {{-- SEO --}}
    <meta name="description" content="@yield('meta_description', 'Quadrant Properties Dubai — Luxury Real Estate in Dubai')">
    <meta name="csrf-token"  content="{{ csrf_token() }}">

    {{-- Open Graph --}}
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ request()->url() }}">
    <meta property="og:title"       content="@yield('title', setting('site_name'))">
    <meta property="og:description" content="@yield('meta_description', '')">
    <meta property="og:image"       content="@yield('og_image', URL::to('') . '/public/' . setting('site_logo'))">
    <meta property="og:site_name"   content="{{ setting('site_name', 'Quadrant Properties') }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('title', setting('site_name'))">
    <meta name="twitter:description" content="@yield('meta_description', '')">
    <meta name="twitter:image"       content="@yield('og_image', URL::to('') . '/public/' . setting('site_logo'))">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ request()->url() }}">

    {{-- CSS --}}
    <link href="{{ URL::to('') }}/public/assets/css/bootstrap.css"              rel="stylesheet">
    <link href="{{ URL::to('') }}/public/assets/css/all.min.css"                rel="stylesheet">
    <link href="{{ URL::to('') }}/public/assets/css/owl.carousel.min.css"       rel="stylesheet">
    <link href="{{ URL::to('') }}/public/assets/css/owl.theme.default.css"      rel="stylesheet">
    <link href="{{ URL::to('') }}/public/assets/css/lightgallery.css"           rel="stylesheet">
    <link href="{{ URL::to('') }}/public/assets/css/common1931.css?v=1"         rel="stylesheet">
    <link href="{{ URL::to('') }}/public/assets/css/responsivec8ac.css?ver2.1"  rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @yield('head')

    <style>
        body {
            font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
            color: #1B2538;
            /* padding-top: 86px; */
        }
        body.qp-home-page {
            padding-top: 0 !important;
        }
        h1, h2, h3, h4, h5 {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }
        #preloader { position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(255,255,255,.8); display:flex; justify-content:center; align-items:center; z-index:9999; }
        .spinner { border:4px solid #f3f3f3; border-top:4px solid #3a3526; border-radius:50%; width:50px; height:50px; animation:1s linear infinite spin; }
        @keyframes spin { 0%{transform:rotate(0)} 100%{transform:rotate(360deg)} }
        body div#preloader { display: none !important; }
    </style>

    {{-- Organisation Schema — on every page --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "RealEstateAgent",
        "name": "{{ setting('site_name', 'Quadrant Properties') }}",
        "url": "{{ URL::to('') }}",
        "logo": "{{ URL::to('') }}/public/{{ setting('site_logo') }}",
        "telephone": "{{ setting('contact_phone') }}",
        "email": "{{ setting('contact_email') }}",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ setting('office_address') }}",
            "addressLocality": "Dubai",
            "addressCountry": "AE"
        },
        "sameAs": [
            "{{ setting('instagram_url') }}",
            "{{ setting('facebook_url') }}",
            "{{ setting('linkedin_url') }}"
        ]
    }
    </script>

    {{-- Per-page schema slot (used by developments/show.blade.php etc.) --}}
    @yield('schema')

</head>
<body class="@yield('body-class')">

<div id="preloader"><div class="spinner"></div></div>

@include('website.include.header')

@yield('content')

@include('website.include.footer')

</body>
</html>