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
                                    <h4 class="mb-4 mb-sm-0 card-title">Inquiries</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Leads & CRM</span>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Inquiries</span>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Status Count Cards --}}
            <div class="row mb-3">
                <div class="col-md-3 col-6">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body py-3">
                            <h3 class="fw-bold text-primary mb-0">{{ $counts['all'] }}</h3>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body py-3">
                            <h3 class="fw-bold text-danger mb-0">{{ $counts['new'] }}</h3>
                            <small class="text-muted">New</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body py-3">
                            <h3 class="fw-bold text-warning mb-0">{{ $counts['contacted'] }}</h3>
                            <small class="text-muted">Contacted</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body py-3">
                            <h3 class="fw-bold text-success mb-0">{{ $counts['qualified'] }}</h3>
                            <small class="text-muted">Qualified</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body py-3">
                            <form method="GET" action="{{ route('admin.inquiries.index') }}">
                                <div class="row g-2 align-items-end">

                                    {{-- Search --}}
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Search</label>
                                        <input type="text" class="form-control" name="search"
                                               placeholder="Name, Email or Phone..."
                                               value="{{ request('search') }}">
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="">All Status</option>
                                            <option value="new"       {{ request('status') == 'new'       ? 'selected' : '' }}>New</option>
                                            <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                            <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                                            <option value="closed"    {{ request('status') == 'closed'    ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>

                                    {{-- Inquiry Type --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Inquiry Type</label>
                                        <select class="form-select" name="inquiry_type">
                                            <option value="">All Types</option>
                                            <option value="property"  {{ request('inquiry_type') == 'property'  ? 'selected' : '' }}>Property</option>
                                            <option value="general"   {{ request('inquiry_type') == 'general'   ? 'selected' : '' }}>General</option>
                                            <option value="valuation" {{ request('inquiry_type') == 'valuation' ? 'selected' : '' }}>Valuation</option>
                                        </select>
                                    </div>

                                    {{-- Buttons --}}
                                    <div class="col-md-2 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <iconify-icon icon="solar:filter-line-duotone" class="me-1"></iconify-icon>
                                            Filter
                                        </button>
                                        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h4 class="card-title mb-0">
                                    All Inquiries
                                    <span class="badge bg-primary-subtle text-primary ms-2">{{ count($inquiries) }}</span>
                                </h4>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Type</th>
                                            <th>Property</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($inquiries as $inquiry)
                                        <tr class="{{ $inquiry->status === 'new' ? 'table-warning' : '' }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="fw-semibold d-block">{{ $inquiry->name }}</span>
                                                @if($inquiry->status === 'new')
                                                    <span class="badge bg-danger-subtle text-danger" style="font-size:10px;">NEW</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="d-block">
                                                    <iconify-icon icon="solar:letter-line-duotone" class="me-1"></iconify-icon>
                                                    {{ $inquiry->email }}
                                                </small>
                                                @if($inquiry->phone)
                                                    <small class="d-block text-muted">
                                                        <iconify-icon icon="solar:phone-line-duotone" class="me-1"></iconify-icon>
                                                        {{ $inquiry->phone }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $typeColors = [
                                                        'property'  => 'bg-primary-subtle text-primary',
                                                        'general'   => 'bg-secondary-subtle text-secondary',
                                                        'valuation' => 'bg-info-subtle text-info',
                                                    ];
                                                    $tColor = $typeColors[$inquiry->inquiry_type] ?? 'bg-secondary-subtle text-secondary';
                                                @endphp
                                                <span class="badge {{ $tColor }}">
                                                    {{ ucfirst($inquiry->inquiry_type ?? 'general') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($inquiry->property_title)
                                                    <small class="d-block fw-semibold">{{ Str::limit($inquiry->property_title, 25) }}</small>
                                                    <small class="text-muted">{{ $inquiry->property_ref }}</small>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($inquiry->message, 50) }}</small>
                                            </td>
                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($inquiry->created_at)->format('d M Y') }}</small>
                                                <small class="d-block text-muted">{{ \Carbon\Carbon::parse($inquiry->created_at)->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'new'       => 'bg-danger-subtle text-danger',
                                                        'contacted' => 'bg-warning-subtle text-warning',
                                                        'qualified' => 'bg-success-subtle text-success',
                                                        'closed'    => 'bg-secondary-subtle text-secondary',
                                                    ];
                                                    $sColor = $statusColors[$inquiry->status] ?? 'bg-secondary-subtle text-secondary';
                                                @endphp
                                                <span class="badge {{ $sColor }}">{{ ucfirst($inquiry->status) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{ route('admin.inquiries.show', $inquiry->id) }}"
                                                       class="btn btn-sm btn-primary" title="View">
                                                        <iconify-icon icon="solar:eye-line-duotone"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('admin.inquiries.delete', $inquiry->id) }}"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Delete this inquiry from {{ $inquiry->name }}?')">
                                                        <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5 text-muted">
                                                <iconify-icon icon="solar:inbox-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                                No inquiries found.
                                            </td>
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