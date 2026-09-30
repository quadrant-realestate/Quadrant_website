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
                                    <h4 class="mb-4 mb-sm-0 card-title">Add Development</h4>
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

            <form action="{{ route('admin.developments.store') }}" method="POST" enctype="multipart/form-data">
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

                                    {{-- Title --}}
                                    <div class="col-md-8">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="title"
                                                   id="dev_title"
                                                   placeholder="Development Title"
                                                   value="{{ old('title') }}"
                                                   oninput="generateSlug()"
                                                   required>
                                            <label>Development Title *</label>
                                            @if($errors->has('title'))
                                                <span class="text-danger text-sm">This field is required.</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="status">
                                                <option value="upcoming"           {{ old('status') == 'upcoming'           ? 'selected' : '' }}>Upcoming</option>
                                                <option value="launched"           {{ old('status', 'launched') == 'launched' ? 'selected' : '' }}>Launched</option>
                                                <option value="under_construction" {{ old('status') == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                                                <option value="completed"          {{ old('status') == 'completed'          ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            <label>Status</label>
                                        </div>
                                    </div>

                                    {{-- Developer Name --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="developer_name"
                                                   placeholder="Developer Name"
                                                   value="{{ old('developer_name') }}">
                                            <label>Developer Name</label>
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

                                    {{-- Property Types --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="property_types"
                                                   placeholder="Property Types"
                                                   value="{{ old('property_types') }}">
                                            <label>Property Types</label>
                                        </div>
                                        <div class="form-text mb-3">e.g. Apartments, Townhouses, Villas</div>
                                    </div>

                                    {{-- Total Units --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="total_units"
                                                   placeholder="Total Units"
                                                   value="{{ old('total_units') }}" min="0">
                                            <label>Total Units</label>
                                        </div>
                                    </div>

                                    {{-- Floors --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="floors"
                                                   placeholder="Floors"
                                                   value="{{ old('floors') }}" min="0">
                                            <label>Total Floors</label>
                                        </div>
                                    </div>

                                    {{-- Short Description --}}
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="short_description"
                                                      placeholder="Short Description"
                                                      style="height:80px">{{ old('short_description') }}</textarea>
                                            <label>Short Description</label>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Full Description</label>
                                        <textarea class="form-control" name="description"
                                                  rows="6"
                                                  placeholder="Full development description...">{{ old('description') }}</textarea>
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

                                    {{-- Price From --}}
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="price_from"
                                                   placeholder="Starting Price"
                                                   value="{{ old('price_from') }}" step="0.01">
                                            <label>Starting Price</label>
                                        </div>
                                    </div>

                                    {{-- Currency --}}
                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="price_currency">
                                                <option value="AED" selected>AED</option>
                                                <option value="USD">USD</option>
                                                <option value="GBP">GBP</option>
                                                <option value="EUR">EUR</option>
                                            </select>
                                            <label>Currency</label>
                                        </div>
                                    </div>

                                    {{-- Down Payment % --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="down_payment_pct"
                                                   placeholder="Down Payment %"
                                                   value="{{ old('down_payment_pct') }}"
                                                   step="0.01" min="0" max="100">
                                            <label>Down Payment (%)</label>
                                        </div>
                                    </div>

                                    {{-- Completion % --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="completion_pct"
                                                   placeholder="Completion %"
                                                   value="{{ old('completion_pct') }}"
                                                   min="0" max="100">
                                            <label>Completion (%)</label>
                                        </div>
                                    </div>

                                    {{-- Handover Date --}}
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="date" class="form-control" name="handover_date"
                                                   value="{{ old('handover_date') }}">
                                            <label>Handover Date</label>
                                        </div>
                                    </div>

                                    {{-- Payment Plan --}}
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="payment_plan"
                                                      placeholder="Payment Plan"
                                                      style="height:100px">{{ old('payment_plan') }}</textarea>
                                            <label>Payment Plan Details</label>
                                        </div>
                                        <div class="form-text mb-3">e.g. 20% Down, 40% During Construction, 40% on Handover</div>
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
                                        <label class="form-label fw-semibold">Main Image *</label>
                                        <input class="form-control" type="file" name="main_image" accept="image/*">
                                        <div class="form-text">Cover image for this development.</div>
                                    </div>

                                    {{-- Banner Image --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Banner Image</label>
                                        <input class="form-control" type="file" name="banner_image" accept="image/*">
                                        <div class="form-text">Recommended: 1920x600px</div>
                                    </div>

                                    {{-- Developer Logo --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Developer Logo</label>
                                        <input class="form-control" type="file" name="developer_logo" accept="image/*">
                                    </div>

                                    {{-- Brochure PDF --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Brochure PDF</label>
                                        <input class="form-control" type="file" name="brochure_pdf" accept=".pdf">
                                    </div>

                                    {{-- Gallery --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Gallery Images</label>
                                        <input class="form-control" type="file" name="gallery[]" accept="image/*" multiple>
                                        <div class="form-text">Select multiple images.</div>
                                    </div>

                                    {{-- Video URL --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="video_url"
                                                   placeholder="Video URL"
                                                   value="{{ old('video_url') }}">
                                            <label>Video URL (YouTube/Vimeo)</label>
                                        </div>
                                    </div>

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
                                                   value="{{ old('meta_title') }}">
                                            <label>Meta Title</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="meta_desc"
                                                   placeholder="Meta Description"
                                                   value="{{ old('meta_desc') }}">
                                            <label>Meta Description</label>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex flex-wrap gap-4 mt-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_featured" id="is_featured"
                                                   {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_featured">Featured</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_active" id="is_active" checked
                                                   {{ old('is_active', '1') ? 'checked' : '' }}>
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
                                Save Development
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
    const name = document.getElementById('dev_title').value;
    const slug = name.trim().toLowerCase()
        .replace(/&/g, '-and-')
        .replace(/[\s\W-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}
</script>