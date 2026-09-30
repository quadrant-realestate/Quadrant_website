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
                                    <h4 class="mb-4 mb-sm-0 card-title">Edit Amenity</h4>
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Form --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('admin.amenities.update', $amenity->id) }}" method="POST">
                                @csrf
                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="name"
                                                   id="amenity_name"
                                                   placeholder="Enter Amenity Name"
                                                   value="{{ old('name', $amenity->name) }}"
                                                   required>
                                            <label>Name *</label>
                                            @if($errors->has('name'))
                                                <span class="text-danger text-sm">This field is required.</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Category --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="category" id="category" required>
                                                <option value="" disabled>Select Category</option>
                                                <option value="Indoor"   {{ old('category', $amenity->category) == 'Indoor'   ? 'selected' : '' }}>Indoor</option>
                                                <option value="Outdoor"  {{ old('category', $amenity->category) == 'Outdoor'  ? 'selected' : '' }}>Outdoor</option>
                                                <option value="Building" {{ old('category', $amenity->category) == 'Building' ? 'selected' : '' }}>Building</option>
                                                <option value="View"     {{ old('category', $amenity->category) == 'View'     ? 'selected' : '' }}>View</option>
                                            </select>
                                            <label for="category">Category *</label>
                                            @if($errors->has('category'))
                                                <span class="text-danger text-sm">This field is required.</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Icon --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="icon"
                                                   id="amenity_icon"
                                                   placeholder="Icon"
                                                   value="{{ old('icon', $amenity->icon) }}"
                                                   oninput="previewIcon()">
                                            <label>Icon (Iconify)</label>
                                        </div>
                                        <div class="form-text mb-3">
                                            Enter iconify icon name e.g.
                                            <code>solar:swimming-bold</code> —
                                            browse at
                                            <a href="https://icon-sets.iconify.design" target="_blank">icon-sets.iconify.design</a>
                                            &nbsp;
                                            <span id="icon_preview" class="ms-2 fs-4 text-primary">
                                                @if($amenity->icon)
                                                    <iconify-icon icon="{{ $amenity->icon }}"></iconify-icon>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Sort Order --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number"
                                                   class="form-control"
                                                   name="sort_order"
                                                   placeholder="Sort Order"
                                                   value="{{ old('sort_order', $amenity->sort_order) }}"
                                                   min="0">
                                            <label>Sort Order</label>
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                            <a href="{{ route('admin.amenities.index') }}" class="btn btn-outline-secondary">
                                                Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary hstack gap-2">
                                                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                                Update Amenity
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

<script>
function previewIcon() {
    const icon = document.getElementById('amenity_icon').value;
    const preview = document.getElementById('icon_preview');
    if (icon) {
        preview.innerHTML = '<iconify-icon icon="' + icon + '"></iconify-icon>';
    } else {
        preview.innerHTML = '';
    }
}
</script>