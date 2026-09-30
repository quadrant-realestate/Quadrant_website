<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyFrontController extends Controller
{
    // ============================================================
    // SHARED QUERY BUILDER — reused across all listing pages
    // ============================================================
    private function baseQuery()
    {
        return DB::table('properties')
                  ->leftJoin('communities', 'properties.community_id', '=', 'communities.id')
                  ->leftJoin('property_types', 'properties.property_type_id', '=', 'property_types.id')
                  ->select(
                      'properties.id',
                      'properties.title',
                      'properties.slug',
                      'properties.reference_no',
                      'properties.listing_type',
                      'properties.status',
                      'properties.price',
                      'properties.price_currency',
                      'properties.price_on_request',
                      'properties.bedrooms',
                      'properties.bathrooms',
                      'properties.area_sqft',
                      'properties.main_image',
                      'communities.name as community_name',
                      'property_types.name as type_name'
                  )
                  ->where('properties.status', 'active');
    }

    // ============================================================
    // APPLY FILTERS — shared across sale/rent/private/international
    // ============================================================
    private function applyFilters($query, Request $request)
    {
        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('properties.title', 'like', '%' . $request->search . '%')
                  ->orWhere('communities.name', 'like', '%' . $request->search . '%')
                  ->orWhere('properties.address', 'like', '%' . $request->search . '%')
                  ->orWhere('properties.reference_no', 'like', '%' . $request->search . '%');
            });
        }

        // Property Type
        if ($request->property_type_id) {
            $query->where('properties.property_type_id', $request->property_type_id);
        }

        // Bedrooms
        if ($request->bedrooms) {
            if ($request->bedrooms == '7') {
                $query->where('properties.bedrooms', '>=', 7);
            } else {
                $query->where('properties.bedrooms', $request->bedrooms);
            }
        }

        // Community
        if ($request->community_id) {
            $query->where('properties.community_id', $request->community_id);
        }

        // Min Price
        if ($request->min_price) {
            $query->where('properties.price', '>=', $request->min_price);
        }

        // Max Price
        if ($request->max_price) {
            $query->where('properties.price', '<=', $request->max_price);
        }

        // Sort
        if ($request->sort == 'price_asc') {
            $query->orderBy('properties.price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->orderBy('properties.price', 'desc');
        } else {
            $query->orderBy('properties.created_at', 'desc');
        }

        return $query;
    }

    // ============================================================
    // FOR SALE
    // ============================================================
    public function sale(Request $request)
    {
        $query = $this->baseQuery()->where('properties.listing_type', 'sale');
        $query = $this->applyFilters($query, $request);

        $properties    = $query->paginate(12)->withQueryString();
        $propertyTypes = DB::table('property_types')->where('is_active', 1)->orderBy('sort_order')->get();
        $communities   = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('website.properties.sale', compact('properties', 'propertyTypes', 'communities'));
    }

    // ============================================================
    // FOR RENT
    // ============================================================
    public function rent(Request $request)
    {
        $query = $this->baseQuery()->where('properties.listing_type', 'rent');
        $query = $this->applyFilters($query, $request);

        $properties    = $query->paginate(12)->withQueryString();
        $propertyTypes = DB::table('property_types')->where('is_active', 1)->orderBy('sort_order')->get();
        $communities   = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('website.properties.rent', compact('properties', 'propertyTypes', 'communities'));
    }

    // ============================================================
    // PRIVATE OFFICE
    // ============================================================
    public function private(Request $request)
    {
        $query = $this->baseQuery()->where('properties.listing_type', 'private');
        $query = $this->applyFilters($query, $request);

        $properties    = $query->paginate(12)->withQueryString();
        $propertyTypes = DB::table('property_types')->where('is_active', 1)->orderBy('sort_order')->get();
        $communities   = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('website.properties.private', compact('properties', 'propertyTypes', 'communities'));
    }

    // ============================================================
    // INTERNATIONAL
    // ============================================================
    public function international(Request $request)
    {
        $query = $this->baseQuery()->where('properties.listing_type', 'international');
        $query = $this->applyFilters($query, $request);

        $properties    = $query->paginate(12)->withQueryString();
        $propertyTypes = DB::table('property_types')->where('is_active', 1)->orderBy('sort_order')->get();
        $communities   = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('website.properties.international', compact('properties', 'propertyTypes', 'communities'));
    }

    // ============================================================
    // SEARCH — handles all listing_type searches from the header form
    // ============================================================
    public function search(Request $request)
    {
        $query = $this->baseQuery();

        // Filter by listing type if given
        if ($request->listing_type) {
            $query->where('properties.listing_type', $request->listing_type);
        }

        $query = $this->applyFilters($query, $request);

        $properties    = $query->paginate(12)->withQueryString();
        $propertyTypes = DB::table('property_types')->where('is_active', 1)->orderBy('sort_order')->get();
        $communities   = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('website.properties.search', compact('properties', 'propertyTypes', 'communities'));
    }

    // ============================================================
    // SHOW — single property detail page
    // ============================================================
    public function show($slug)
    {
        $property = DB::table('properties')
                      ->leftJoin('communities', 'properties.community_id', '=', 'communities.id')
                      ->leftJoin('property_types', 'properties.property_type_id', '=', 'property_types.id')
                      ->select('properties.*', 'communities.name as community_name', 'property_types.name as type_name')
                      ->where('properties.slug', $slug)
                      ->where('properties.status', 'active')
                      ->first();

        if (!$property) {
            abort(404);
        }

        // Gallery images
        $gallery = DB::table('property_images')
                     ->where('property_id', $property->id)
                     ->orderBy('sort_order')
                     ->get();

        // Amenities
        $amenities = DB::table('property_amenities')
                       ->join('amenities', 'property_amenities.amenity_id', '=', 'amenities.id')
                       ->select('amenities.*')
                       ->where('property_amenities.property_id', $property->id)
                       ->orderBy('amenities.category')
                       ->get();

        // Related properties (same community, same type)
        $relatedProperties = DB::table('properties')
                               ->leftJoin('communities', 'properties.community_id', '=', 'communities.id')
                               ->leftJoin('property_types', 'properties.property_type_id', '=', 'property_types.id')
                               ->select(
                                   'properties.id',
                                   'properties.title',
                                   'properties.slug',
                                   'properties.price',
                                   'properties.price_currency',
                                   'properties.price_on_request',
                                   'properties.bedrooms',
                                   'properties.bathrooms',
                                   'properties.area_sqft',
                                   'properties.main_image',
                                   'communities.name as community_name',
                                   'property_types.name as type_name'
                               )
                               ->where('properties.status', 'active')
                               ->where('properties.id', '!=', $property->id)
                               ->where('properties.community_id', $property->community_id)
                               ->limit(4)
                               ->get();

        return view('website.properties.show', compact(
            'property',
            'gallery',
            'amenities',
            'relatedProperties'
        ));
    }
}
