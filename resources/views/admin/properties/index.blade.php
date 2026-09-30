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
                                    <h4 class="mb-4 mb-sm-0 card-title">Properties</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Properties</span>
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Filters --}}
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body py-3">
                            <form method="GET" action="{{ route('admin.properties.index') }}">
                                <div class="row g-2 align-items-end">

                                    {{-- Search --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Search</label>
                                        <input type="text"
                                               class="form-control"
                                               name="search"
                                               placeholder="Title or Reference No..."
                                               value="{{ request('search') }}">
                                    </div>

                                    {{-- Listing Type --}}
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Listing Type</label>
                                        <select class="form-select" name="listing_type">
                                            <option value="">All Types</option>
                                            <option value="sale"          {{ request('listing_type') == 'sale'          ? 'selected' : '' }}>For Sale</option>
                                            <option value="rent"          {{ request('listing_type') == 'rent'          ? 'selected' : '' }}>For Rent</option>
                                            <option value="off_plan"      {{ request('listing_type') == 'off_plan'      ? 'selected' : '' }}>Off Plan</option>
                                            <option value="international" {{ request('listing_type') == 'international' ? 'selected' : '' }}>International</option>
                                            <option value="private"       {{ request('listing_type') == 'private'       ? 'selected' : '' }}>Private</option>
                                        </select>
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="">All Status</option>
                                            <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                                            <option value="sold"     {{ request('status') == 'sold'     ? 'selected' : '' }}>Sold</option>
                                            <option value="rented"   {{ request('status') == 'rented'   ? 'selected' : '' }}>Rented</option>
                                            <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>

                                    {{-- Community --}}
                                    <div class="col-md-3">
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

                                    {{-- Buttons --}}
                                    <div class="col-md-2 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <iconify-icon icon="solar:filter-line-duotone" class="me-1"></iconify-icon>
                                            Filter
                                        </button>
                                        <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary w-100">
                                            Reset
                                        </a>
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
                                    All Properties
                                    <span class="badge bg-primary-subtle text-primary ms-2">{{ count($properties) }}</span>
                                </h4>
                                <a href="{{ route('admin.properties.create') }}" class="btn btn-primary hstack gap-2">
                                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-4"></iconify-icon>
                                    Add New Property
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Title / Ref</th>
                                            <th>Type</th>
                                            <th>Listing</th>
                                            <th>Price</th>
                                            <th>Beds/Baths</th>
                                            <th>Community</th>
                                            <th>Featured</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($properties as $property)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($property->main_image)
                                                    <img src="{{ asset('public/'.$property->main_image) }}"
                                                         alt="{{ $property->title }}"
                                                         class="rounded"
                                                         width="60" height="50"
                                                         style="object-fit:cover;">
                                                @else
                                                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                         style="width:60px;height:50px;">
                                                        <iconify-icon icon="solar:buildings-line-duotone" class="text-muted fs-5"></iconify-icon>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold d-block">{{ Str::limit($property->title, 35) }}</span>
                                                <small class="text-muted">{{ $property->reference_no }}</small>
                                            </td>
                                            <td>
                                                <small class="badge bg-secondary-subtle text-secondary">{{ $property->type_name ?? '—' }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $listingColors = [
                                                        'sale'          => 'bg-success-subtle text-success',
                                                        'rent'          => 'bg-info-subtle text-info',
                                                        'off_plan'      => 'bg-warning-subtle text-warning',
                                                        'international' => 'bg-primary-subtle text-primary',
                                                        'private'       => 'bg-danger-subtle text-danger',
                                                    ];
                                                    $listingLabels = [
                                                        'sale'          => 'Sale',
                                                        'rent'          => 'Rent',
                                                        'off_plan'      => 'Off Plan',
                                                        'international' => 'International',
                                                        'private'       => 'Private',
                                                    ];
                                                    $lColor = $listingColors[$property->listing_type] ?? 'bg-secondary-subtle text-secondary';
                                                    $lLabel = $listingLabels[$property->listing_type] ?? $property->listing_type;
                                                @endphp
                                                <span class="badge {{ $lColor }}">{{ $lLabel }}</span>
                                            </td>
                                            <td>
                                                @if($property->price_on_request)
                                                    <span class="text-muted">On Request</span>
                                                @else
                                                    <span class="fw-semibold">
                                                        {{ $property->price_currency }}
                                                        {{ number_format($property->price)  }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($property->bedrooms !== null)
                                                    <span>{{ $property->bedrooms }} BR / {{ $property->bathrooms }} BA</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $property->community_name ?? '—' }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.properties.toggle-featured', $property->id) }}">
                                                    @if($property->is_featured)
                                                        <iconify-icon icon="solar:star-bold" class="fs-5 text-warning"></iconify-icon>
                                                    @else
                                                        <iconify-icon icon="solar:star-line-duotone" class="fs-5 text-muted"></iconify-icon>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'active'   => 'bg-success-subtle text-success',
                                                        'sold'     => 'bg-danger-subtle text-danger',
                                                        'rented'   => 'bg-info-subtle text-info',
                                                        'reserved' => 'bg-warning-subtle text-warning',
                                                        'inactive' => 'bg-secondary-subtle text-secondary',
                                                    ];
                                                    $sColor = $statusColors[$property->status] ?? 'bg-secondary-subtle text-secondary';
                                                @endphp
                                                <span class="badge {{ $sColor }}">{{ ucfirst($property->status) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{ route('admin.properties.edit', $property->id) }}"
                                                       class="btn btn-sm btn-info" title="Edit">
                                                        <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('admin.properties.delete', $property->id) }}"
                                                       class="btn btn-sm btn-danger"
                                                       title="Delete"
                                                       onclick="return confirm('Delete {{ addslashes($property->title) }}? This cannot be undone.')">
                                                        <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-5 text-muted">
                                                <iconify-icon icon="solar:buildings-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                                No properties found.
                                                <a href="{{ route('admin.properties.create') }}">Add one now</a>
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