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
                                    <h4 class="mb-4 mb-sm-0 card-title">Investments</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Investments</span>
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
                            <form method="GET" action="{{ route('admin.investments.index') }}">
                                <div class="row g-2 align-items-end">

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Search</label>
                                        <input type="text" class="form-control" name="search"
                                               placeholder="Title or Investment Type..."
                                               value="{{ request('search') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Community</label>
                                        <select class="form-select" name="community_id">
                                            <option value="">All Communities</option>
                                            @foreach($communities as $community)
                                                <option value="{{ $community->id }}" {{ request('community_id') == $community->id ? 'selected' : '' }}>
                                                    {{ $community->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <iconify-icon icon="solar:filter-line-duotone" class="me-1"></iconify-icon>
                                            Filter
                                        </button>
                                        <a href="{{ route('admin.investments.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                                    All Investments
                                    <span class="badge bg-primary-subtle text-primary ms-2">{{ count($investments) }}</span>
                                </h4>
                                <a href="{{ route('admin.investments.create') }}" class="btn btn-primary hstack gap-2">
                                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-4"></iconify-icon>
                                    Add Investment
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>ROI %</th>
                                            <th>Gross Yield</th>
                                            <th>Annual Rental</th>
                                            <th>Community</th>
                                            <th>Featured</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($investments as $investment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($investment->main_image)
                                                    <img src="{{ asset('public/'.$investment->main_image) }}"
                                                         alt="{{ $investment->title }}"
                                                         class="rounded"
                                                         width="60" height="50"
                                                         style="object-fit:cover;">
                                                @else
                                                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                         style="width:60px;height:50px;">
                                                        <iconify-icon icon="solar:chart-line-duotone" class="text-muted fs-5"></iconify-icon>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold d-block">{{ Str::limit($investment->title, 30) }}</span>
                                                @if($investment->property_title)
                                                    <small class="text-muted">{{ Str::limit($investment->property_title, 25) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($investment->investment_type)
                                                    <span class="badge bg-info-subtle text-info">{{ $investment->investment_type }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($investment->price)
                                                    <span class="fw-semibold">
                                                        {{ $investment->price_currency }}
                                                        {{ number_format($investment->price) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($investment->roi_percentage)
                                                    <span class="badge bg-success-subtle text-success fw-semibold">
                                                        {{ $investment->roi_percentage }}%
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($investment->gross_yield)
                                                    {{ $investment->gross_yield }}%
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($investment->annual_rental)
                                                    {{ $investment->price_currency }} {{ number_format($investment->annual_rental) }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td><small>{{ $investment->community_name ?? '—' }}</small></td>
                                            <td>
                                                <a href="{{ route('admin.investments.toggle-featured', $investment->id) }}">
                                                    @if($investment->is_featured)
                                                        <iconify-icon icon="solar:star-bold" class="fs-5 text-warning"></iconify-icon>
                                                    @else
                                                        <iconify-icon icon="solar:star-line-duotone" class="fs-5 text-muted"></iconify-icon>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.investments.toggle-status', $investment->id) }}">
                                                    @if($investment->is_active)
                                                        <span class="badge bg-success-subtle text-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                                    @endif
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{ route('admin.investments.edit', $investment->id) }}"
                                                       class="btn btn-sm btn-info" title="Edit">
                                                        <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('admin.investments.delete', $investment->id) }}"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Delete {{ addslashes($investment->title) }}?')">
                                                        <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-5 text-muted">
                                                <iconify-icon icon="solar:chart-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                                No investments found.
                                                <a href="{{ route('admin.investments.create') }}">Add one now</a>
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