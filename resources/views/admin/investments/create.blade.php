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
                                    <h4 class="mb-4 mb-sm-0 card-title">Add Investment</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Investments</span>
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

            <form action="{{ route('admin.investments.store') }}" method="POST" enctype="multipart/form-data">
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
                                                   placeholder="Investment Title"
                                                   value="{{ old('title') }}" required>
                                            <label>Investment Title *</label>
                                            @if($errors->has('title'))
                                                <span class="text-danger text-sm">This field is required.</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Investment Type --}}
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="investment_type">
                                                <option value="">Select Type</option>
                                                <option value="Buy-to-Let"  {{ old('investment_type') == 'Buy-to-Let'  ? 'selected' : '' }}>Buy-to-Let</option>
                                                <option value="Commercial"  {{ old('investment_type') == 'Commercial'  ? 'selected' : '' }}>Commercial</option>
                                                <option value="Off-Plan"    {{ old('investment_type') == 'Off-Plan'    ? 'selected' : '' }}>Off-Plan</option>
                                                <option value="Short-Term"  {{ old('investment_type') == 'Short-Term'  ? 'selected' : '' }}>Short-Term Rental</option>
                                                <option value="Land"        {{ old('investment_type') == 'Land'        ? 'selected' : '' }}>Land</option>
                                            </select>
                                            <label>Investment Type</label>
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

                                    {{-- Description --}}
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Description</label>
                                        <textarea class="form-control" name="description"
                                                  rows="5"
                                                  placeholder="Investment description...">{{ old('description') }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 2: Financial Details ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:chart-line-duotone" class="me-2"></iconify-icon>
                                    Financial Details
                                </h5>
                                <div class="row">

                                    {{-- Price --}}
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="price"
                                                   placeholder="Price"
                                                   value="{{ old('price') }}" step="0.01">
                                            <label>Price</label>
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

                                    {{-- Annual Rental --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="annual_rental"
                                                   placeholder="Annual Rental"
                                                   value="{{ old('annual_rental') }}" step="0.01">
                                            <label>Annual Rental Income</label>
                                        </div>
                                    </div>

                                    {{-- ROI % --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="roi_percentage"
                                                   placeholder="ROI %"
                                                   value="{{ old('roi_percentage') }}" step="0.01">
                                            <label>ROI (%)</label>
                                        </div>
                                    </div>

                                    {{-- Gross Yield --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="gross_yield"
                                                   placeholder="Gross Yield %"
                                                   value="{{ old('gross_yield') }}" step="0.01">
                                            <label>Gross Yield (%)</label>
                                        </div>
                                    </div>

                                    {{-- Net Yield --}}
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="net_yield"
                                                   placeholder="Net Yield %"
                                                   value="{{ old('net_yield') }}" step="0.01">
                                            <label>Net Yield (%)</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 3: Media & Options ===== --}}
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
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Main Image</label>
                                        <input class="form-control" type="file" name="main_image" accept="image/*">
                                    </div>

                                    {{-- Flags --}}
                                    <div class="col-md-6 d-flex align-items-center gap-4">
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
                            <a href="{{ route('admin.investments.index') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4 hstack gap-2">
                                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                Save Investment
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
@include('admin.include.footer')