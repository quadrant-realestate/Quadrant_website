<div class="form-group">
    <label>Title</label>
    <input type="text" name="title" class="form-control" required
           value="{{ old('title', $post->title ?? '') }}">
</div>
 
<div class="form-group">
    <label>Category</label>
    <select name="category" class="form-control" required>
        @php
            $cats = [
                'off-plan'        => 'Off-Plan',
                'market-insights' => 'Market Insights',
                'communities'     => 'Communities',
                'buyer-guides'    => 'Buyer Guides',
                'quadrant-view'   => 'Quadrant View',
            ];
            $selectedCat = old('category', $post->category ?? 'off-plan');
        @endphp
        @foreach($cats as $value => $label)
            <option value="{{ $value }}" {{ $selectedCat == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>
 
<div class="form-group">
    <label>Author</label>
    <input type="text" name="author" class="form-control"
           value="{{ old('author', $post->author ?? '') }}" placeholder="e.g. Quadrant Editorial Team">
</div>
 
<div class="form-group">
    <label>Excerpt <small class="text-muted">(short summary shown on the listing card)</small></label>
    <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
</div>
 
<div class="form-group">
    <label>Body</label>
    <textarea name="body" id="body" class="form-control" rows="12">{{ old('body', $post->body ?? '') }}</textarea>
</div>
 
<div class="form-group">
    <label>Main Image</label>
    <input type="file" name="main_image" class="form-control">
    @if(!empty($post->main_image))
        <img src="{{ URL::to('') }}/public/{{ $post->main_image }}" height="60" class="mt-2 d-block">
    @endif
</div>
 
<div class="form-group">
    <label>Meta Title <small class="text-muted">(SEO — leave blank to use article title)</small></label>
    <input type="text" name="meta_title" class="form-control"
           value="{{ old('meta_title', $post->meta_title ?? '') }}">
</div>
 
<div class="form-group">
    <label>Meta Description <small class="text-muted">(SEO)</small></label>
    <textarea name="meta_desc" class="form-control" rows="2">{{ old('meta_desc', $post->meta_desc ?? '') }}</textarea>
</div>
 
<div class="form-group form-check">
    <input type="checkbox" name="is_published" id="is_published" class="form-check-input"
           value="1" {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_published">Publish this article</label>
</div>