<style>
    /* ===========================================================
       QUADRANT HEADER — fixed, transparent-to-navy on scroll
    =========================================================== */
    /* body { margin-top: 0 !important; padding-top: 0 !important; } */

    #qp-header {
        /* background: rgba(11,32,58,.55); */
    }
    #qp-header.qp-scrolled {
        background: #11203A;
        box-shadow: 0 2px 20px rgba(0,0,0,.18);
    }

    /* #qp-header .navbar { padding: 14px 0 !important; } */

    #qp-header .logo-box img {
        max-height: 40px;
        /* width: auto !important; */
        height: auto;
        transition: max-height .3s ease;
    }
    #qp-header.qp-scrolled .logo-box img {
        /* display:none!important */
    }

    #qp-header .navbar-nav .nav-link {
    /* color: #ffffff !important; */
    font-size: 13px;
    letter-spacing: .3px;
    font-weight: 500;
    padding: 8px 14px !important;
    /* text-shadow: 0 1px 4px rgba(0,0,0,.5); */
    transition: color .2s ease;
}
#qp-header .navbar-nav .nav-link:hover,
#qp-header .navbar-nav .nav-link.active {
    color: #C8A965 !important;
    text-shadow: none;
}
/* Force white on scroll state too */
#qp-header.qp-scrolled .navbar-nav .nav-link {
    color: #ffffff !important;
    text-shadow: none;
}
#qp-header.qp-scrolled .navbar-nav .nav-link:hover,
#qp-header.qp-scrolled .navbar-nav .nav-link.active {
    color: #C8A965 !important;
}

    #qp-header .EndSide a[href*="contact"] {
        text-shadow: none;
    }

    #qp-header .navbar { padding: 4px 0 !important; }
</style>

<header class="header js-header" id="qp-header" style="position:fixed; top:0; left:0; right:0; z-index:1000; transition:background .3s ease, box-shadow .3s ease;">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">

            {{-- Logo --}}
            <div class="logo-web">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <div class="logo-box">
                        {{-- Light logo: white/gold version — shown on dark header (default) --}}
                        <img src="{{ URL::to('') }}/public/logo-dark-quadrant.png"
                            width="160" height="auto" loading="lazy"
                            alt="{{ setting('site_name', 'Quadrant Properties') }}"
                            id="qp-logo-dark"
                            style="display:block;">

                        {{-- Dark logo: navy version — shown when header is on light background --}}
                        <img src="{{ URL::to('') }}/public/logo-light-quadrant.png"
                            width="160" height="auto" loading="lazy"
                            alt="{{ setting('site_name', 'Quadrant Properties') }}"
                            
                            id="qp-logo-light"
                            style="display:none;">
                    </div>
                </a>
            </div>

            {{-- Desktop Nav --}}
            <div class="right-head">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('offplan.*','developments.*') ? 'active' : '' }}"
                               href="{{ route('developments.index') }}">Buy</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sell') ? 'active' : '' }}"
                            href="{{ route('sell') }}">Sell</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('communities.*') ? 'active' : '' }}"
                               href="{{ route('communities.index') }}">Communities</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}"
                               href="{{ route('services') }}">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                               href="{{ route('about') }}">About</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('giving') ? 'active' : '' }}"
                               href="{{ route('giving') }}">Giving</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}"
                               href="{{ route('blogs.index') }}">Blogs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                               href="{{ route('contact') }}">Contact</a>
                        </li>
                    </ul>
                </div>

                <div class="EndSide">
                    {{-- Register Interest CTA --}}
                    <div class="schedule-call mobile-none">
                        <a class="btn" href="{{ route('contact') }}"
                           style="border:1px solid #C8A965; color:#C8A965; background:transparent; padding:10px 20px; font-size:12px; letter-spacing:.5px; text-transform:uppercase; font-weight:600; border-radius:2px; transition:all .2s ease;"
                           onmouseover="this.style.background='#C8A965'; this.style.color='#11203A';"
                           onmouseout="this.style.background='transparent'; this.style.color='#C8A965';">
                            Register Interest
                        </a>
                    </div>

                    {{-- Mobile toggle --}}
                    <div class="mobile-icon">
                        <button class="navbar-toggler nav-btn nav-slider"
                                aria-label="Menu" type="button">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </div>
            </div>

        </nav>
    </div>
</header>

{{-- Transparent-to-navy scroll behaviour --}}
<script>
(function() {
    var header    = document.getElementById('qp-header');
    var logoLight = document.getElementById('qp-logo-light');
    var logoDark  = document.getElementById('qp-logo-dark');

    if (!header) return;

    function setHeaderState(scrolled) {
        if (scrolled) {
            header.classList.add('qp-scrolled');

            // On dark navy background show light/white logo
            if (logoLight) logoLight.style.display = 'block';
            if (logoDark)  logoDark.style.display  = 'none';

        } else {
            header.classList.remove('qp-scrolled');

            // On top/transparent background show dark/navy logo
            if (logoLight) logoLight.style.display = 'none';
            if (logoDark)  logoDark.style.display  = 'block';
        }
    }

    window.addEventListener('scroll', function() {
        setHeaderState(window.scrollY > 60);
    });

    setHeaderState(window.scrollY > 60);
})();
</script>

{{-- Mobile Sidebar --}}
<nav class="sidebar" id="accordion-menu">
    <div class="authfy-body">
        <div class="menu-logo-box" style="background-color:transparent;margin-bottom:0px!important">
            {{-- Sidebar always has dark background — use light logo --}}
    <img loading="lazy"
         src="{{ URL::to('') }}/public/logo-dark-quadrant.png"
         alt="{{ setting('site_name', 'Quadrant Properties') }}">
        </div>
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="{{ route('developments.index') }}">Buy</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('sell') }}">Sell</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('communities.index') }}">Communities</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('services') }}">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
            <!-- <li class="nav-item"><a class="nav-link" href="{{ route('giving') }}">Giving</a></li> -->
            <li class="nav-item"><a class="nav-link" href="{{ route('blogs.index') }}">Blogs</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
        </ul>
        <div class="menu-btn-grup">
            <a class="btn green-btn" href="{{ route('contact') }}">Register Interest</a>
        </div>
    </div>
</nav>