<!--<a id="back2Top" class="top-scroll" title="Back to top" href="#">-->
<!--    <img width="17" height="17" loading="lazy" alt="Back to top"-->
<!--         src="{{ URL::to('') }}/public/assets/img/arrow-right.svg">-->
<!--</a>-->

<footer style="background:#11203A; padding:60px 0 0; color:#fff;">
    <div class="container">
        <div class="row">

            {{-- Col 1: Brand --}}
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                {{-- Always use light logo on dark footer background --}}
                <img src="{{ URL::to('') }}/public/logo-light-quadrant.png"
                     alt="{{ setting('site_name', 'Quadrant Properties') }}"
                     style="max-height:44px; width:auto; margin-bottom:16px; display:block;margin-left:-5px!important">

                <p style="font-family:'Cormorant Garamond',serif; font-size:15px; color:#C8A965; margin:0 0 8px; font-style:italic;">
                    Know where you stand.
                </p>
                <p style="font-size:12px; color:#8a9ab5; line-height:1.5; margin:0;">
                    Luxury &amp; off-plan real estate advisory, Dubai.
                </p>
            </div>

            {{-- Col 2: Explore --}}
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h6 style="font-size:11px; letter-spacing:1.5px; text-transform:uppercase; color:#C8A965; margin-bottom:16px; font-family:'Inter',sans-serif;">Explore</h6>
                <ul style="list-style:none; padding:0; margin:0;">
                    <li style="margin-bottom:10px;"><a href="{{ route('developments.index') }}" style="color:#8a9ab5; font-size:13px; text-decoration:none;">Buy</a></li>
                    <li style="margin-bottom:10px;"><a href="{{ route('sell') }}" style="color:#8a9ab5; font-size:13px; text-decoration:none;">Sell</a></li>
                    <li style="margin-bottom:10px;"><a href="{{ route('communities.index') }}" style="color:#8a9ab5; font-size:13px; text-decoration:none;">Communities</a></li>
                    <li style="margin-bottom:10px;"><a href="{{ route('services') }}" style="color:#8a9ab5; font-size:13px; text-decoration:none;">Services</a></li>
                    <li style="margin-bottom:10px;"><a href="{{ route('about') }}" style="color:#8a9ab5; font-size:13px; text-decoration:none;">About</a></li>
                    <!-- <li style="margin-bottom:10px;"><a href="{{ route('giving') }}" style="color:#8a9ab5; font-size:13px; text-decoration:none;">Giving</a></li> -->
                    <li style="margin-bottom:10px;"><a href="{{ route('blogs.index') }}" style="color:#8a9ab5; font-size:13px; text-decoration:none;">Blogs</a></li>
                    <li style="margin-bottom:10px;"><a href="{{ route('contact') }}" style="color:#8a9ab5; font-size:13px; text-decoration:none;">Contact</a></li>
                </ul>
            </div>

            {{-- Col 3: Communities --}}
            <div class="col-lg-3 col-md-3 col-6 mb-4">
                <h6 style="font-size:11px; letter-spacing:1.5px; text-transform:uppercase; color:#C8A965; margin-bottom:16px; font-family:'Inter',sans-serif;">Communities</h6>
                <ul style="list-style:none; padding:0; margin:0;">
                    @php
                        $footerCommunities = [
                            'downtown-dubai'      => 'Downtown Dubai',
                            'dubai-marina'        => 'Dubai Marina',
                            'palm-jebel-ali'      => 'Palm Jebel Ali',
                            'dubai-creek-harbour' => 'Dubai Creek Harbour',
                            'dubai-hills-estate'  => 'Dubai Hills Estate',
                            'jvc'                 => 'JVC',
                            'dubai-south'         => 'Dubai South',
                        ];
                    @endphp
                    @foreach($footerCommunities as $slug => $name)
                        <li style="margin-bottom:10px;">
                            <a href="{{ route('communities.show', $slug) }}"
                               style="color:#8a9ab5; font-size:13px; text-decoration:none;">
                                {{ $name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Col 4: Contact --}}
            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <h6 style="font-size:11px; letter-spacing:1.5px; text-transform:uppercase; color:#C8A965; margin-bottom:16px; font-family:'Inter',sans-serif;">Contact</h6>
                <ul style="list-style:none; padding:0; margin:0;">

                    {{-- Office Address --}}
                    @if(setting('office_address'))
                        <li style="margin-bottom:10px; color:#8a9ab5; font-size:13px; line-height:1.5;">
                            {{ setting('office_address') }}
                        </li>
                    @endif

                    {{-- Phone --}}
                    @if(setting('contact_phone'))
                        <li style="margin-bottom:10px;">
                            <a href="tel:{{ setting('contact_phone') }}"
                               style="color:#8a9ab5; font-size:13px; text-decoration:none;">
                                <i class="fa-solid fa-phone" style="font-size:16px; color:#C8A965; width:16px;"></i>
                                {{ setting('contact_phone') }}
                            </a>
                        </li>
                    @endif

                    {{-- Email --}}
                    @if(setting('contact_email'))
                        <li style="margin-bottom:10px;">
                            <a href="mailto:{{ setting('contact_email') }}"
                               style="color:#8a9ab5; font-size:13px; text-decoration:none;">
                                <i class="fa-solid fa-envelope" style="font-size:16px; color:#C8A965; width:16px;"></i>
                                {{ setting('contact_email') }}
                            </a>
                        </li>
                    @endif

                   {{-- Social Icons Row --}}
