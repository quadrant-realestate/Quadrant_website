<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevelopmentFloorPlanController extends Controller
{
    // ============================================================
    // INDEX — list floor plans for a specific development
    // ============================================================
    public function index($developmentId)
    {
        $development = DB::table('developments')->where('id', $developmentId)->first();
        if (!$development) abort(404);

        $floorPlans = DB::table('development_floor_plans')
                         ->where('development_id', $developmentId)
                         ->orderBy('sort_order')
                         ->get();

        return view('admin.development_floor_plans.index', compact('development', 'floorPlans'));
    }

    // ============================================================
    // CREATE / STORE
    // ============================================================
    public function create($developmentId)
    {
        $development = DB::table('developments')->where('id', $developmentId)->first();
        if (!$development) abort(404);

        return view('admin.development_floor_plans.create', compact('development'));
    }

    public function store(Request $request, $developmentId)
    {
        $request->validate([
            'unit_type' => 'required|string|max:100',
            'size_sqft' => 'nullable|string|max:50',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('uploads/floor_plans');
            if (!file_exists($destination)) mkdir($destination, 0755, true);
            $file->move($destination, $filename);
            $imagePath = 'uploads/floor_plans/' . $filename;
        }

        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('uploads/floor_plans/pdf');
            if (!file_exists($destination)) mkdir($destination, 0755, true);
            $file->move($destination, $filename);
            $pdfPath = 'uploads/floor_plans/pdf/' . $filename;
        }

        $maxOrder = DB::table('development_floor_plans')
                       ->where('development_id', $developmentId)
                       ->max('sort_order');

        DB::table('development_floor_plans')->insert([
            'development_id' => $developmentId,
            'unit_type'      => $request->unit_type,
            'size_sqft'      => $request->size_sqft,
            'image'          => $imagePath,
            'pdf_file'       => $pdfPath,
            'sort_order'     => ($maxOrder ?? 0) + 1,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('admin.development_floor_plans.index', $developmentId)
                          ->with('success', 'Floor plan added successfully.');
    }

    // ============================================================
    // EDIT / UPDATE
    // ============================================================
    public function edit($developmentId, $id)
    {
        $development = DB::table('developments')->where('id', $developmentId)->first();
        if (!$development) abort(404);

        $floorPlan = DB::table('development_floor_plans')->where('id', $id)->first();
        if (!$floorPlan) abort(404);

        return view('admin.development_floor_plans.edit', compact('development', 'floorPlan'));
    }

    public function update(Request $request, $developmentId, $id)
    {
        $floorPlan = DB::table('development_floor_plans')->where('id', $id)->first();
        if (!$floorPlan) abort(404);

        $request->validate([
            'unit_type' => 'required|string|max:100',
            'size_sqft' => 'nullable|string|max:50',
        ]);

        $imagePath = $floorPlan->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('uploads/floor_plans');
            if (!file_exists($destination)) mkdir($destination, 0755, true);
            $file->move($destination, $filename);
            $imagePath = 'uploads/floor_plans/' . $filename;
        }

        $pdfPath = $floorPlan->pdf_file;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('uploads/floor_plans/pdf');
            if (!file_exists($destination)) mkdir($destination, 0755, true);
            $file->move($destination, $filename);
            $pdfPath = 'uploads/floor_plans/pdf/' . $filename;
        }

        DB::table('development_floor_plans')->where('id', $id)->update([
            'unit_type'  => $request->unit_type,
            'size_sqft'  => $request->size_sqft,
            'image'      => $imagePath,
            'pdf_file'   => $pdfPath,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.development_floor_plans.index', $developmentId)
                          ->with('success', 'Floor plan updated successfully.');
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function destroy($developmentId, $id)
    {
        DB::table('development_floor_plans')->where('id', $id)->delete();
        return redirect()->route('admin.development_floor_plans.index', $developmentId)
                          ->with('success', 'Floor plan deleted.');
    }

    // ============================================================
    // REORDER (optional — drag/drop sort_order update via AJAX)
    // ============================================================
    public function reorder(Request $request, $developmentId)
    {
        $order = $request->input('order', []); // array of floor plan IDs in new order
        foreach ($order as $index => $id) {
            DB::table('development_floor_plans')
              ->where('id', $id)
              ->where('development_id', $developmentId)
              ->update(['sort_order' => $index + 1]);
        }
        return response()->json(['success' => true]);
    }
}