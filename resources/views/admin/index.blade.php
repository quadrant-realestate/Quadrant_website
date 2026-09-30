@include('admin.include.header')
    <div class="body-wrapper-inner">
        <div class="container-fluid">

            {{-- Page Header --}}
            <div class="row">
                <div class="col-lg-12 d-flex align-items-strech">
                    <div class="card card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="d-sm-flex align-items-center justify-space-between">
                                    <div>
                                        <h4 class="mb-1 card-title">Welcome back, {{ session('name') }}</h4>
                                        <p class="text-muted mb-0 fs-2">
                                            <iconify-icon icon="solar:calendar-line-duotone" class="me-1"></iconify-icon>
                                            {{ now()->format('l, d F Y') }}
                                        </p>
                                    </div>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Dashboard</span>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- ROW 1: Main Stat Cards --}}
            {{-- ============================================ --}}
            <div class="row mt-2">

                {{-- Total Properties --}}
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded p-2" style="background:#e8f0fa;">
                                    <iconify-icon icon="solar:buildings-line-duotone" class="fs-6" style="color:#214A8B;"></iconify-icon>
                                </div>
                                <a href="{{ route('admin.properties.index') }}" class="text-muted fs-2">View All</a>
                            </div>
                            <h3 class="fw-bold mb-1">{{ $totalProperties }}</h3>
                            <p class="text-muted mb-2 fs-2">Total Properties</p>
                            <div class="d-flex gap-3">
                                <small class="badge bg-success-subtle text-success">{{ $activeProperties }} Active</small>
                                <small class="badge bg-danger-subtle text-danger">{{ $soldProperties }} Sold</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- New Inquiries --}}
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded p-2" style="background:#fde8e8;">
                                    <iconify-icon icon="solar:inbox-line-line-duotone" class="fs-6" style="color:#e53e3e;"></iconify-icon>
                                </div>
                                <a href="{{ route('admin.inquiries.index') }}" class="text-muted fs-2">View All</a>
                            </div>
                            <h3 class="fw-bold mb-1">{{ $newInquiries }}</h3>
                            <p class="text-muted mb-2 fs-2">New Inquiries</p>
                            <div class="d-flex gap-3">
                                <small class="badge bg-warning-subtle text-warning">{{ $contactedInquiries }} Contacted</small>
                                <small class="badge bg-success-subtle text-success">{{ $qualifiedInquiries }} Qualified</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Developments --}}
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded p-2" style="background:#e8faf0;">
                                    <iconify-icon icon="solar:city-line-duotone" class="fs-6" style="color:#38a169;"></iconify-icon>
                                </div>
                                <a href="{{ route('admin.developments.index') }}" class="text-muted fs-2">View All</a>
                            </div>
                            <h3 class="fw-bold mb-1">{{ $totalDevelopments }}</h3>
                            <p class="text-muted mb-2 fs-2">Developments</p>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($devByStatus as $dev)
                                    <small class="badge bg-primary-subtle text-primary">
                                        {{ ucfirst(str_replace('_', ' ', $dev->status)) }}: {{ $dev->total }}
                                    </small>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Communities --}}
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded p-2" style="background:#faf3e8;">
                                    <iconify-icon icon="solar:map-point-wave-line-duotone" class="fs-6" style="color:#D4AF37;"></iconify-icon>
                                </div>
                                <a href="{{ route('admin.communities.index') }}" class="text-muted fs-2">View All</a>
                            </div>
                            <h3 class="fw-bold mb-1">{{ $totalCommunities }}</h3>
                            <p class="text-muted mb-2 fs-2">Communities</p>
                            <div class="d-flex gap-3">
                                <small class="badge bg-info-subtle text-info">{{ $totalInvestments }} Investments</small>
                                <small class="badge bg-warning-subtle text-warning">{{ $totalBrandedRes }} Branded</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ============================================ --}}
            {{-- ROW 2: Listing Breakdown + Inquiry Pipeline --}}
            {{-- ============================================ --}}
            <div class="row">

                {{-- Listing Type Breakdown --}}
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4 pb-2 border-bottom">
                                <iconify-icon icon="solar:pie-chart-line-duotone" class="me-2"></iconify-icon>
                                Listings by Type
                            </h5>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold">For Sale</span>
                                    <span class="badge bg-success-subtle text-success">{{ $forSale }}</span>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-success"
                                         style="width: {{ $totalProperties > 0 ? ($forSale / $totalProperties) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold">For Rent</span>
                                    <span class="badge bg-info-subtle text-info">{{ $forRent }}</span>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-info"
                                         style="width: {{ $totalProperties > 0 ? ($forRent / $totalProperties) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold">Off Plan</span>
                                    <span class="badge bg-warning-subtle text-warning">{{ $offPlan }}</span>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-warning"
                                         style="width: {{ $totalProperties > 0 ? ($offPlan / $totalProperties) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row text-center mt-3">
                                <div class="col-4">
                                    <h5 class="fw-bold text-primary mb-0">{{ $featuredProperties }}</h5>
                                    <small class="text-muted">Featured</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="fw-bold text-warning mb-0">{{ $rentedProperties }}</h5>
                                    <small class="text-muted">Rented</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="fw-bold text-danger mb-0">{{ $soldProperties }}</h5>
                                    <small class="text-muted">Sold</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Inquiry Pipeline --}}
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4 pb-2 border-bottom">
                                <iconify-icon icon="solar:chart-line-duotone" class="me-2"></iconify-icon>
                                Inquiry Pipeline
                            </h5>

                            <div class="d-flex flex-column gap-3">

                                <div class="d-flex align-items-center justify-content-between p-3 rounded"
                                     style="background:#fde8e8;">
                                    <div class="d-flex align-items-center gap-2">
                                        <iconify-icon icon="solar:letter-unopened-line-duotone" class="fs-5 text-danger"></iconify-icon>
                                        <span class="fw-semibold">New</span>
                                    </div>
                                    <h4 class="fw-bold text-danger mb-0">{{ $newInquiries }}</h4>
                                </div>

                                <div class="d-flex align-items-center justify-content-between p-3 rounded"
                                     style="background:#fef9e8;">
                                    <div class="d-flex align-items-center gap-2">
                                        <iconify-icon icon="solar:phone-calling-line-duotone" class="fs-5 text-warning"></iconify-icon>
                                        <span class="fw-semibold">Contacted</span>
                                    </div>
                                    <h4 class="fw-bold text-warning mb-0">{{ $contactedInquiries }}</h4>
                                </div>

                                <div class="d-flex align-items-center justify-content-between p-3 rounded"
                                     style="background:#e8faf0;">
                                    <div class="d-flex align-items-center gap-2">
                                        <iconify-icon icon="solar:verified-check-line-duotone" class="fs-5 text-success"></iconify-icon>
                                        <span class="fw-semibold">Qualified</span>
                                    </div>
                                    <h4 class="fw-bold text-success mb-0">{{ $qualifiedInquiries }}</h4>
                                </div>

                                <div class="d-flex align-items-center justify-content-between p-3 rounded"
                                     style="background:#f0f0f0;">
                                    <div class="d-flex align-items-center gap-2">
                                        <iconify-icon icon="solar:archive-line-duotone" class="fs-5 text-secondary"></iconify-icon>
                                        <span class="fw-semibold">Total</span>
                                    </div>
                                    <h4 class="fw-bold text-secondary mb-0">{{ $totalInquiries }}</h4>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4 pb-2 border-bottom">
                                <iconify-icon icon="solar:bolt-line-duotone" class="me-2"></iconify-icon>
                                Quick Actions
                            </h5>

                            <div class="d-grid gap-2">

                                <a href="{{ route('admin.properties.create') }}"
                                   class="btn btn-primary hstack gap-3">
                                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-5"></iconify-icon>
                                    Add New Property
                                </a>

                                <a href="{{ route('admin.developments.create') }}"
                                   class="btn btn-outline-primary hstack gap-3">
                                    <iconify-icon icon="solar:city-line-duotone" class="fs-5"></iconify-icon>
                                    Add Development
                                </a>

                                <a href="{{ route('admin.communities.create') }}"
                                   class="btn btn-outline-secondary hstack gap-3">
                                    <iconify-icon icon="solar:map-point-wave-line-duotone" class="fs-5"></iconify-icon>
                                    Add Community
                                </a>

                                <a href="{{ route('admin.inquiries.index') }}"
                                   class="btn btn-outline-danger hstack gap-3 justify-content-between">
                                    <div class="hstack gap-3">
                                        <iconify-icon icon="solar:inbox-line-line-duotone" class="fs-5"></iconify-icon>
                                        View All Inquiries
                                    </div>
                                    @if($newInquiries > 0)
                                        <span class="badge bg-danger rounded-pill">{{ $newInquiries }} New</span>
                                    @endif
                                </a>

                                <a href="{{ route('admin.investments.create') }}"
                                   class="btn btn-outline-success hstack gap-3">
                                    <iconify-icon icon="solar:chart-line-duotone" class="fs-5"></iconify-icon>
                                    Add Investment
                                </a>

                                <a href="{{ route('admin.settings.index') }}"
                                   class="btn btn-outline-secondary hstack gap-3">
                                    <iconify-icon icon="solar:settings-line-duotone" class="fs-5"></iconify-icon>
                                    Site Settings
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ============================================ --}}
            {{-- ROW 3: Recent Properties --}}
            {{-- ============================================ --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="card-title mb-0">
                                    <iconify-icon icon="solar:buildings-line-duotone" class="me-2"></iconify-icon>
                                    Recent Properties
                                </h5>
                                <a href="{{ route('admin.properties.index') }}" class="btn btn-sm btn-outline-primary">
                                    View All
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Property</th>
                                            <th>Reference</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Beds</th>
                                            <th>Community</th>
                                            <th>Status</th>
                                            <th>Added</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentProperties as $property)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($property->main_image)
                                                        <img src="{{ asset('public/'.$property->main_image) }}"
                                                             class="rounded"
                                                             width="45" height="40"
                                                             style="object-fit:cover;">
                                                    @else
                                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                             style="width:45px;height:40px;">
                                                            <iconify-icon icon="solar:buildings-line-duotone" class="text-muted"></iconify-icon>
                                                        </div>
                                                    @endif
                                                    <span class="fw-semibold">{{ Str::limit($property->title, 28) }}</span>
                                                </div>
                                            </td>
                                            <td><small class="text-muted">{{ $property->reference_no }}</small></td>
                                            <td>
                                                @php
                                                    $lColors = [
                                                        'sale'          => 'bg-success-subtle text-success',
                                                        'rent'          => 'bg-info-subtle text-info',
                                                        'off_plan'      => 'bg-warning-subtle text-warning',
                                                        'international' => 'bg-primary-subtle text-primary',
                                                        'private'       => 'bg-danger-subtle text-danger',
                                                    ];
                                                    $lLabels = [
                                                        'sale'          => 'Sale',
                                                        'rent'          => 'Rent',
                                                        'off_plan'      => 'Off Plan',
                                                        'international' => 'Intl',
                                                        'private'       => 'Private',
                                                    ];
                                                @endphp
                                                <span class="badge {{ $lColors[$property->listing_type] ?? 'bg-secondary-subtle text-secondary' }}">
                                                    {{ $lLabels[$property->listing_type] ?? $property->listing_type }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($property->price_on_request)
                                                    <small class="text-muted">On Request</small>
                                                @else
                                                    <small class="fw-semibold">
                                                        {{ $property->price_currency }}
                                                        {{ number_format($property->price) }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($property->bedrooms !== null)
                                                    {{ $property->bedrooms }} BR
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td><small>{{ $property->community_name ?? '—' }}</small></td>
                                            <td>
                                                @php
                                                    $sColors = [
                                                        'active'   => 'bg-success-subtle text-success',
                                                        'sold'     => 'bg-danger-subtle text-danger',
                                                        'rented'   => 'bg-info-subtle text-info',
                                                        'reserved' => 'bg-warning-subtle text-warning',
                                                        'inactive' => 'bg-secondary-subtle text-secondary',
                                                    ];
                                                @endphp
                                                <span class="badge {{ $sColors[$property->status] ?? 'bg-secondary-subtle text-secondary' }}">
                                                    {{ ucfirst($property->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($property->created_at)->format('d M Y') }}
                                                </small>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">No properties yet.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- ROW 4: Recent Inquiries --}}
            {{-- ============================================ --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="card-title mb-0">
                                    <iconify-icon icon="solar:inbox-line-line-duotone" class="me-2"></iconify-icon>
                                    Recent Inquiries
                                    @if($newInquiries > 0)
                                        <span class="badge bg-danger ms-2">{{ $newInquiries }} New</span>
                                    @endif
                                </h5>
                                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-sm btn-outline-primary">
                                    View All
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Type</th>
                                            <th>Property</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentInquiries as $inquiry)
                                        <tr class="{{ $inquiry->status === 'new' ? 'table-warning' : '' }}">
                                            <td>
                                                <span class="fw-semibold d-block">{{ $inquiry->name }}</span>
                                                @if($inquiry->status === 'new')
                                                    <span class="badge bg-danger-subtle text-danger" style="font-size:10px;">NEW</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="d-block">{{ $inquiry->email }}</small>
                                                @if($inquiry->phone)
                                                    <small class="text-muted">{{ $inquiry->phone }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $typeColors = [
                                                        'general'   => 'bg-secondary-subtle text-secondary',
                                                        'buy'       => 'bg-success-subtle text-success',
                                                        'rent'      => 'bg-info-subtle text-info',
                                                        'sell'      => 'bg-warning-subtle text-warning',
                                                        'valuation' => 'bg-primary-subtle text-primary',
                                                        'callback'  => 'bg-danger-subtle text-danger',
                                                    ];
                                                    $tColor = $typeColors[$inquiry->inquiry_type] ?? 'bg-secondary-subtle text-secondary';
                                                @endphp
                                                <span class="badge {{ $tColor }}">
                                                    {{ ucfirst($inquiry->inquiry_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($inquiry->property_title)
                                                    <small>{{ Str::limit($inquiry->property_title, 20) }}</small>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($inquiry->message, 40) }}</small>
                                            </td>
                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($inquiry->created_at)->format('d M') }}</small>
                                                <small class="d-block text-muted">{{ \Carbon\Carbon::parse($inquiry->created_at)->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'new'       => 'bg-danger-subtle text-danger',
                                                        'contacted' => 'bg-warning-subtle text-warning',
                                                        'qualified' => 'bg-success-subtle text-success',
                                                        'closed'    => 'bg-secondary-subtle text-secondary',
                                                        'lost'      => 'bg-dark-subtle text-dark',
                                                    ];
                                                    $sColor = $statusColors[$inquiry->status] ?? 'bg-secondary-subtle text-secondary';
                                                @endphp
                                                <span class="badge {{ $sColor }}">{{ ucfirst($inquiry->status) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.inquiries.show', $inquiry->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <iconify-icon icon="solar:eye-line-duotone"></iconify-icon>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">No inquiries yet.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@include('admin.include.footer')