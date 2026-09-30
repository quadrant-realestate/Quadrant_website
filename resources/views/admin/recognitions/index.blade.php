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
                                    <h4 class="mb-4 mb-sm-0 card-title">Recognitions & Awards</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Media & Content</span>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Recognitions</span>
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

            {{-- Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h4 class="card-title mb-0">
                                    All Recognitions
                                    <span class="badge bg-primary-subtle text-primary ms-2">{{ count($recognitions) }}</span>
                                </h4>
                                <a href="{{ route('admin.recognitions.create') }}" class="btn btn-primary hstack gap-2">
                                    <iconify-icon icon="solar:add-circle-line-duotone" class="fs-4"></iconify-icon>
                                    Add Recognition
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Logo</th>
                                            <th>Media Name</th>
                                            <th>URL</th>
                                            <th>Sort Order</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recognitions as $recognition)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($recognition->logo)
                                                    <img src="{{ asset('public/'.$recognition->logo) }}"
                                                         alt="{{ $recognition->media_name }}"
                                                         height="40"
                                                         style="object-fit:contain; max-width:100px;">
                                                @else
                                                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                         style="width:60px;height:40px;">
                                                        <iconify-icon icon="solar:medal-ribbons-star-line-duotone" class="text-muted fs-5"></iconify-icon>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $recognition->media_name }}</span>
                                            </td>
                                            <td>
                                                @if($recognition->url)
                                                    <a href="{{ $recognition->url }}" target="_blank" class="text-primary">
                                                        <iconify-icon icon="solar:link-line-duotone" class="me-1"></iconify-icon>
                                                        {{ Str::limit($recognition->url, 35) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $recognition->sort_order }}</td>
                                            <td>
                                                @if($recognition->is_active)
                                                    <span class="badge bg-success-subtle text-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{ route('admin.recognitions.toggle', $recognition->id) }}"
                                                       class="btn btn-sm {{ $recognition->is_active ? 'btn-warning' : 'btn-success' }}"
                                                       title="{{ $recognition->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <iconify-icon icon="{{ $recognition->is_active ? 'solar:eye-closed-line-duotone' : 'solar:eye-line-duotone' }}"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('admin.recognitions.edit', $recognition->id) }}"
                                                       class="btn btn-sm btn-info" title="Edit">
                                                        <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('admin.recognitions.delete', $recognition->id) }}"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Delete {{ addslashes($recognition->media_name) }}?')">
                                                        <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <iconify-icon icon="solar:medal-ribbons-star-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                                No recognitions found.
                                                <a href="{{ route('admin.recognitions.create') }}">Add one now</a>
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