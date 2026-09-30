<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    // ============================================================
    // INDEX
    // ============================================================
    public function index()
    {
        $communities = DB::table('communities')
                          ->orderBy('sort_order', 'asc')
                          ->orderBy('name', 'asc')
                          ->get();

        return view('admin.communities.index', compact('communities'));
    }

    // ============================================================
    // CREATE
    // ============================================================
    public function create()
    {
        return view('admin.communities.create');
    }

    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:communities,name',
            'city'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'meta_title'  => 'nullable|string|max:200',
            'meta_desc'   => 'nullable|string|max:300',
        ]);

        // Handle image upload
        $image       = null;
        $bannerImage = null;

        if ($request->hasFile('image')) {
            $file  = $request->file('image');
            $name  = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/communities'), $name);
            $image = 'uploads/communities/' . $name;
        }

        if ($request->hasFile('banner_image')) {
            $file  = $request->file('banner_image');
            $name  = time() . '_banner_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/communities'), $name);
            $bannerImage = 'uploads/communities/' . $name;
        }

        DB::table('communities')->insert([
            'name'         => trim($request->name),
            'slug'         => Str::slug($request->name),
            'description'  => $request->description,
            'image'        => $image,
            'banner_image' => $bannerImage,
            'city'         => $request->city ?? 'Dubai',
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'is_featured'  => $request->has('is_featured') ? 1 : 0,
            'is_active'    => $request->has('is_active') ? 1 : 0,
            'sort_order'   => $request->sort_order ?? 0,
            'meta_title'   => $request->meta_title,
            'meta_desc'    => $request->meta_desc,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.communities.index')
                         ->with('success', 'Community added successfully.');
    }

    // ============================================================
    // EDIT
    // ============================================================
    public function edit($id)
    {
        $community = DB::table('communities')->where('id', $id)->first();

        if (!$community) {
            return redirect()->route('admin.communities.index')
                             ->with('error', 'Community not found.');
        }

        return view('admin.communities.edit', compact('community'));
    }

    // ============================================================
    // UPDATE
    // ============================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:communities,name,' . $id,
            'city'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'meta_title'  => 'nullable|string|max:200',
            'meta_desc'   => 'nullable|string|max:300',
        ]);

        $community = DB::table('communities')->where('id', $id)->first();

        $image       = $community->image;
        $bannerImage = $community->banner_image;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($image && file_exists(public_path($image))) {
                unlink(public_path($image));
            }
            $file  = $request->file('image');
            $name  = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/communities'), $name);
            $image = 'uploads/communities/' . $name;
        }

        if ($request->hasFile('banner_image')) {
            if ($bannerImage && file_exists(public_path($bannerImage))) {
                unlink(public_path($bannerImage));
            }
            $file  = $request->file('banner_image');
            $name  = time() . '_banner_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/communities'), $name);
            $bannerImage = 'uploads/communities/' . $name;
        }

        DB::table('communities')->where('id', $id)->update([
            'name'         => trim($request->name),
            'slug'         => Str::slug($request->name),
            'description'  => $request->description,
            'image'        => $image,
            'banner_image' => $bannerImage,
            'city'         => $request->city ?? 'Dubai',
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'is_featured'  => $request->has('is_featured') ? 1 : 0,
            'is_active'    => $request->has('is_active') ? 1 : 0,
            'sort_order'   => $request->sort_order ?? 0,
            'meta_title'   => $request->meta_title,
            'meta_desc'    => $request->meta_desc,
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.communities.index')
                         ->with('success', 'Community updated successfully.');
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function delete($id)
    {
        $inUse = DB::table('properties')->where('community_id', $id)->count();

        if ($inUse > 0) {
            return redirect()->route('admin.communities.index')
                             ->with('error', 'Cannot delete. ' . $inUse . ' properties are linked to this community.');
        }

        $community = DB::table('communities')->where('id', $id)->first();

        // Delete images from server
        if ($community->image && file_exists(public_path($community->image))) {
            unlink(public_path($community->image));
        }
        if ($community->banner_image && file_exists(public_path($community->banner_image))) {
            unlink(public_path($community->banner_image));
        }

        DB::table('communities')->where('id', $id)->delete();

        return redirect()->route('admin.communities.index')
                         ->with('success', 'Community deleted successfully.');
    }

    // ============================================================
    // TOGGLE STATUS
    // ============================================================
    public function toggleStatus($id)
    {
        $community = DB::table('communities')->where('id', $id)->first();

        if (!$community) {
            return redirect()->route('admin.communities.index')
                             ->with('error', 'Community not found.');
        }

        DB::table('communities')->where('id', $id)->update([
            'is_active'  => $community->is_active ? 0 : 1,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.communities.index')
                         ->with('success', 'Status updated successfully.');
    }

    // ============================================================
    // TOGGLE FEATURED
    // ============================================================
    public function toggleFeatured($id)
    {
        $community = DB::table('communities')->where('id', $id)->first();

        if (!$community) {
            return redirect()->route('admin.communities.index')
                             ->with('error', 'Community not found.');
        }

        DB::table('communities')->where('id', $id)->update([
            'is_featured' => $community->is_featured ? 0 : 1,
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.communities.index')
                         ->with('success', 'Featured status updated successfully.');
    }
}