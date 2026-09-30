<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropertyTypeController extends Controller
{
    // ============================================================
    // INDEX — List all property types
    // ============================================================
    public function index()
    {
        $types = DB::table('property_types')
                    ->orderBy('sort_order', 'asc')
                    ->get();

        return view('admin.property_types.index', compact('types'));
    }

    // ============================================================
    // CREATE — Show add form
    // ============================================================
    public function create()
    {
        return view('admin.property_types.create');
    }

    // ============================================================
    // STORE — Save new property type
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:80|unique:property_types,name',
            'sort_order' => 'nullable|integer',
        ]);

        DB::table('property_types')->insert([
            'name'       => trim($request->name),
            'slug'       => Str::slug($request->name),
            'is_active'  => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.property-types.index')
                         ->with('success', 'Property type added successfully.');
    }

    // ============================================================
    // EDIT — Show edit form
    // ============================================================
    public function edit($id)
    {
        $type = DB::table('property_types')->where('id', $id)->first();

        if (!$type) {
            return redirect()->route('admin.property-types.index')
                             ->with('error', 'Property type not found.');
        }

        return view('admin.property_types.edit', compact('type'));
    }

    // ============================================================
    // UPDATE — Save changes
    // ============================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'       => 'required|string|max:80|unique:property_types,name,' . $id,
            'sort_order' => 'nullable|integer',
        ]);

        DB::table('property_types')->where('id', $id)->update([
            'name'       => trim($request->name),
            'slug'       => Str::slug($request->name),
            'is_active'  => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.property-types.index')
                         ->with('success', 'Property type updated successfully.');
    }

    // ============================================================
    // DELETE — Remove property type
    // ============================================================
    public function delete($id)
    {
        // Check if any property is using this type
        $inUse = DB::table('properties')->where('property_type_id', $id)->count();

        if ($inUse > 0) {
            return redirect()->route('admin.property-types.index')
                             ->with('error', 'Cannot delete. ' . $inUse . ' properties are using this type.');
        }

        DB::table('property_types')->where('id', $id)->delete();

        return redirect()->route('admin.property-types.index')
                         ->with('success', 'Property type deleted successfully.');
    }

    // ============================================================
    // TOGGLE STATUS — Active / Inactive
    // ============================================================
    public function toggleStatus($id)
    {
        $type = DB::table('property_types')->where('id', $id)->first();

        if (!$type) {
            return redirect()->route('admin.property-types.index')
                             ->with('error', 'Property type not found.');
        }

        DB::table('property_types')->where('id', $id)->update([
            'is_active' => $type->is_active ? 0 : 1,
        ]);

        return redirect()->route('admin.property-types.index')
                         ->with('success', 'Status updated successfully.');
    }
}