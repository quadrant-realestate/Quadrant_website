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
                                    <h4 class="mb-4 mb-sm-0 card-title">Inquiry Details</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Inquiries</span>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">View</span>
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

            <div class="row">

                {{-- LEFT: Inquiry Details --}}
                <div class="col-lg-8">

                    {{-- Contact Info --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4 pb-2 border-bottom">
                                <iconify-icon icon="solar:user-circle-line-duotone" class="me-2"></iconify-icon>
                                Contact Information
                            </h5>
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="text-muted small fw-semibold">Full Name</label>
                                    <p class="fw-semibold mb-0 fs-5">{{ $inquiry->name }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="text-muted small fw-semibold">Email Address</label>
                                    <p class="mb-0">
                                        <a href="mailto:{{ $inquiry->email }}" class="text-primary">
                                            {{ $inquiry->email }}
                                        </a>
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="text-muted small fw-semibold">Phone Number</label>
                                    <p class="mb-0">
                                        @if($inquiry->phone)
                                            <a href="tel:{{ $inquiry->phone }}" class="text-primary">
                                                {{ $inquiry->phone }}
                                            </a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="text-muted small fw-semibold">Inquiry Type</label>
                                    <p class="mb-0">
                                        @php
                                            $typeColors = [
                                                'property'  => 'bg-primary-subtle text-primary',
                                                'general'   => 'bg-secondary-subtle text-secondary',
                                                'valuation' => 'bg-info-subtle text-info',
                                            ];
                                            $tColor = $typeColors[$inquiry->inquiry_type] ?? 'bg-secondary-subtle text-secondary';
                                        @endphp
                                        <span class="badge {{ $tColor }} fs-2 py-1 px-2">
                                            {{ ucfirst($inquiry->inquiry_type ?? 'general') }}
                                        </span>
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="text-muted small fw-semibold">Received On</label>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($inquiry->created_at)->format('d M Y, h:i A') }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="text-muted small fw-semibold">Budget</label>
                                    <p class="mb-0">
                                        @if($inquiry->budget)
                                            <span class="fw-semibold">AED {{ number_format($inquiry->budget) }}</span>
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Message --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3 pb-2 border-bottom">
                                <iconify-icon icon="solar:chat-line-line-duotone" class="me-2"></iconify-icon>
                                Message
                            </h5>
                            <p class="mb-0" style="line-height:1.8;">
                                {{ $inquiry->message ?? 'No message provided.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3 pb-2 border-bottom">
                                <iconify-icon icon="solar:notes-line-duotone" class="me-2"></iconify-icon>
                                Admin Notes
                            </h5>
                            <form action="{{ route('admin.inquiries.notes', $inquiry->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control" name="notes" rows="5"
                                              placeholder="Add your notes about this lead...">{{ $inquiry->notes }}</textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary hstack gap-2">
                                        <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                        Save Notes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                {{-- RIGHT: Status & Property --}}
                <div class="col-lg-4">

                    {{-- Status Update --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3 pb-2 border-bottom">
                                <iconify-icon icon="solar:flag-line-duotone" class="me-2"></iconify-icon>
                                Lead Status
                            </h5>

                            {{-- Current Status --}}
                            <div class="mb-4 text-center">
                                @php
                                    $statusColors = [
                                        'new'       => 'bg-danger-subtle text-danger',
                                        'contacted' => 'bg-warning-subtle text-warning',
                                        'qualified' => 'bg-success-subtle text-success',
                                        'closed'    => 'bg-secondary-subtle text-secondary',
                                    ];
                                    $sColor = $statusColors[$inquiry->status] ?? 'bg-secondary-subtle text-secondary';
                                @endphp
                                <span class="badge {{ $sColor }} fs-4 px-4 py-2">
                                    {{ ucfirst($inquiry->status) }}
                                </span>
                            </div>

                            {{-- Change Status --}}
                            <form action="{{ route('admin.inquiries.status', $inquiry->id) }}" method="POST">
                                @csrf
                                <div class="form-floating mb-3">
                                    <select class="form-select" name="status">
                                        <option value="new"       {{ $inquiry->status == 'new'       ? 'selected' : '' }}>New</option>
                                        <option value="contacted" {{ $inquiry->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="qualified" {{ $inquiry->status == 'qualified' ? 'selected' : '' }}>Qualified</option>
                                        <option value="closed"    {{ $inquiry->status == 'closed'    ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    <label>Update Status</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 hstack gap-2 justify-content-center">
                                    <iconify-icon icon="solar:refresh-line-duotone" class="fs-5"></iconify-icon>
                                    Update Status
                                </button>
                            </form>

                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3 pb-2 border-bottom">
                                <iconify-icon icon="solar:bolt-line-duotone" class="me-2"></iconify-icon>
                                Quick Actions
                            </h5>

                            <div class="d-grid gap-2">

                                <a href="mailto:{{ $inquiry->email }}"
                                   class="btn btn-outline-primary hstack gap-2 justify-content-center">
                                    <iconify-icon icon="solar:letter-line-duotone" class="fs-5"></iconify-icon>
                                    Send Email
                                </a>

                                @if($inquiry->phone)
                                    <a href="tel:{{ $inquiry->phone }}"
                                       class="btn btn-outline-success hstack gap-2 justify-content-center">
                                        <iconify-icon icon="solar:phone-line-duotone" class="fs-5"></iconify-icon>
                                        Call Now
                                    </a>

                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $inquiry->phone) }}"
                                       target="_blank"
                                       class="btn btn-outline-success hstack gap-2 justify-content-center">
                                        <iconify-icon icon="solar:chat-round-like-line-duotone" class="fs-5"></iconify-icon>
                                        WhatsApp
                                    </a>
                                @endif

                                <a href="{{ route('admin.inquiries.delete', $inquiry->id) }}"
                                   class="btn btn-outline-danger hstack gap-2 justify-content-center"
                                   onclick="return confirm('Delete this inquiry?')">
                                    <iconify-icon icon="solar:trash-bin-trash-line-duotone" class="fs-5"></iconify-icon>
                                    Delete Inquiry
                                </a>

                            </div>
                        </div>
                    </div>

                    {{-- Linked Property --}}
                    @if($inquiry->property_title)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3 pb-2 border-bottom">
                                <iconify-icon icon="solar:buildings-line-duotone" class="me-2"></iconify-icon>
                                Linked Property
                            </h5>

                            @if($inquiry->property_image)
                                <img src="{{ asset($inquiry->property_image) }}"
                                     alt="{{ $inquiry->property_title }}"
                                     class="img-fluid rounded mb-3"
                                     style="object-fit:cover; height:120px; width:100%;">
                            @endif

                            <p class="fw-semibold mb-1">{{ $inquiry->property_title }}</p>
                            <small class="text-muted">Ref: {{ $inquiry->property_ref }}</small>

                            <div class="mt-3">
                                <a href="{{ route('admin.properties.edit', $inquiry->property_id) }}"
                                   class="btn btn-sm btn-outline-primary w-100">
                                    <iconify-icon icon="solar:pen-line-duotone" class="me-1"></iconify-icon>
                                    View Property
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Back Button --}}
                    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary w-100 hstack gap-2 justify-content-center">
                        <iconify-icon icon="solar:arrow-left-line-duotone" class="fs-5"></iconify-icon>
                        Back to All Inquiries
                    </a>

                </div>

            </div>

        </div>
    </div>
@include('admin.include.footer')