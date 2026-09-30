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
                                    <h4 class="mb-4 mb-sm-0 card-title">Edit Development</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Developments</span>
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
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Project Detail Page Content</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">
            These sections power the Project Detail Page (Floor Plans tab and Highlights &amp; Amenities grid).
        </p>
        <a href="{{ route('admin.development_floor_plans.index', $development->id) }}" class="btn btn-outline-primary">
            <iconify-icon icon="solar:layers-line-duotone"></iconify-icon>
            Manage Floor Plans
        </a>
        <a href="{{ route('admin.development_amenities.edit', $development->id) }}" class="btn btn-outline-primary">
            <iconify-icon icon="solar:star-shine-line-duotone"></iconify-icon>
            Manage Amenities
        </a>
    </div>
</div>
            <form action="{{ route('admin.developments.update', $development->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ===== SECTION 1: Basic Info ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:document-text-line-duotone" class="me-2"></iconify-icon>
                                    Basic Information
                                </h5>
                                <div class="row">

                                    <div class="col-md-8">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="title"
                                                   placeholder="Development Title"
                                                   value="{{ old('title', $development->title) }}" required>
                                            <label>Development Title *</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="status">
                                                <option value="upcoming"           {{ old('status', $development->status) == 'upcoming'           ? 'selected' : '' }}>Upcoming</option>
                                                <option value="launched"           {{ old('status', $development->status) == 'launched'           ? 'selected' : '' }}>Launched</option>
                                                <option value="under_construction" {{ old('status', $development->status) == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                                                <option value="completed"          {{ old('status', $development->status) == 'completed'          ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            <label>Status</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="developer_name"
                                                   placeholder="Developer Name"
                                                   value="{{ old('developer_name', $development->developer_name) }}">
                                            <label>Developer Name</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="community_id">
                                                <option value="">Select Community</option>
                                                @foreach($communities as $community)
                                                    <option value="{{ $community->id }}" {{ old('community_id', $development->community_id) == $community->id ? 'selected' : '' }}>
                                                        {{ $community->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Community</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="property_types"
                                                   placeholder="Property Types"
                                                   value="{{ old('property_types', $development->property_types) }}">
                                            <label>Property Types</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="total_units"
                                                   placeholder="Total Units"
                                                   value="{{ old('total_units', $development->total_units) }}" min="0">
                                            <label>Total Units</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="floors"
                                                   placeholder="Floors"
                                                   value="{{ old('floors', $development->floors) }}" min="0">
                                            <label>Total Floors</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="short_description"
                                                      placeholder="Short Description"
                                                      style="height:80px">{{ old('short_description', $development->short_description) }}</textarea>
                                            <label>Short Description</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Full Description</label>
                                        <textarea class="form-control" name="description" rows="6">{{ old('description', $development->description) }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 2: Pricing & Timeline ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:wallet-money-line-duotone" class="me-2"></iconify-icon>
                                    Pricing & Timeline
                                </h5>
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="price_from"
                                                   placeholder="Starting Price"
                                                   value="{{ old('price_from', $development->price_from) }}" step="0.01">
                                            <label>Starting Price</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="price_currency">
                                                <option value="AED" {{ old('price_currency', $development->price_currency) == 'AED' ? 'selected' : '' }}>AED</option>
                                                <option value="USD" {{ old('price_currency', $development->price_currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="GBP" {{ old('price_currency', $development->price_currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                                <option value="EUR" {{ old('price_currency', $development->price_currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            </select>
                                            <label>Currency</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="down_payment_pct"
                                                   placeholder="Down Payment %"
                                                   value="{{ old('down_payment_pct', $development->down_payment_pct) }}"
                                                   step="0.01" min="0" max="100">
                                            <label>Down Payment (%)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="completion_pct"
                                                   placeholder="Completion %"
                                                   value="{{ old('completion_pct', $development->completion_pct) }}"
                                                   min="0" max="100">
                                            <label>Completion (%)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="date" class="form-control" name="handover_date"
                                                   value="{{ old('handover_date', $development->handover_date) }}">
                                            <label>Handover Date</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="payment_plan"
                                                      placeholder="Payment Plan"
                                                      style="height:100px">{{ old('payment_plan', $development->payment_plan) }}</textarea>
                                            <label>Payment Plan Details</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 3: Media ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:gallery-wide-line-duotone" class="me-2"></iconify-icon>
                                    Media
                                </h5>
                                <div class="row">

                                    {{-- Main Image --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Main Image</label>
                                        @if($development->main_image)
                                            <div class="mb-2">
                                                <img src="{{ asset('public/'.$development->main_image) }}"
                                                     alt="Main" class="rounded"
                                                     height="80" style="object-fit:cover;">
                                                <small class="text-muted d-block mt-1">Current image</small>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="main_image" accept="image/*">
                                    </div>

                                    {{-- Banner Image --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Banner Image</label>
                                        @if($development->banner_image)
                                            <div class="mb-2">
                                                <img src="{{ asset('public/'.$development->banner_image) }}"
                                                     alt="Banner" class="rounded"
                                                     height="80" style="object-fit:cover;">
                                                <small class="text-muted d-block mt-1">Current banner</small>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="banner_image" accept="image/*">
                                    </div>

                                    {{-- Developer Logo --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Developer Logo</label>
                                        @if($development->developer_logo)
                                            <div class="mb-2">
                                                <img src="{{ asset('public/'.$development->developer_logo) }}"
                                                     alt="Logo" height="40">
                                                <small class="text-muted d-block mt-1">Current logo</small>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="developer_logo" accept="image/*">
                                    </div>

                                    {{-- Brochure --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Brochure PDF</label>
                                        @if($development->brochure_pdf)
                                            <div class="mb-2">
                                                <a href="{{ asset('public/'.$development->brochure_pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <iconify-icon icon="solar:file-download-line-duotone"></iconify-icon>
                                                    Current Brochure
                                                </a>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="brochure_pdf" accept=".pdf">
                                    </div>

                                    {{-- Gallery --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Add More Gallery Images</label>
                                        <input class="form-control" type="file" name="gallery[]" accept="image/*" multiple>
                                    </div>

                                    {{-- Video URL --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="video_url"
                                                   placeholder="Video URL"
                                                   value="{{ old('video_url', $development->video_url) }}">
                                            <label>Video URL</label>
                                        </div>
                                    </div>

                                    {{-- Existing Gallery --}}
                                    @if(count($gallery) > 0)
                                    <div class="col-12 mt-2">
                                        <label class="form-label fw-semibold">Existing Gallery</label>
                                        <div class="d-flex flex-wrap gap-3">
                                            @foreach($gallery as $img)
                                            <div class="position-relative">
                                                <img src="{{ asset('public/'.$img->image_path) }}"
                                                     alt="Gallery" class="rounded"
                                                     width="100" height="80"
                                                     style="object-fit:cover;">
                                                <a href="{{ route('admin.developments.gallery.delete', $img->id) }}"
                                                   class="position-absolute top-0 end-0 btn btn-danger btn-sm p-0 px-1"
                                                   style="font-size:10px;"
                                                   onclick="return confirm('Delete this image?')">&times;</a>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 4: SEO & Options ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:settings-line-duotone" class="me-2"></iconify-icon>
                                    SEO & Options
                                </h5>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="meta_title"
                                                   placeholder="Meta Title"
                                                   value="{{ old('meta_title', $development->meta_title) }}">
                                            <label>Meta Title</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="meta_desc"
                                                   placeholder="Meta Description"
                                                   value="{{ old('meta_desc', $development->meta_desc) }}">
                                            <label>Meta Description</label>
                                        </div>
                                    </div>
<div class="col-md-6">
                                    <div class="form-group">
                                        <label>OG Image <small class="text-muted">(Social share image — ideally 1200×630px)</small></label>
                                        <input type="file" name="og_image" class="form-control">
                                        @if(!empty($development->og_image))
                                            <img src="{{ URL::to('') }}/public/{{ $development->og_image }}" height="60" class="mt-2 d-block">
                                        @endif
                                    </div>
</div>
                                    <div class="col-12 d-flex flex-wrap gap-4 mt-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_featured" id="is_featured"
                                                   {{ old('is_featured', $development->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_featured">Featured</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_active" id="is_active"
                                                   {{ old('is_active', $development->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_active">Active</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RERA Compliance --}}
                <div class="card mt-4">
                    <div class="card-header"><h5>RERA Compliance</h5></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>RERA Permit Number</label>
                                    <input type="text" name="rera_permit" class="form-control"
                                        value="{{ old('rera_permit', $development->rera_permit ?? '') }}"
                                        placeholder="e.g. 7123456789">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>RERA QR Code Image</label>
                                    <input type="file" name="rera_qr_image" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bedroom Range</label>
                                    <input type="text" name="bedroom_range" class="form-control"
                                        value="{{ old('bedroom_range', $development->bedroom_range ?? '') }}"
                                        placeholder="e.g. 1-3 BR">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Size From (sq ft)</label>
                                    <input type="text" name="size_from" class="form-control"
                                        value="{{ old('size_from', $development->size_from ?? '') }}"
                                        placeholder="e.g. 750">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Size To (sq ft)</label>
                                    <input type="text" name="size_to" class="form-control"
                                        value="{{ old('size_to', $development->size_to ?? '') }}"
                                        placeholder="e.g. 3200">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Quadrant's View <small class="text-muted">(advisory note — your differentiator)</small></label>
                                    <textarea name="quadrant_view" class="form-control" rows="4"
                                            placeholder="Short advisory note from Quadrant about this project...">{{ old('quadrant_view', $development->quadrant_view ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- LOCATION --}}
                {{-- ============================================================ --}}
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Location</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nearby Landmarks
                                <small class="text-muted">(one per line — format: "Dubai Mall — 8 min")</small>
                            </label>
                            <textarea name="nearby_landmarks" class="form-control" rows="5"
                                    placeholder="Dubai Mall — 8 min&#10;Burj Khalifa — 10 min&#10;DXB Airport — 20 min">{{ old('nearby_landmarks', $development->nearby_landmarks ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                {{-- Submit --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.developments.index') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4 hstack gap-2">
                                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                Update Development
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
@include('admin.include.footer')