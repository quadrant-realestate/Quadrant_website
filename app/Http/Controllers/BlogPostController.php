<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    // ============================================================
    // INDEX — Admin listing
    // ============================================================
    public function index()
    {
        $posts = DB::table('blog_posts')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.blog_posts.index', compact('posts'));
    }

    // ============================================================
    // CREATE / STORE
    // ============================================================
    public function create()
    {
        return view('admin.blog_posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|in:off-plan,market-insights,communities,buyer-guides,quadrant-view',
            'body'     => 'nullable|string',
        ]);

        $slug = Str::slug($request->title);
        // Ensure unique slug
        $originalSlug = $slug;
        $i = 1;
        while (DB::table('blog_posts')->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i;
            $i++;
        }

        $imagePath = null;
        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('uploads/blog');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $imagePath = 'uploads/blog/' . $filename;
        }

        DB::table('blog_posts')->insert([
            'title'        => $request->title,
            'slug'         => $slug,
            'category'     => $request->category,
            'excerpt'      => $request->excerpt,
            'body'         => $request->body,
            'main_image'   => $imagePath,
            'author'       => $request->author,
            'is_published' => $request->has('is_published') ? 1 : 0,
            'published_at' => $request->has('is_published') ? now() : null,
            'meta_title'   => $request->meta_title,
            'meta_desc'    => $request->meta_desc,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.blog_posts.index')->with('success', 'Article created successfully.');
    }

    // ============================================================
    // EDIT / UPDATE
    // ============================================================
    public function edit($id)
    {
        $post = DB::table('blog_posts')->where('id', $id)->first();
        if (!$post) abort(404);
        return view('admin.blog_posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = DB::table('blog_posts')->where('id', $id)->first();
        if (!$post) abort(404);

        $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|in:off-plan,market-insights,communities,buyer-guides,quadrant-view',
        ]);

        $imagePath = $post->main_image;
        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('uploads/blog');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $imagePath = 'uploads/blog/' . $filename;
        }

        DB::table('blog_posts')->where('id', $id)->update([
            'title'        => $request->title,
            'category'     => $request->category,
            'excerpt'      => $request->excerpt,
            'body'         => $request->body,
            'main_image'   => $imagePath,
            'author'       => $request->author,
            'is_published' => $request->has('is_published') ? 1 : 0,
            'published_at' => $request->has('is_published') ? ($post->published_at ?? now()) : null,
            'meta_title'   => $request->meta_title,
            'meta_desc'    => $request->meta_desc,
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.blog_posts.index')->with('success', 'Article updated successfully.');
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function destroy($id)
    {
        DB::table('blog_posts')->where('id', $id)->delete();
        return redirect()->route('admin.blog_posts.index')->with('success', 'Article deleted.');
    }
}