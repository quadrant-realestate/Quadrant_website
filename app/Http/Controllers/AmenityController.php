<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AmenityController extends Controller
{
    // ============================================================
    // INDEX
    // ============================================================
    public function index()
    {
        $amenities = DB::table('amenities')
                        ->orderBy('category', 'asc')
                        ->orderBy('sort_order', 'asc')
                        ->get();

        return view('admin.amenities.index', compact('amenities'));
    }

    // ============================================================
    // CREATE
    // ============================================================
    public function create()
    {
        return view('admin.amenities.create');
    }

    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100|unique:amenities,name',
            'category' => 'required|string|max:80',
        ]);

        DB::table('amenities')->insert([
            'name'       => trim($request->name),
            'icon'       => trim($request->icon) ?? null,
            'category'   => trim($request->category),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.amenities.index')
                         ->with('success', 'Amenity added successfully.');
    }

    // ============================================================
    // EDIT
    // ============================================================
    public function edit($id)
    {
        $amenity = DB::table('amenities')->where('id', $id)->first();

        if (!$amenity) {
            return redirect()->route('admin.amenities.index')
                             ->with('error', 'Amenity not found.');
        }

        return view('admin.amenities.edit', compact('amenity'));
    }

    // ============================================================
    // UPDATE
    // ============================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:100|unique:amenities,name,' . $id,
            'category' => 'required|string|max:80',
        ]);

        DB::table('amenities')->where('id', $id)->update([
            'name'       => trim($request->name),
            'icon'       => trim($request->icon) ?? null,
            'category'   => trim($request->category),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.amenities.index')
                         ->with('success', 'Amenity updated successfully.');
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function delete($id)
    {
        // Check if any property is using this amenity
        $inUse = DB::table('property_amenities')->where('amenity_id', $id)->count();

        if ($inUse > 0) {
            return redirect()->route('admin.amenities.index')
                             ->with('error', 'Cannot delete. ' . $inUse . ' properties are using this amenity.');
        }

        DB::table('amenities')->where('id', $id)->delete();

        return redirect()->route('admin.amenities.index')
                         ->with('success', 'Amenity deleted successfully.');
    }
}