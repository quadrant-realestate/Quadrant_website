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
                                    <h4 class="mb-4 mb-sm-0 card-title">Amenities</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Amenities</span>
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

            {{-- Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h4 class="card-title mb-0">All Amenities</h4>
                                <a href="{{ route('admin.amenities.create') }}" class="btn btn-primary hstack gap-2">
                                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-4"></iconify-icon>
                                    Add New Amenity
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table id="myTable" class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Icon</th>
                                            <th>Sort Order</th>
                                            <th>Properties</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($amenities as $amenity)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><span class="fw-semibold">{{ $amenity->name }}</span></td>
                                            <td>
                                                @php
                                                    $categoryColors = [
                                                        'Indoor'   => 'bg-info-subtle text-info',
                                                        'Outdoor'  => 'bg-success-subtle text-success',
                                                        'Building' => 'bg-warning-subtle text-warning',
                                                        'View'     => 'bg-primary-subtle text-primary',
                                                    ];
                                                    $color = $categoryColors[$amenity->category] ?? 'bg-secondary-subtle text-secondary';
                                                @endphp
                                                <span class="badge {{ $color }}">{{ $amenity->category }}</span>
                                            </td>
                                            <td>
                                                @if($amenity->icon)
                                                    <iconify-icon icon="{{ $amenity->icon }}" class="fs-5"></iconify-icon>
                                                    <small class="text-muted ms-1">{{ $amenity->icon }}</small>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $amenity->sort_order }}</td>
                                            <td>
                                                @php
                                                    $count = DB::table('property_amenities')->where('amenity_id', $amenity->id)->count();
                                                @endphp
                                                <span class="badge bg-primary-subtle text-primary">{{ $count }} properties</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{ route('admin.amenities.edit', $amenity->id) }}"
                                                       class="btn btn-sm btn-info" title="Edit">
                                                        <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('admin.amenities.delete', $amenity->id) }}"
                                                       class="btn btn-sm btn-danger"
                                                       title="Delete"
                                                       onclick="return confirm('Are you sure you want to delete {{ $amenity->name }}?')">
                                                        <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <iconify-icon icon="solar:inbox-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                                No amenities found.
                                                <a href="{{ route('admin.amenities.create') }}">Add one now</a>
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