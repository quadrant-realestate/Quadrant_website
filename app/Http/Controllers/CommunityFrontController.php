<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityFrontController extends Controller
{
    // ============================================================
    // INDEX — Communities Listing
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('communities')
                    ->select(
                        'id',
                        'name',
                        'slug',
                        'image',
                        'city'
                    )
                    ->where('is_active', 1);

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%');
            });
        }

        $query->orderBy('sort_order', 'asc')
              ->orderBy('name', 'asc');

        $communities = $query->paginate(12)->withQueryString();

        return view('website.communities.index', compact('communities'));
    }

    // ============================================================
    // SHOW — Single Community Detail Page
    // ============================================================
    public function show($slug)
    {
        $community = DB::table('communities')
                        ->where('slug', $slug)
                        ->where('is_active', 1)
                        ->first();

        if (!$community) {
            abort(404);
        }

        // Properties in this community
        $properties = DB::table('properties')
                         ->leftJoin('property_types', 'properties.property_type_id', '=', 'property_types.id')
                         ->select(
                             'properties.id',
                             'properties.title',
                             'properties.slug',
                             'properties.listing_type',
                             'properties.price',
                             'properties.price_currency',
                             'properties.price_on_request',
                             'properties.bedrooms',
                             'properties.bathrooms',
                             'properties.area_sqft',
                             'properties.main_image',
                             'property_types.name as type_name'
                         )
                         ->where('properties.status', 'active')
                         ->where('properties.community_id', $community->id)
                         ->orderBy('properties.created_at', 'desc')
                         ->limit(8)
                         ->get();

        // Developments in this community
        $developments = DB::table('developments')
                           ->select(
                               'id',
                               'title',
                               'slug',
                               'main_image',
                               'price_from',
                               'price_currency'
                           )
                           ->where('is_active', 1)
                           ->where('community_id', $community->id)
                           ->orderBy('created_at', 'desc')
                           ->limit(8)
                           ->get();

        // Other communities (excluding current)
        $otherCommunities = DB::table('communities')
                               ->select('id', 'name', 'slug', 'image')
                               ->where('is_active', 1)
                               ->where('id', '!=', $community->id)
                               ->orderBy('sort_order', 'asc')
                               ->limit(8)
                               ->get();

        return view('website.communities.show', compact(
            'community',
            'properties',
            'developments',
            'otherCommunities'
        ));
    }
}
