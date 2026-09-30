<aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="{{ route('admin.dashboard') }}" class="text-nowrap logo-img">
            <img src="{{ asset('public/logo-quadrant.png') }}" alt="" style="width: 200px;height:auto"/>
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            
                    <!-- ==================== DASHBOARD ==================== -->
    <li class="nav-small-cap">
      <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
      <span class="hide-menu">Main</span>
    </li>

    <li class="sidebar-item">
      <a class="sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false">
        <iconify-icon icon="solar:widget-5-line-duotone"></iconify-icon>
        <span class="hide-menu">Dashboard</span>
      </a>
    </li>

    <!-- ==================== PROPERTIES ==================== -->
    <li class="nav-small-cap">
      <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
      <span class="hide-menu">Properties</span>
    </li>

    <!-- Properties -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:buildings-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Properties</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ route('admin.properties.create') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Add Property</span>
            </div>
            <span class="hide-menu badge bg-success-subtle text-success fs-1 py-1">New</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.properties.index') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">All Properties</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.properties.index', ['listing_type' => 'sale']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">For Sale</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.properties.index', ['listing_type' => 'rent']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">For Rent</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.properties.index', ['listing_type' => 'private']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Private Listings</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.properties.index', ['listing_type' => 'international']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">International</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Property Types -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:tag-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Property Types</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.property-types.create') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Add Type</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.property-types.index') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">All Types</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Amenities -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:star-shine-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Amenities</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.amenities.create') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Add Amenity</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.amenities.index') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">All Amenities</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- ==================== PROJECTS ==================== -->
    <li class="nav-small-cap">
      <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
      <span class="hide-menu">Projects</span>
    </li>

    <!-- New Developments -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:city-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Developments</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ route('admin.developments.create') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Add Development</span>
            </div>
            <span class="hide-menu badge bg-success-subtle text-success fs-1 py-1">New</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.developments.index') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">All Developments</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Investments -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:chart-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Investments</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.investments.create') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Add Investment</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.investments.index') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">All Investments</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Branded Residences -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:crown-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Branded Residences</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.branded-residences.create') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Add Residence</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.branded-residences.index') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">All Residences</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- ==================== LOCATIONS ==================== -->
    <li class="nav-small-cap">
      <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
      <span class="hide-menu">Locations</span>
    </li>

    <!-- Communities -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:map-point-wave-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Communities</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ route('admin.communities.create') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Add Community</span>
            </div>
            <span class="hide-menu badge bg-success-subtle text-success fs-1 py-1">New</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.communities.index') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">All Communities</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- ==================== LEADS ==================== -->
    <li class="nav-small-cap">
      <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
      <span class="hide-menu">Leads & CRM</span>
    </li>

    <!-- Inquiries -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:inbox-line-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Inquiries</span>
        </div>
        @php $newInquiries = DB::table('inquiries')->where('status','new')->count(); @endphp
        @if($newInquiries > 0)
          <span class="badge bg-danger rounded-pill ms-auto">{{ $newInquiries }}</span>
        @endif
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.inquiries.index') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">All Inquiries</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.inquiries.index', ['status' => 'new']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">New</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.inquiries.index', ['status' => 'contacted']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Contacted</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.inquiries.index', ['status' => 'qualified']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Qualified</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.inquiries.index', ['status' => 'closed']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Closed</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- ==================== MEDIA ==================== -->
    <li class="nav-small-cap">
      <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
      <span class="hide-menu">Media & Content</span>
    </li>

    <!-- Recognitions -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:medal-ribbons-star-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Recognitions</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.recognitions.create') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Add Recognition</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.recognitions.index') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">All Recognitions</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- ==================== INSIGHTS ==================== -->
    <li class="nav-small-cap">
      <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
      <span class="hide-menu">Insights</span>
    </li>

    <!-- Articles -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:document-text-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Articles</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ route('admin.blog_posts.create') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Add Article</span>
            </div>
            <span class="hide-menu badge bg-success-subtle text-success fs-1 py-1">New</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.blog_posts.index') }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">All Articles</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- ==================== SETTINGS ==================== -->
    <li class="nav-small-cap">
      <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
      <span class="hide-menu">Configuration</span>
    </li>

    <!-- Settings -->
    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex">
            <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
          </span>
          <span class="hide-menu">Site Settings</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.settings.index', ['group' => 'general']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">General</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.settings.index', ['group' => 'contact']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Contact Info</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.settings.index', ['group' => 'social']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Social Links</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('admin.settings.index', ['group' => 'homepage']) }}">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex"><span class="icon-small"></span></span>
              <span class="hide-menu">Homepage</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Admin Profile -->
    <li class="sidebar-item">
      <a class="sidebar-link" href="{{ route('admin.profile') }}" aria-expanded="false">
        <iconify-icon icon="solar:user-circle-line-duotone"></iconify-icon>
        <span class="hide-menu">My Profile</span>
      </a>
    </li>
            <li>
              <center>
                <a href="{{ route('signout') }}"
                   class="btn btn-primary">Logout</a>
              </center>
            </li>

  
          </ul>
         
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>