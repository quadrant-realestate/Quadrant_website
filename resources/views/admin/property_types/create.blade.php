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
                                    <h4 class="mb-4 mb-sm-0 card-title">Add Property Type</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Property Types</span>
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

                            <form action="{{ route('admin.property-types.store') }}" method="POST">
                                @csrf
                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="name"
                                                   id="type_name"
                                                   placeholder="Enter Type Name"
                                                   value="{{ old('name') }}"
                                                   oninput="generateSlug()">
                                            <label>Name *</label>
                                            @if($errors->has('name'))
                                                <span class="text-danger text-sm ml-2">This field is required.</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Slug --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control"
                                                   name="slug"
                                                   id="type_slug"
                                                   placeholder="Slug"
                                                   value="{{ old('slug') }}">
                                            <label>Slug *</label>
                                            @if($errors->has('slug'))
                                                <span class="text-danger text-sm ml-2">This field is required.</span>
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

                                    {{-- Status --}}
                                    <div class="col-md-6 d-flex align-items-center mb-3">
                                        <div class="form-check form-switch ms-2">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="is_active"
                                                   id="is_active"
                                                   checked>
                                            <label class="form-check-label fw-semibold" for="is_active">Active</label>
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="col-12">
                                        <div class="d-flex  gap-2 mt-3">
                                            <a href="{{ route('admin.property-types.index') }}" class="btn btn-outline-secondary">
                                                Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary hstack gap-2">
                                                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                                Save Type
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
    const name = document.getElementById('type_name').value;
    const slug = name.trim().toLowerCase()
        .replace(/&/g, '-and-')
        .replace(/[\s\W-]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('type_slug').value = slug;
}
</script>