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
                                    <h4 class="mb-4 mb-sm-0 card-title">Add Branded Residence</h4>
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
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Add</span>
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

            <form action="{{ route('admin.branded-residences.store') }}" method="POST" enctype="multipart/form-data">
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
                                                   value="{{ old('brand_name') }}" required>
                                            <label>Brand Name * <small class="text-muted">(e.g. Bulgari, Armani)</small></label>
                                            @if($errors->has('brand_name'))
                                                <span class="text-danger text-sm">This field is required.</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Title --}}
                                    <div class="col-md-5">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="title"
                                                   id="residence_title"
                                                   placeholder="Residence Title"
                                                   value="{{ old('title') }}"
                                                   oninput="generateSlug()"
                                                   required>
                                            <label>Residence Title *</label>
                                            @if($errors->has('title'))
                                                <span class="text-danger text-sm">This field is required.</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Price From --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="price_from"
                                                   placeholder="Price From"
                                                   value="{{ old('price_from') }}" step="0.01">
                                            <label>Starting Price (AED)</label>
                                        </div>
                                    </div>

                                    {{-- Community --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="community_id">
                                                <option value="">Select Community</option>
                                                @foreach($communities as $community)
                                                    <option value="{{ $community->id }}" {{ old('community_id') == $community->id ? 'selected' : '' }}>
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
                                                    <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
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
                                                  rows="5"
                                                  placeholder="Branded residence description...">{{ old('description') }}</textarea>
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
                                        <input class="form-control" type="file" name="main_image" accept="image/*">
                                        <div class="form-text">Cover image for this residence.</div>
                                    </div>

                                    {{-- Brand Logo --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Brand Logo</label>
                                        <input class="form-control" type="file" name="brand_logo" accept="image/*">
                                        <div class="form-text">e.g. Bulgari logo, Armani logo. PNG with transparent bg recommended.</div>
                                    </div>

                                    {{-- Flags --}}
                                    <div class="col-md-4 d-flex align-items-center gap-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_featured" id="is_featured"
                                                   {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_featured">Featured</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_active" id="is_active" checked>
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
                                Save Residence
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
@include('admin.include.footer')

<script>
function generateSlug() {
    const name = document.getElementById('residence_title').value;
    const slug = name.trim().toLowerCase()
        .replace(/&/g, '-and-')
        .replace(/[\s\W-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}
</script>