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
                                    <h4 class="mb-4 mb-sm-0 card-title">Edit Branded Residence</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Branded Residences</span>
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
                <div class="alert alert-danger text-center">Please fill in the required fields.</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.branded-residences.update', $residence->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ===== SECTION 1: Brand & Basic Info ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:crown-line-duotone" class="me-2"></iconify-icon>
                                    Brand & Basic Information
                                </h5>
                                <div class="row">

                                    {{-- Brand Name --}}
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="brand_name"
                                                   placeholder="Brand Name"
                                                   value="{{ old('brand_name', $residence->brand_name) }}" required>
                                            <label>Brand Name *</label>
                                        </div>
                                    </div>

                                    {{-- Title --}}
                                    <div class="col-md-5">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="title"
                                                   placeholder="Residence Title"
                                                   value="{{ old('title', $residence->title) }}" required>
                                            <label>Residence Title *</label>
                                        </div>
                                    </div>

                                    {{-- Price From --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="price_from"
                                                   placeholder="Price From"
                                                   value="{{ old('price_from', $residence->price_from) }}" step="0.01">
                                            <label>Starting Price (AED)</label>
                                        </div>
                                    </div>

                                    {{-- Community --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="community_id">
                                                <option value="">Select Community</option>
                                                @foreach($communities as $community)
                                                    <option value="{{ $community->id }}" {{ old('community_id', $residence->community_id) == $community->id ? 'selected' : '' }}>
                                                        {{ $community->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Community</label>
                                        </div>
                                    </div>

                                    {{-- Linked Property --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="property_id">
                                                <option value="">No Linked Property</option>
                                                @foreach($properties as $property)
                                                    <option value="{{ $property->id }}" {{ old('property_id', $residence->property_id) == $property->id ? 'selected' : '' }}>
                                                        {{ $property->title }} ({{ $property->reference_no }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Linked Property (Optional)</label>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Description</label>
                                        <textarea class="form-control" name="description"
                                                  rows="5">{{ old('description', $residence->description) }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 2: Media & Options ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:gallery-wide-line-duotone" class="me-2"></iconify-icon>
                                    Media & Options
                                </h5>
                                <div class="row">

                                    {{-- Main Image --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Main Image</label>
                                        @if($residence->main_image)
                                            <div class="mb-2">
                                                <img src="{{ asset('public/'.$residence->main_image) }}"
                                                     alt="Main" class="rounded"
                                                     height="80" style="object-fit:cover;">
                                                <small class="text-muted d-block mt-1">Current image</small>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="main_image" accept="image/*">
                                        <div class="form-text">Leave empty to keep current image.</div>
                                    </div>

                                    {{-- Brand Logo --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Brand Logo</label>
                                        @if($residence->brand_logo)
                                            <div class="mb-2">
                                                <img src="{{ asset('public/'.$residence->brand_logo) }}"
                                                     alt="Brand Logo"
                                                     height="40"
                                                     style="object-fit:contain;">
                                                <small class="text-muted d-block mt-1">Current logo</small>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="brand_logo" accept="image/*">
                                        <div class="form-text">Leave empty to keep current logo.</div>
                                    </div>

                                    {{-- Flags --}}
                                    <div class="col-md-4 d-flex align-items-center gap-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_featured" id="is_featured"
                                                   {{ old('is_featured', $residence->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_featured">Featured</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_active" id="is_active"
                                                   {{ old('is_active', $residence->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_active">Active</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.branded-residences.index') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4 hstack gap-2">
                                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                Update Residence
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
@include('admin.include.footer')