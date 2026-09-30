@include('admin.include.header')


<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Insights / Articles</h3>
        <a href="{{ route('admin.blog_posts.create') }}" class="btn btn-primary">+ New Article</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ ucwords(str_replace('-', ' ', $post->category)) }}</td>
                            <td>
                                @if($post->is_published)
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d M Y') : '—' }}</td>
                            <td>
                                <a href="{{ route('admin.blog_posts.edit', $post->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('admin.blog_posts.destroy', $post->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this article?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No articles yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $posts->links() }}
        </div>
    </div>
</div>
@include('admin.include.footer')