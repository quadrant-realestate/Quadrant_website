<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevelopmentAmenityController extends Controller
{
    // ============================================================
    // EDIT — checkbox list of all amenities, pre-checked for this development
    // ============================================================
    public function edit($developmentId)
    {
        $development = DB::table('developments')->where('id', $developmentId)->first();
        if (!$development) abort(404);

        $allAmenities = DB::table('amenities')->orderBy('category')->orderBy('name')->get();

        $selectedAmenityIds = DB::table('development_amenities')
                                 ->where('development_id', $developmentId)
                                 ->pluck('amenity_id')
                                 ->toArray();

        return view('admin.development_amenities.edit', compact(
            'development',
            'allAmenities',
            'selectedAmenityIds'
        ));
    }

    // ============================================================
    // UPDATE — sync selected amenities for this development
    // ============================================================
    public function update(Request $request, $developmentId)
    {
        $development = DB::table('developments')->where('id', $developmentId)->first();
        if (!$development) abort(404);

        $selectedIds = $request->input('amenity_ids', []);

        // Remove existing links, then re-insert selected ones
        DB::table('development_amenities')->where('development_id', $developmentId)->delete();

        $rows = [];
        foreach ($selectedIds as $amenityId) {
            $rows[] = [
                'development_id' => $developmentId,
                'amenity_id'     => $amenityId,
            ];
        }
        if (!empty($rows)) {
            DB::table('development_amenities')->insert($rows);
        }

        return redirect()->route('admin.development_amenities.edit', $developmentId)
                          ->with('success', 'Amenities updated successfully.');
    }
}