<li style="margin-top:8px;">
    <div style="display:flex; align-items:center; gap:14px;">

        @if(setting('whatsapp_number'))
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('whatsapp_number')) }}"
               target="_blank" title="WhatsApp"
               style="color:#C8A965; font-size:20px; transition:color .2s ease;"
               onmouseover="this.style.color='#fff';"
               onmouseout="this.style.color='#C8A965';">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
        @endif

        <a href="{{ setting('instagram_url', 'https://www.instagram.com/quadrantproperties/') }}"
           target="_blank" title="Instagram"
           style="color:#C8A965; font-size:20px; transition:color .2s ease;"
           onmouseover="this.style.color='#fff';"
           onmouseout="this.style.color='#C8A965';">
            <i class="fa-brands fa-instagram"></i>
        </a>

        @if(setting('linkedin_url'))
            <a href="{{ setting('linkedin_url') }}"
               target="_blank" title="LinkedIn"
               style="color:#C8A965; font-size:20px; transition:color .2s ease;"
               onmouseover="this.style.color='#fff';"
               onmouseout="this.style.color='#C8A965';">
                <i class="fa-brands fa-linkedin"></i>
            </a>
        @endif

        @if(setting('facebook_url'))
            <a href="{{ setting('facebook_url') }}"
               target="_blank" title="Facebook"
               style="color:#C8A965; font-size:20px; transition:color .2s ease;"
               onmouseover="this.style.color='#fff';"
               onmouseout="this.style.color='#C8A965';">
                <i class="fa-brands fa-facebook"></i>
            </a>
        @endif

    </div>
</li>

                </ul>
            </div>

        </div>
    </div>

    {{-- Brass Rule + Legal Line --}}
    <div style="border-top: 1px solid #C8A965; margin-top:30px; padding:20px 0;">
        <div class="container">
            <p style="font-size:11px; color:#5B6678; margin:0; text-align:center;">
                © {{ date('Y') }} Quadrant Properties. All rights reserved.
                | ORN: {{ setting('rera_number', '[Office Registration Number]') }}
                | RERA Permit displayed per listing.
                |
                <a href="{{ route('privacy') }}" style="font-size:11px;color:#5B6678;">Privacy Policy</a>
                &nbsp;|&nbsp;
                <a href="{{ route('terms') }}"  style="font-size:11px;color:#5B6678;">Terms of Use</a>
            </p>
        </div>
    </div>
</footer>
<style>
.floating_btn {
  position: fixed;
  bottom: 30px;
  right: 36px;
  width: 100px;
  height: 100px;
  display: flex;
  flex-direction: column;
  align-items:center;
  justify-content:center;
  z-index: 1000;
}

@keyframes pulsing {
to {
    box-shadow: 0 0 0 30px rgba(232, 76, 61, 0);
}
}

.contact_icon {
  background-color: #42db87;
  color: #fff;
  width: 53px;
  height: 53px;
  font-size:30px;
  border-radius: 50px;
  text-align: center;
  box-shadow: 2px 2px 3px #999;
  display: flex;
  align-items: center;
  justify-content: center;
  transform: translatey(0px);
  animation: pulse 1.5s infinite;
  box-shadow: 0 0 0 0 #42db87;
  -webkit-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
  -moz-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
  -ms-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
  animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
  font-weight: normal;
  font-family: sans-serif;
  text-decoration: none !important;
  transition: all 300ms ease-in-out;
}

@media (max-width: 575px) {
.floating_btn {
    right: -10px;
    bottom:5px;
}
}
</style>

<div class="floating_btn">
    <a target="_blank"
       href="https://api.whatsapp.com/send/?phone={{ preg_replace('/[^0-9]/', '', setting('whatsapp_number', '971524233010')) }}&text&type=phone_number"
       style="text-decoration:none">
        <div class="contact_icon">
            <i class="fab fa-whatsapp my-float" style="font-size:30px;"></i>
        </div>
    </a>
</div>


<div class="overlay-body"></div>

{{-- Scripts --}}
<script src="{{ URL::to('') }}/public/assets/js/jquery.min.js"></script>
<script src="{{ URL::to('') }}/public/assets/cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="{{ URL::to('') }}/public/assets/js/bootstrap6654.js?v1"></script>
<script src="{{ URL::to('') }}/public/assets/js/lightgallery6654.js?v1" defer></script>
<script src="{{ URL::to('') }}/public/assets/js/owl.carouselc4ca.js?1"></script>
<script src="{{ URL::to('') }}/public/assets/js/main43a0.js?v3"></script>

@yield('scripts')