<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevelopmentFrontController extends Controller
{
    // ============================================================
    // INDEX — Off-Plan Projects Catalogue
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('developments')
                    ->leftJoin('communities', 'developments.community_id', '=', 'communities.id')
                    ->select(
                        'developments.id',
                        'developments.title',
                        'developments.slug',
                        'developments.main_image',
                        'developments.developer_name',
                        'developments.developer_logo',
                        'developments.price_from',
                        'developments.price_currency',
                        'developments.status',
                        'developments.handover_date',
                        'developments.property_types',
                        'developments.bedroom_range',
                        'developments.payment_plan',
                        'developments.rera_permit',
                        'developments.is_featured',
                        'communities.name as community_name'
                    )
                    ->where('developments.is_active', 1);

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('developments.title', 'like', '%' . $request->search . '%')
                  ->orWhere('developments.developer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('communities.name', 'like', '%' . $request->search . '%');
            });
        }

        // Community filter
        if ($request->community_id) {
            $query->where('developments.community_id', $request->community_id);
        }

        // Developer filter
        if ($request->developer) {
            $query->where('developments.developer_name', $request->developer);
        }

        // Property type filter (stored as comma-separated text e.g. "Apartment, Penthouse")
        if ($request->property_type) {
            $query->where('developments.property_types', 'like', '%' . $request->property_type . '%');
        }

        // Bedrooms filter (stored as a range string e.g. "1-3 BR" — match loosely)
        if ($request->bedrooms) {
            $query->where('developments.bedroom_range', 'like', '%' . $request->bedrooms . '%');
        }

        // Price range
        if ($request->min_price) {
            $query->where('developments.price_from', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('developments.price_from', '<=', $request->max_price);
        }

        // Handover year
        if ($request->handover_year) {
            $query->whereYear('developments.handover_date', $request->handover_year);
        }

        // Status
        if ($request->status) {
            $query->where('developments.status', $request->status);
        }

        // Sort
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('developments.price_from', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('developments.price_from', 'desc');
                break;
            case 'handover_soonest':
                $query->orderBy('developments.handover_date', 'asc');
                break;
            case 'featured':
                $query->orderBy('developments.is_featured', 'desc')
                      ->orderBy('developments.created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('developments.created_at', 'desc');
                break;
        }

        $developments = $query->paginate(12)->withQueryString();

        // Filter dropdown data
        $communities = DB::table('communities')
                          ->whereIn('slug', [
                              'downtown-dubai','dubai-marina','palm-jebel-ali',
                              'dubai-creek-harbour','dubai-hills-estate','jvc','dubai-south',
                          ])
                          ->orderBy('sort_order')
                          ->get();

        $developers = DB::table('developments')
                         ->where('is_active', 1)
                         ->whereNotNull('developer_name')
                         ->distinct()
                         ->orderBy('developer_name')
                         ->pluck('developer_name');

        $handoverYears = DB::table('developments')
                            ->where('is_active', 1)
                            ->whereNotNull('handover_date')
                            ->selectRaw('YEAR(handover_date) as yr')
                            ->distinct()
                            ->orderBy('yr')
                            ->pluck('yr');

        return view('website.developments.index', compact(
            'developments',
            'communities',
            'developers',
            'handoverYears'
        ));
    }

    // ============================================================
    // SHOW — Single Development OR Branded Residence Detail Page
    // ============================================================
    public function show($slug)
    {
        $development = DB::table('developments')
                          ->leftJoin('communities', 'developments.community_id', '=', 'communities.id')
                          ->select(
                              'developments.*',
                              'communities.name as community_name',
                              'communities.latitude as community_latitude',
                              'communities.longitude as community_longitude'
                          )
                          ->where('developments.slug', $slug)
                          ->where('developments.is_active', 1)
                          ->first();

        if ($development) {
            $gallery = DB::table('development_images')
                         ->where('development_id', $development->id)
                         ->orderBy('sort_order')
                         ->get();

            $floorPlans = DB::table('development_floor_plans')
                             ->where('development_id', $development->id)
                             ->orderBy('sort_order')
                             ->get();

            $amenities = DB::table('development_amenities')
                            ->join('amenities', 'development_amenities.amenity_id', '=', 'amenities.id')
                            ->select('amenities.*')
                            ->where('development_amenities.development_id', $development->id)
                            ->orderBy('amenities.category')
                            ->get();

            $relatedDevelopments = DB::table('developments')
                                      ->leftJoin('communities', 'developments.community_id', '=', 'communities.id')
                                      ->select(
                                          'developments.id',
                                          'developments.title',
                                          'developments.slug',
                                          'developments.main_image',
                                          'developments.price_from',
                                          'developments.price_currency',
                                          'communities.name as community_name'
                                      )
                                      ->where('developments.is_active', 1)
                                      ->where('developments.id', '!=', $development->id)
                                      ->where(function($q) use ($development) {
                                          $q->where('developments.community_id', $development->community_id)
                                            ->orWhere('developments.developer_name', $development->developer_name);
                                      })
                                      ->limit(3)
                                      ->get();

            return view('website.developments.show', compact(
                'development',
                'gallery',
                'floorPlans',
                'amenities',
                'relatedDevelopments'
            ));
        }

        // Not found in developments — check branded_residences table
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

        if ($residence) {
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

        abort(404);
    }
}