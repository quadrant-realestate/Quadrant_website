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
                                    <h4 class="mb-4 mb-sm-0 card-title">Add Community</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Communities</span>
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
                <div class="alert alert-danger text-center">
                    Please fill in the required fields.
                </div>
            @endif

            {{-- Form --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('admin.communities.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="name"
                                                   id="community_name"
                                                   placeholder="Community Name"
                                                   value="{{ old('name') }}"
                                                   oninput="generateSlug()"
                                                   required>
                                            <label>Name *</label>
                                            @if($errors->has('name'))
                                                <span class="text-danger text-sm">This field is required.</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Slug --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="slug"
                                                   id="community_slug"
                                                   placeholder="Slug"
                                                   value="{{ old('slug') }}">
                                            <label>Slug *</label>
                                        </div>
                                    </div>

                                    {{-- City --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="city"
                                                   placeholder="City"
                                                   value="{{ old('city', 'Dubai') }}"
                                                   required>
                                            <label>City *</label>
                                            @if($errors->has('city'))
                                                <span class="text-danger text-sm">This field is required.</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Sort Order --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number"
                                                   class="form-control"
                                                   name="sort_order"
                                                   placeholder="Sort Order"
                                                   value="{{ old('sort_order', 0) }}"
                                                   min="0">
                                            <label>Sort Order</label>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control"
                                                      name="description"
                                                      placeholder="Description"
                                                      style="height: 120px">{{ old('description') }}</textarea>
                                            <label>Description</label>
                                        </div>
                                    </div>

                                    {{-- Community Image --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Community Image</label>
                                        <input class="form-control" type="file" name="image" accept="image/*">
                                        <div class="form-text">Recommended: 800x600px</div>
                                    </div>

                                    {{-- Banner Image --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Banner Image</label>
                                        <input class="form-control" type="file" name="banner_image" accept="image/*">
                                        <div class="form-text">Recommended: 1920x600px</div>
                                    </div>

                                    {{-- Latitude --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="latitude"
                                                   placeholder="Latitude"
                                                   value="{{ old('latitude') }}">
                                            <label>Latitude</label>
                                        </div>
                                    </div>

                                    {{-- Longitude --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="longitude"
                                                   placeholder="Longitude"
                                                   value="{{ old('longitude') }}">
                                            <label>Longitude</label>
                                        </div>
                                    </div>

                                    {{-- Meta Title --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="meta_title"
                                                   placeholder="Meta Title"
                                                   value="{{ old('meta_title') }}">
                                            <label>Meta Title</label>
                                        </div>
                                    </div>

                                    {{-- Meta Description --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="meta_desc"
                                                   placeholder="Meta Description"
                                                   value="{{ old('meta_desc') }}">
                                            <label>Meta Description</label>
                                        </div>
                                    </div>

                                    {{-- Toggles --}}
                                    <div class="col-md-6 d-flex align-items-center gap-4 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                            <label class="form-check-label fw-semibold" for="is_active">Active</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured">
                                            <label class="form-check-label fw-semibold" for="is_featured">Featured</label>
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                            <a href="{{ route('admin.communities.index') }}" class="btn btn-outline-secondary">
                                                Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary hstack gap-2">
                                                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                                Save Community
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
function generateSlug() {
    const name = document.getElementById('community_name').value;
    const slug = name.trim().toLowerCase()
        .replace(/&/g, '-and-')
        .replace(/[\s\W-]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('community_slug').value = slug;
}
</script>