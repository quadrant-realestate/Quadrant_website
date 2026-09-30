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
                                    <h4 class="mb-4 mb-sm-0 card-title">Edit Investment</h4>
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

            <form action="{{ route('admin.investments.update', $investment->id) }}" method="POST" enctype="multipart/form-data">
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
                                                   placeholder="Investment Title"
                                                   value="{{ old('title', $investment->title) }}" required>
                                            <label>Investment Title *</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="investment_type">
                                                <option value="">Select Type</option>
                                                <option value="Buy-to-Let"  {{ old('investment_type', $investment->investment_type) == 'Buy-to-Let'  ? 'selected' : '' }}>Buy-to-Let</option>
                                                <option value="Commercial"  {{ old('investment_type', $investment->investment_type) == 'Commercial'  ? 'selected' : '' }}>Commercial</option>
                                                <option value="Off-Plan"    {{ old('investment_type', $investment->investment_type) == 'Off-Plan'    ? 'selected' : '' }}>Off-Plan</option>
                                                <option value="Short-Term"  {{ old('investment_type', $investment->investment_type) == 'Short-Term'  ? 'selected' : '' }}>Short-Term Rental</option>
                                                <option value="Land"        {{ old('investment_type', $investment->investment_type) == 'Land'        ? 'selected' : '' }}>Land</option>
                                            </select>
                                            <label>Investment Type</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="property_id">
                                                <option value="">No Linked Property</option>
                                                @foreach($properties as $property)
                                                    <option value="{{ $property->id }}" {{ old('property_id', $investment->property_id) == $property->id ? 'selected' : '' }}>
                                                        {{ $property->title }} ({{ $property->reference_no }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Linked Property (Optional)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="community_id">
                                                <option value="">Select Community</option>
                                                @foreach($communities as $community)
                                                    <option value="{{ $community->id }}" {{ old('community_id', $investment->community_id) == $community->id ? 'selected' : '' }}>
                                                        {{ $community->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Community</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Description</label>
                                        <textarea class="form-control" name="description"
                                                  rows="5">{{ old('description', $investment->description) }}</textarea>
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

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="price"
                                                   placeholder="Price"
                                                   value="{{ old('price', $investment->price) }}" step="0.01">
                                            <label>Price</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="price_currency">
                                                <option value="AED" {{ old('price_currency', $investment->price_currency) == 'AED' ? 'selected' : '' }}>AED</option>
                                                <option value="USD" {{ old('price_currency', $investment->price_currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="GBP" {{ old('price_currency', $investment->price_currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                                <option value="EUR" {{ old('price_currency', $investment->price_currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            </select>
                                            <label>Currency</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="annual_rental"
                                                   placeholder="Annual Rental"
                                                   value="{{ old('annual_rental', $investment->annual_rental) }}" step="0.01">
                                            <label>Annual Rental Income</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="roi_percentage"
                                                   placeholder="ROI %"
                                                   value="{{ old('roi_percentage', $investment->roi_percentage) }}" step="0.01">
                                            <label>ROI (%)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="gross_yield"
                                                   placeholder="Gross Yield %"
                                                   value="{{ old('gross_yield', $investment->gross_yield) }}" step="0.01">
                                            <label>Gross Yield (%)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="net_yield"
                                                   placeholder="Net Yield %"
                                                   value="{{ old('net_yield', $investment->net_yield) }}" step="0.01">
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
                                        @if($investment->main_image)
                                            <div class="mb-2">
                                                <img src="{{ asset('public/'.$investment->main_image) }}"
                                                     alt="Main Image" class="rounded"
                                                     height="80" style="object-fit:cover;">
                                                <small class="text-muted d-block mt-1">Current image</small>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="main_image" accept="image/*">
                                        <div class="form-text">Leave empty to keep current image.</div>
                                    </div>

                                    {{-- Flags --}}
                                    <div class="col-md-6 d-flex align-items-center gap-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_featured" id="is_featured"
                                                   {{ old('is_featured', $investment->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_featured">Featured</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_active" id="is_active"
                                                   {{ old('is_active', $investment->is_active) ? 'checked' : '' }}>
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
                                Update Investment
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
@include('admin.include.footer')