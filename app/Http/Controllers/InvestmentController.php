<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvestmentController extends Controller
{
    // ============================================================
    // INDEX
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('investments')
                    ->leftJoin('communities', 'investments.community_id', '=', 'communities.id')
                    ->leftJoin('properties', 'investments.property_id', '=', 'properties.id')
                    ->select(
                        'investments.*',
                        'communities.name as community_name',
                        'properties.title as property_title'
                    );

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('investments.title', 'like', '%' . $request->search . '%')
                  ->orWhere('investments.investment_type', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by community
        if ($request->community_id) {
            $query->where('investments.community_id', $request->community_id);
        }

        $investments = $query->orderBy('investments.created_at', 'desc')->get();
        $communities = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('admin.investments.index', compact('investments', 'communities'));
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

        return view('admin.investments.create', compact('communities', 'properties'));
    }

    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255|unique:investments,title',
            'community_id'    => 'nullable|exists:communities,id',
            'property_id'     => 'nullable|exists:properties,id',
            'price'           => 'nullable|numeric',
            'roi_percentage'  => 'nullable|numeric',
            'gross_yield'     => 'nullable|numeric',
            'net_yield'       => 'nullable|numeric',
            'annual_rental'   => 'nullable|numeric',
        ]);

        // Main Image
        $mainImage = null;
        if ($request->hasFile('main_image')) {
            $file      = $request->file('main_image');
            $name      = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/investments'), $name);
            $mainImage = 'uploads/investments/' . $name;
        }

        DB::table('investments')->insert([
            'title'           => trim($request->title),
            'slug'            => Str::slug($request->title),
            'property_id'     => $request->property_id,
            'community_id'    => $request->community_id,
            'description'     => $request->description,
            'investment_type' => $request->investment_type,
            'price'           => $request->price,
            'price_currency'  => $request->price_currency ?? 'AED',
            'roi_percentage'  => $request->roi_percentage,
            'gross_yield'     => $request->gross_yield,
            'net_yield'       => $request->net_yield,
            'annual_rental'   => $request->annual_rental,
            'main_image'      => $mainImage,
            'is_featured'     => $request->has('is_featured') ? 1 : 0,
            'is_active'       => $request->has('is_active') ? 1 : 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return redirect()->route('admin.investments.index')
                         ->with('success', 'Investment listing added successfully.');
    }

    // ============================================================
    // EDIT
    // ============================================================
    public function edit($id)
    {
        $investment = DB::table('investments')->where('id', $id)->first();

        if (!$investment) {
            return redirect()->route('admin.investments.index')
                             ->with('error', 'Investment not found.');
        }

        $communities = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();
        $properties  = DB::table('properties')
                          ->where('status', 'active')
                          ->orderBy('title')
                          ->get(['id', 'title', 'reference_no']);

        return view('admin.investments.edit', compact('investment', 'communities', 'properties'));
    }

    // ============================================================
    // UPDATE
    // ============================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'          => 'required|string|max:255|unique:investments,title,' . $id,
            'community_id'   => 'nullable|exists:communities,id',
            'property_id'    => 'nullable|exists:properties,id',
            'price'          => 'nullable|numeric',
            'roi_percentage' => 'nullable|numeric',
            'gross_yield'    => 'nullable|numeric',
            'net_yield'      => 'nullable|numeric',
            'annual_rental'  => 'nullable|numeric',
        ]);

        $investment = DB::table('investments')->where('id', $id)->first();

        // Handle main image
        $mainImage = $investment->main_image;
        if ($request->hasFile('main_image')) {
            if ($mainImage && file_exists(public_path($mainImage))) {
                unlink(public_path($mainImage));
            }
            $file      = $request->file('main_image');
            $name      = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/investments'), $name);
            $mainImage = 'uploads/investments/' . $name;
        }

        DB::table('investments')->where('id', $id)->update([
            'title'           => trim($request->title),
            'slug'            => Str::slug($request->title),
            'property_id'     => $request->property_id,
            'community_id'    => $request->community_id,
            'description'     => $request->description,
            'investment_type' => $request->investment_type,
            'price'           => $request->price,
            'price_currency'  => $request->price_currency ?? 'AED',
            'roi_percentage'  => $request->roi_percentage,
            'gross_yield'     => $request->gross_yield,
            'net_yield'       => $request->net_yield,
            'annual_rental'   => $request->annual_rental,
            'main_image'      => $mainImage,
            'is_featured'     => $request->has('is_featured') ? 1 : 0,
            'is_active'       => $request->has('is_active') ? 1 : 0,
            'updated_at'      => now(),
        ]);

        return redirect()->route('admin.investments.index')
                         ->with('success', 'Investment listing updated successfully.');
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function delete($id)
    {
        $investment = DB::table('investments')->where('id', $id)->first();

        if (!$investment) {
            return redirect()->route('admin.investments.index')
                             ->with('error', 'Investment not found.');
        }

        if ($investment->main_image && file_exists(public_path($investment->main_image))) {
            unlink(public_path($investment->main_image));
        }

        DB::table('investments')->where('id', $id)->delete();

        return redirect()->route('admin.investments.index')
                         ->with('success', 'Investment deleted successfully.');
    }

    // ============================================================
    // TOGGLE FEATURED
    // ============================================================
    public function toggleFeatured($id)
    {
        $investment = DB::table('investments')->where('id', $id)->first();

        DB::table('investments')->where('id', $id)->update([
            'is_featured' => $investment->is_featured ? 0 : 1,
            'updated_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Featured status updated.');
    }

    // ============================================================
    // TOGGLE STATUS
    // ============================================================
    public function toggleStatus($id)
    {
        $investment = DB::table('investments')->where('id', $id)->first();

        DB::table('investments')->where('id', $id)->update([
            'is_active'  => $investment->is_active ? 0 : 1,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}