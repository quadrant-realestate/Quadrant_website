<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandedResidenceFrontController extends Controller
{
    // ============================================================
    // INDEX — Branded Residences Listing
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('branded_residences')
                    ->leftJoin('communities', 'branded_residences.community_id', '=', 'communities.id')
                    ->select(
                        'branded_residences.id',
                        'branded_residences.brand_name',
                        'branded_residences.brand_logo',
                        'branded_residences.title',
                        'branded_residences.slug',
                        'branded_residences.main_image',
                        'branded_residences.price_from',
                        'communities.name as community_name'
                    )
                    ->where('branded_residences.is_active', 1);

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('branded_residences.title', 'like', '%' . $request->search . '%')
                  ->orWhere('branded_residences.brand_name', 'like', '%' . $request->search . '%')
                  ->orWhere('communities.name', 'like', '%' . $request->search . '%');
            });
        }

        // Community
        if ($request->community_id) {
            $query->where('branded_residences.community_id', $request->community_id);
        }

        $query->orderBy('branded_residences.is_featured', 'desc')
              ->orderBy('branded_residences.created_at', 'desc');

        $residences  = $query->paginate(12)->withQueryString();
        $communities = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('website.branded-residences.index', compact(
            'residences',
            'communities'
        ));
    }

    // ============================================================
    // SHOW — Single Branded Residence Detail Page
    // ============================================================
    public function show($slug)
    {
        $residence = DB::table('branded_residences')
                        ->leftJoin('communities', 'branded_residences.community_id', '=', 'communities.id')
                        ->leftJoin('properties', 'branded_residences.property_id', '=', 'properties.id')
                        ->select(
                            'branded_residences.*',
                            'communities.name as community_name',
                            'communities.latitude as community_latitude',
                            'communities.longitude as community_longitude',
                            'properties.title as property_title',
                            'properties.slug as property_slug',
                            'properties.area_sqft as property_area_sqft',
                            'properties.bedrooms as property_bedrooms'
                        )
                        ->where('branded_residences.slug', $slug)
                        ->where('branded_residences.is_active', 1)
                        ->first();

        if (!$residence) {
            abort(404);
        }

        // Related branded residences (same community)
        $relatedResidences = DB::table('branded_residences')
                                ->leftJoin('communities', 'branded_residences.community_id', '=', 'communities.id')
                                ->select(
                                    'branded_residences.id',
                                    'branded_residences.title',
                                    'branded_residences.slug',
                                    'branded_residences.main_image',
                                    'communities.name as community_name'
                                )
                                ->where('branded_residences.is_active', 1)
                                ->where('branded_residences.id', '!=', $residence->id)
                                ->limit(4)
                                ->get();

        return view('website.branded-residences.show', compact(
            'residence',
            'relatedResidences'
        ));
    }
}
