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
                                    <h4 class="mb-4 mb-sm-0 card-title">Edit Recognition</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Recognitions</span>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Edit</span>
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
            @if($errors->any())
                <div class="alert alert-danger text-center">
                    Please fill in the required fields.
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Form --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('admin.recognitions.update', $recognition->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    {{-- Media Name --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="media_name"
                                                   placeholder="Media Name"
                                                   value="{{ old('media_name', $recognition->media_name) }}"
                                                   required>
                                            <label>Media Name *</label>
                                            @if($errors->has('media_name'))
                                                <span class="text-danger text-sm">This field is required.</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- URL --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="url"
                                                   class="form-control"
                                                   name="url"
                                                   placeholder="https://example.com"
                                                   value="{{ old('url', $recognition->url) }}">
                                            <label>Website URL</label>
                                        </div>
                                    </div>

                                    {{-- Logo --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Logo</label>
                                        @if($recognition->logo)
                                            <div class="mb-2 d-flex align-items-center gap-3">
                                                <img src="{{ asset('public/'.$recognition->logo) }}"
                                                     alt="{{ $recognition->media_name }}"
                                                     height="50"
                                                     style="object-fit:contain; max-width:120px;">
                                                <small class="text-muted">Current logo</small>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="logo" accept="image/*">
                                        <div class="form-text">Leave empty to keep current logo.</div>
                                    </div>

                                    {{-- Sort Order --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number"
                                                   class="form-control"
                                                   name="sort_order"
                                                   placeholder="Sort Order"
                                                   value="{{ old('sort_order', $recognition->sort_order) }}"
                                                   min="0">
                                            <label>Sort Order</label>
                                        </div>
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-3 d-flex align-items-center">
                                        <div class="form-check form-switch ms-2">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="is_active"
                                                   id="is_active"
                                                   {{ old('is_active', $recognition->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_active">Active</label>
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                            <a href="{{ route('admin.recognitions.index') }}" class="btn btn-outline-secondary">
                                                Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary hstack gap-2">
                                                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                                Update Recognition
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@include('admin.include.footer')