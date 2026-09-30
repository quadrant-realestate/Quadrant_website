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
                                    <h4 class="mb-4 mb-sm-0 card-title">Developments</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Developments</span>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">All</span>
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

            {{-- Filters --}}
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body py-3">
                            <form method="GET" action="{{ route('admin.developments.index') }}">
                                <div class="row g-2 align-items-end">

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Search</label>
                                        <input type="text" class="form-control" name="search"
                                               placeholder="Title or Developer..."
                                               value="{{ request('search') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="">All Status</option>
                                            <option value="upcoming"           {{ request('status') == 'upcoming'           ? 'selected' : '' }}>Upcoming</option>
                                            <option value="launched"           {{ request('status') == 'launched'           ? 'selected' : '' }}>Launched</option>
                                            <option value="under_construction" {{ request('status') == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                                            <option value="completed"          {{ request('status') == 'completed'          ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <iconify-icon icon="solar:filter-line-duotone" class="me-1"></iconify-icon>
                                            Filter
                                        </button>
                                        <a href="{{ route('admin.developments.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                                    All Developments
                                    <span class="badge bg-primary-subtle text-primary ms-2">{{ count($developments) }}</span>
                                </h4>
                                <a href="{{ route('admin.developments.create') }}" class="btn btn-primary hstack gap-2">
                                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-4"></iconify-icon>
                                    Add Development
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Developer</th>
                                            <th>Community</th>
                                            <th>Price From</th>
                                            <th>Handover</th>
                                            <th>Status</th>
                                            <th>Featured</th>
                                            <th>Active</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($developments as $dev)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($dev->main_image)
                                                    <img src="{{ asset('public/'.$dev->main_image) }}"
                                                         alt="{{ $dev->title }}"
                                                         class="rounded"
                                                         width="60" height="50"
                                                         style="object-fit:cover;">
                                                @else
                                                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                         style="width:60px;height:50px;">
                                                        <iconify-icon icon="solar:city-line-duotone" class="text-muted fs-5"></iconify-icon>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold d-block">{{ Str::limit($dev->title, 30) }}</span>
                                                @if($dev->total_units)
                                                    <small class="text-muted">{{ $dev->total_units }} units</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($dev->developer_logo)
                                                    <img src="{{ asset('public/'.$dev->developer_logo) }}"
                                                         alt="{{ $dev->developer_name }}"
                                                         height="25" class="me-1">
                                                @endif
                                                <small>{{ $dev->developer_name ?? '—' }}</small>
                                            </td>
                                            <td><small>{{ $dev->community_name ?? '—' }}</small></td>
                                            <td>
                                                @if($dev->price_from)
                                                    <span class="fw-semibold">
                                                        {{ $dev->price_currency }}
                                                        {{ number_format($dev->price_from) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($dev->handover_date)
                                                    <small>{{ \Carbon\Carbon::parse($dev->handover_date)->format('M Y') }}</small>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'upcoming'           => 'bg-info-subtle text-info',
                                                        'launched'           => 'bg-success-subtle text-success',
                                                        'under_construction' => 'bg-warning-subtle text-warning',
                                                        'completed'          => 'bg-primary-subtle text-primary',
                                                    ];
                                                    $statusLabels = [
                                                        'upcoming'           => 'Upcoming',
                                                        'launched'           => 'Launched',
                                                        'under_construction' => 'Under Construction',
                                                        'completed'          => 'Completed',
                                                    ];
                                                    $sColor = $statusColors[$dev->status] ?? 'bg-secondary-subtle text-secondary';
                                                    $sLabel = $statusLabels[$dev->status] ?? $dev->status;
                                                @endphp
                                                <span class="badge {{ $sColor }}">{{ $sLabel }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.developments.toggle-featured', $dev->id) }}">
                                                    @if($dev->is_featured)
                                                        <iconify-icon icon="solar:star-bold" class="fs-5 text-warning"></iconify-icon>
                                                    @else
                                                        <iconify-icon icon="solar:star-line-duotone" class="fs-5 text-muted"></iconify-icon>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.developments.toggle-status', $dev->id) }}">
                                                    @if($dev->is_active)
                                                        <span class="badge bg-success-subtle text-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                                    @endif
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{ route('admin.developments.edit', $dev->id) }}"
                                                       class="btn btn-sm btn-info" title="Edit">
                                                        <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('admin.developments.delete', $dev->id) }}"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Delete {{ addslashes($dev->title) }}?')">
                                                        <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-5 text-muted">
                                                <iconify-icon icon="solar:city-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                                No developments found.
                                                <a href="{{ route('admin.developments.create') }}">Add one now</a>
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