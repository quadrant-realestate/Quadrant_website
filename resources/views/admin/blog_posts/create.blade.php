@include('admin.include.header')
<div class="container-fluid">
    <h3 class="mb-4">New Article</h3>
 
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
            <form action="{{ route('admin.blog_posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.blog_posts._form', ['post' => null])
                <button type="submit" class="btn btn-primary">Create Article</button>
                <a href="{{ route('admin.blog_posts.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@include('admin.include.footer')