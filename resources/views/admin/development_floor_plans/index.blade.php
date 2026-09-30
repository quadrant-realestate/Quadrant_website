@include('admin.include.header')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Floor Plans</h3>
            <p class="text-muted mb-0">{{ $development->title }}</p>
        </div>
        <div>
            <a href="{{ route('admin.developments.edit', $development->id) }}" class="btn btn-outline-secondary">&larr; Back to Development</a>
            <a href="{{ route('admin.development_floor_plans.create', $development->id) }}" class="btn btn-primary">+ Add Floor Plan</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="100">Image</th>
                        <th>Unit Type</th>
                        <th>Size</th>
                        <th>PDF</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($floorPlans as $plan)
                        <tr>
                            <td>
                                @if($plan->image)
                                    <img src="{{ URL::to('') }}/public/{{ $plan->image }}" height="50">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $plan->unit_type }}</td>
                            <td>{{ $plan->size_sqft ?? '—' }}</td>
                            <td>
                                @if($plan->pdf_file)
                                    <a href="{{ URL::to('') }}/public/{{ $plan->pdf_file }}" target="_blank">View PDF</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $plan->sort_order }}</td>
                            <td>
                                <a href="{{ route('admin.development_floor_plans.edit', [$development->id, $plan->id]) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('admin.development_floor_plans.destroy', [$development->id, $plan->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this floor plan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No floor plans added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('admin.include.footer')