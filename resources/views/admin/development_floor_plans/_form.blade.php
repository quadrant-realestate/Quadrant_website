{{--
    SHARED FORM PARTIAL for create.blade.php and edit.blade.php
    Save as: resources/views/admin/development_floor_plans/_form.blade.php
--}}

<div class="form-group">
    <label>Unit Type <small class="text-muted">(e.g. "1 Bedroom", "2 Bedroom", "Penthouse")</small></label>
    <input type="text" name="unit_type" class="form-control" required
           value="{{ old('unit_type', $floorPlan->unit_type ?? '') }}">
</div>

<div class="form-group">
    <label>Size (sq ft) <small class="text-muted">(e.g. "750 sq ft")</small></label>
    <input type="text" name="size_sqft" class="form-control"
           value="{{ old('size_sqft', $floorPlan->size_sqft ?? '') }}">
</div>

<div class="form-group">
    <label>Floor Plan Image</label>
    <input type="file" name="image" class="form-control">
    @if(!empty($floorPlan->image))
        <img src="{{ URL::to('') }}/public/{{ $floorPlan->image }}" height="80" class="mt-2 d-block">
    @endif
</div>

<div class="form-group">
    <label>Downloadable PDF</label>
    <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
    @if(!empty($floorPlan->pdf_file))
        <a href="{{ URL::to('') }}/public/{{ $floorPlan->pdf_file }}" target="_blank" class="d-block mt-2">View current PDF</a>
    @endif
</div>