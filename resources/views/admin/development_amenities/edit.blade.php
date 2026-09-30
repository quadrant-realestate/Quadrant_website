@include('admin.include.header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Amenities</h3>
            <p class="text-muted mb-0">{{ $development->title }}</p>
        </div>
        <a href="{{ route('admin.developments.edit', $development->id) }}" class="btn btn-outline-secondary">&larr; Back to Development</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.development_amenities.update', $development->id) }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    $grouped = $allAmenities->groupBy('category');
                @endphp

                @forelse($grouped as $category => $items)
                    <h6 class="mt-3 mb-2">{{ $category ?: 'General' }}</h6>
                    <div class="row mb-3">
                        @foreach($items as $amenity)
                            <div class="col-md-3 col-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="amenity_ids[]" value="{{ $amenity->id }}"
                                           id="amenity_{{ $amenity->id }}"
                                           {{ in_array($amenity->id, $selectedAmenityIds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amenity_{{ $amenity->id }}">
                                        {{ $amenity->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <p class="text-muted">No amenities exist yet. Add some under <a href="{{ route('admin.amenities.create') }}">Amenities</a> first.</p>
                @endforelse

                <button type="submit" class="btn btn-primary mt-3">Save Amenities</button>
            </form>
        </div>
    </div>
</div>
@include('admin.include.footer')