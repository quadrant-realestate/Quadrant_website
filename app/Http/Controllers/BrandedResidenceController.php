<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandedResidenceController extends Controller
{
    // ============================================================
    // INDEX
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('branded_residences')
                    ->leftJoin('communities', 'branded_residences.community_id', '=', 'communities.id')
                    ->leftJoin('properties', 'branded_residences.property_id', '=', 'properties.id')
                    ->select(
                        'branded_residences.*',
                        'communities.name as community_name',
                        'properties.title as property_title'
                    );

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('branded_residences.title', 'like', '%' . $request->search . '%')
                  ->orWhere('branded_residences.brand_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by community
        if ($request->community_id) {
            $query->where('branded_residences.community_id', $request->community_id);
        }

        $residences  = $query->orderBy('branded_residences.created_at', 'desc')->get();
        $communities = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('admin.branded_residences.index', compact('residences', 'communities'));
    }

    // ============================================================
    // CREATE
    // ============================================================
    public function create()
    {
        $communities = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();
        $properties  = DB::table('properties')
                          ->where('status', 'active')
                          ->orderBy('title')
                          ->get(['id', 'title', 'reference_no']);

        return view('admin.branded_residences.create', compact('communities', 'properties'));
    }

    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'brand_name'   => 'required|string|max:150',
            'title'        => 'required|string|max:255|unique:branded_residences,title',
            'community_id' => 'nullable|exists:communities,id',
            'property_id'  => 'nullable|exists:properties,id',
            'price_from'   => 'nullable|numeric',
        ]);

        // Main Image
        $mainImage = null;
        if ($request->hasFile('main_image')) {
            $file      = $request->file('main_image');
            $name      = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/branded_residences'), $name);
            $mainImage = 'uploads/branded_residences/' . $name;
        }

        // Brand Logo
        $brandLogo = null;
        if ($request->hasFile('brand_logo')) {
            $file      = $request->file('brand_logo');
            $name      = time() . '_logo_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/branded_residences/logos'), $name);
            $brandLogo = 'uploads/branded_residences/logos/' . $name;
        }

        DB::table('branded_residences')->insert([
            'brand_name'   => trim($request->brand_name),
            'brand_logo'   => $brandLogo,
            'property_id'  => $request->property_id,
            'title'        => trim($request->title),
            'slug'         => Str::slug($request->title),
            'description'  => $request->description,
            'community_id' => $request->community_id,
            'price_from'   => $request->price_from,
            'main_image'   => $mainImage,
            'is_featured'  => $request->has('is_featured') ? 1 : 0,
            'is_active'    => $request->has('is_active') ? 1 : 0,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.branded-residences.index')
                         ->with('success', 'Branded residence added successfully.');
    }

    // ============================================================
    // EDIT
    // ============================================================
    public function edit($id)
    {
        $residence = DB::table('branded_residences')->where('id', $id)->first();

        if (!$residence) {
            return redirect()->route('admin.branded-residences.index')
                             ->with('error', 'Branded residence not found.');
        }

        $communities = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();
        $properties  = DB::table('properties')
                          ->where('status', 'active')
                          ->orderBy('title')
                          ->get(['id', 'title', 'reference_no']);

        return view('admin.branded_residences.edit', compact('residence', 'communities', 'properties'));
    }

    // ============================================================
    // UPDATE
    // ============================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'brand_name'   => 'required|string|max:150',
            'title'        => 'required|string|max:255|unique:branded_residences,title,' . $id,
            'community_id' => 'nullable|exists:communities,id',
            'property_id'  => 'nullable|exists:properties,id',
            'price_from'   => 'nullable|numeric',
        ]);

        $residence = DB::table('branded_residences')->where('id', $id)->first();

        // Main Image
        $mainImage = $residence->main_image;
        if ($request->hasFile('main_image')) {
            if ($mainImage && file_exists(public_path($mainImage))) {
                unlink(public_path($mainImage));
            }
            $file      = $request->file('main_image');
            $name      = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/branded_residences'), $name);
            $mainImage = 'uploads/branded_residences/' . $name;
        }

        // Brand Logo
        $brandLogo = $residence->brand_logo;
        if ($request->hasFile('brand_logo')) {
            if ($brandLogo && file_exists(public_path($brandLogo))) {
                unlink(public_path($brandLogo));
            }
            $file      = $request->file('brand_logo');
            $name      = time() . '_logo_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/branded_residences/logos'), $name);
            $brandLogo = 'uploads/branded_residences/logos/' . $name;
        }

        DB::table('branded_residences')->where('id', $id)->update([
            'brand_name'   => trim($request->brand_name),
            'brand_logo'   => $brandLogo,
            'property_id'  => $request->property_id,
            'title'        => trim($request->title),
            'slug'         => Str::slug($request->title),
            'description'  => $request->description,
            'community_id' => $request->community_id,
            'price_from'   => $request->price_from,
            'main_image'   => $mainImage,
            'is_featured'  => $request->has('is_featured') ? 1 : 0,
            'is_active'    => $request->has('is_active') ? 1 : 0,
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.branded-residences.index')
                         ->with('success', 'Branded residence updated successfully.');
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function delete($id)
    {
        $residence = DB::table('branded_residences')->where('id', $id)->first();

        if (!$residence) {
            return redirect()->route('admin.branded-residences.index')
                             ->with('error', 'Branded residence not found.');
        }

        if ($residence->main_image && file_exists(public_path($residence->main_image))) {
            unlink(public_path($residence->main_image));
        }

        if ($residence->brand_logo && file_exists(public_path($residence->brand_logo))) {
            unlink(public_path($residence->brand_logo));
        }

        DB::table('branded_residences')->where('id', $id)->delete();

        return redirect()->route('admin.branded-residences.index')
                         ->with('success', 'Branded residence deleted successfully.');
    }

    // ============================================================
    // TOGGLE FEATURED
    // ============================================================
    public function toggleFeatured($id)
    {
        $residence = DB::table('branded_residences')->where('id', $id)->first();

        DB::table('branded_residences')->where('id', $id)->update([
            'is_featured' => $residence->is_featured ? 0 : 1,
            'updated_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Featured status updated.');
    }

    // ============================================================
    // TOGGLE STATUS
    // ============================================================
    public function toggleStatus($id)
    {
        $residence = DB::table('branded_residences')->where('id', $id)->first();

        DB::table('branded_residences')->where('id', $id)->update([
            'is_active'  => $residence->is_active ? 0 : 1,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}