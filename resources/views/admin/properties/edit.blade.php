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
                                    <h4 class="mb-4 mb-sm-0 card-title">Edit Property</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Properties</span>
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

            <form action="{{ route('admin.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ===== SECTION 1: Basic Info ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:document-text-line-duotone" class="me-2"></iconify-icon>
                                    Basic Information
                                    <small class="text-muted ms-3 fw-normal fs-6">Ref: {{ $property->reference_no }}</small>
                                </h5>
                                <div class="row">

                                    <div class="col-md-8">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="title"
                                                   placeholder="Property Title"
                                                   value="{{ old('title', $property->title) }}" required>
                                            <label>Property Title *</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="listing_type" required>
                                                <option value="sale"          {{ old('listing_type', $property->listing_type) == 'sale'          ? 'selected' : '' }}>For Sale</option>
                                                <option value="rent"          {{ old('listing_type', $property->listing_type) == 'rent'          ? 'selected' : '' }}>For Rent</option>
                                                <option value="off_plan"      {{ old('listing_type', $property->listing_type) == 'off_plan'      ? 'selected' : '' }}>Off Plan</option>
                                                <option value="international" {{ old('listing_type', $property->listing_type) == 'international' ? 'selected' : '' }}>International</option>
                                                <option value="private"       {{ old('listing_type', $property->listing_type) == 'private'       ? 'selected' : '' }}>Private Listing</option>
                                            </select>
                                            <label>Listing Type *</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="property_type_id">
                                                <option value="">Select Type</option>
                                                @foreach($property_types as $type)
                                                    <option value="{{ $type->id }}" {{ old('property_type_id', $property->property_type_id) == $type->id ? 'selected' : '' }}>
                                                        {{ $type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Property Type</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="community_id">
                                                <option value="">Select Community</option>
                                                @foreach($communities as $community)
                                                    <option value="{{ $community->id }}" {{ old('community_id', $property->community_id) == $community->id ? 'selected' : '' }}>
                                                        {{ $community->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Community</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="status">
                                                <option value="active"   {{ old('status', $property->status) == 'active'   ? 'selected' : '' }}>Active</option>
                                                <option value="sold"     {{ old('status', $property->status) == 'sold'     ? 'selected' : '' }}>Sold</option>
                                                <option value="rented"   {{ old('status', $property->status) == 'rented'   ? 'selected' : '' }}>Rented</option>
                                                <option value="reserved" {{ old('status', $property->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                                <option value="inactive" {{ old('status', $property->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            <label>Status</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="short_description"
                                                      placeholder="Short Description"
                                                      style="height:80px">{{ old('short_description', $property->short_description) }}</textarea>
                                            <label>Short Description</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Full Description</label>
                                        <textarea class="form-control" name="description" rows="6"
                                                  placeholder="Full property description...">{{ old('description', $property->description) }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 2: Property Details ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:ruler-cross-pen-line-duotone" class="me-2"></iconify-icon>
                                    Property Details
                                </h5>
                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="bedrooms"
                                                   placeholder="Bedrooms"
                                                   value="{{ old('bedrooms', $property->bedrooms) }}" min="0">
                                            <label>Bedrooms</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="bathrooms"
                                                   placeholder="Bathrooms"
                                                   value="{{ old('bathrooms', $property->bathrooms) }}" min="0">
                                            <label>Bathrooms</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="area_sqft"
                                                   placeholder="Area SqFt"
                                                   value="{{ old('area_sqft', $property->area_sqft) }}" step="0.01">
                                            <label>Area (SqFt)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="plot_area_sqft"
                                                   placeholder="Plot Area"
                                                   value="{{ old('plot_area_sqft', $property->plot_area_sqft) }}" step="0.01">
                                            <label>Plot Area (SqFt)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="parking_spaces"
                                                   placeholder="Parking"
                                                   value="{{ old('parking_spaces', $property->parking_spaces) }}" min="0">
                                            <label>Parking</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="furnished">
                                                <option value="">Select</option>
                                                <option value="furnished"      {{ old('furnished', $property->furnished) == 'furnished'      ? 'selected' : '' }}>Furnished</option>
                                                <option value="semi_furnished" {{ old('furnished', $property->furnished) == 'semi_furnished' ? 'selected' : '' }}>Semi Furnished</option>
                                                <option value="unfurnished"    {{ old('furnished', $property->furnished) == 'unfurnished'    ? 'selected' : '' }}>Unfurnished</option>
                                            </select>
                                            <label>Furnished</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="floor_number"
                                                   placeholder="Floor Number"
                                                   value="{{ old('floor_number', $property->floor_number) }}">
                                            <label>Floor Number</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="total_floors"
                                                   placeholder="Total Floors"
                                                   value="{{ old('total_floors', $property->total_floors) }}">
                                            <label>Total Floors</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 3: Pricing ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:wallet-money-line-duotone" class="me-2"></iconify-icon>
                                    Pricing
                                </h5>
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="price"
                                                   placeholder="Price"
                                                   value="{{ old('price', $property->price) }}" step="0.01">
                                            <label>Price</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="price_currency">
                                                <option value="AED" {{ old('price_currency', $property->price_currency) == 'AED' ? 'selected' : '' }}>AED</option>
                                                <option value="USD" {{ old('price_currency', $property->price_currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="GBP" {{ old('price_currency', $property->price_currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                                <option value="EUR" {{ old('price_currency', $property->price_currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            </select>
                                            <label>Currency</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="service_charge"
                                                   placeholder="Service Charge"
                                                   value="{{ old('service_charge', $property->service_charge) }}" step="0.01">
                                            <label>Service Charge (AED/yr)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3 d-flex align-items-center">
                                        <div class="form-check form-switch ms-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="price_on_request" id="price_on_request"
                                                   {{ old('price_on_request', $property->price_on_request) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="price_on_request">Price on Request</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 4: Location ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:map-point-wave-line-duotone" class="me-2"></iconify-icon>
                                    Location
                                </h5>
                                <div class="row">

                                    <div class="col-md-8">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="address"
                                                   placeholder="Address"
                                                   value="{{ old('address', $property->address) }}">
                                            <label>Address</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="city"
                                                   placeholder="City"
                                                   value="{{ old('city', $property->city) }}">
                                            <label>City</label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="country"
                                                   placeholder="Country"
                                                   value="{{ old('country', $property->country) }}">
                                            <label>Country</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="latitude"
                                                   placeholder="Latitude"
                                                   value="{{ old('latitude', $property->latitude) }}">
                                            <label>Latitude</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="longitude"
                                                   placeholder="Longitude"
                                                   value="{{ old('longitude', $property->longitude) }}">
                                            <label>Longitude</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="google_maps_link"
                                                   placeholder="Google Maps Link"
                                                   value="{{ old('google_maps_link', $property->google_maps_link) }}">
                                            <label>Google Maps Link</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 5: Media ===== --}}
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
                                        @if($property->main_image)
                                            <div class="mb-2">
                                                <img src="{{ asset('public/'.$property->main_image) }}"
                                                     alt="Main Image" class="rounded"
                                                     height="100" style="object-fit:cover;">
                                                <small class="text-muted d-block mt-1">Current main image</small>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="main_image" accept="image/*">
                                        <div class="form-text">Leave empty to keep current image.</div>
                                    </div>

                                    {{-- Gallery --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Add More Gallery Images</label>
                                        <input class="form-control" type="file" name="gallery[]" accept="image/*" multiple>
                                        <div class="form-text">These will be added to the existing gallery.</div>
                                    </div>

                                    {{-- Existing Gallery --}}
                                    @if(count($gallery) > 0)
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-semibold">Existing Gallery</label>
                                        <div class="d-flex flex-wrap gap-3">
                                            @foreach($gallery as $img)
                                            <div class="position-relative">
                                                <img src="{{ asset('public/'.$img->image_path) }}"
                                                     alt="Gallery"
                                                     class="rounded"
                                                     width="100" height="80"
                                                     style="object-fit:cover;">
                                                <a href="{{ route('admin.properties.gallery.delete', $img->id) }}"
                                                   class="position-absolute top-0 end-0 btn btn-danger btn-sm p-0 px-1"
                                                   style="font-size:10px;"
                                                   onclick="return confirm('Delete this image?')">
                                                   &times;
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Floor Plan --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Floor Plan Image</label>
                                        @if($property->floor_plan_image)
                                            <div class="mb-2">
                                                <img src="{{ asset('public/'.$property->floor_plan_image) }}"
                                                     alt="Floor Plan" class="rounded"
                                                     height="80" style="object-fit:cover;">
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="floor_plan_image" accept="image/*">
                                    </div>

                                    {{-- Brochure --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Brochure PDF</label>
                                        @if($property->brochure_pdf)
                                            <div class="mb-2">
                                                <a href="{{ asset('public/'.$property->brochure_pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <iconify-icon icon="solar:file-download-line-duotone"></iconify-icon>
                                                    Current Brochure
                                                </a>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="brochure_pdf" accept=".pdf">
                                    </div>

                                    {{-- Video URL --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="video_url"
                                                   placeholder="Video URL"
                                                   value="{{ old('video_url', $property->video_url) }}">
                                            <label>Video URL (YouTube/Vimeo)</label>
                                        </div>
                                    </div>

                                    {{-- Virtual Tour --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="virtual_tour_url"
                                                   placeholder="Virtual Tour URL"
                                                   value="{{ old('virtual_tour_url', $property->virtual_tour_url) }}">
                                            <label>Virtual Tour URL</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 6: Amenities ===== --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:star-shine-line-duotone" class="me-2"></iconify-icon>
                                    Amenities
                                </h5>

                                @php
                                    $groupedAmenities = $amenities->groupBy('category');
                                @endphp

                                @foreach($groupedAmenities as $category => $items)
                                    <h6 class="text-muted fw-semibold mb-3 mt-3">{{ $category }}</h6>
                                    <div class="row mb-3">
                                        @foreach($items as $amenity)
                                            <div class="col-md-3 col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           name="amenities[]"
                                                           value="{{ $amenity->id }}"
                                                           id="amenity_{{ $amenity->id }}"
                                                           {{ in_array($amenity->id, old('amenities', $selectedAmenities)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="amenity_{{ $amenity->id }}">
                                                        @if($amenity->icon)
                                                            <iconify-icon icon="{{ $amenity->icon }}" class="me-1"></iconify-icon>
                                                        @endif
                                                        {{ $amenity->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION 7: SEO & Flags ===== --}}
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
                                                   value="{{ old('meta_title', $property->meta_title) }}">
                                            <label>Meta Title</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="meta_keywords"
                                                   placeholder="Meta Keywords"
                                                   value="{{ old('meta_keywords', $property->meta_keywords) }}">
                                            <label>Meta Keywords</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="meta_desc"
                                                      placeholder="Meta Description"
                                                      style="height:80px">{{ old('meta_desc', $property->meta_desc) }}</textarea>
                                            <label>Meta Description</label>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex flex-wrap gap-4 mt-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_featured" id="is_featured"
                                                   {{ old('is_featured', $property->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_featured">Featured</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_exclusive" id="is_exclusive"
                                                   {{ old('is_exclusive', $property->is_exclusive) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_exclusive">Exclusive</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="is_new" id="is_new"
                                                   {{ old('is_new', $property->is_new) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_new">New</label>
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
                            <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4 hstack gap-2">
                                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                Update Property
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
@include('admin.include.footer')