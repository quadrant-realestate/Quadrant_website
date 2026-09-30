@include('admin.include.header')
<div class="container-fluid">
    <h3 class="mb-1">Edit Floor Plan</h3>
    <p class="text-muted mb-4">{{ $development->title }}</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.development_floor_plans.update', [$development->id, $floorPlan->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.development_floor_plans._form', ['floorPlan' => $floorPlan])
                <button type="submit" class="btn btn-primary">Update Floor Plan</button>
                <a href="{{ route('admin.development_floor_plans.index', $development->id) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@include('admin.include.footer')