<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestmentFrontController extends Controller
{
    // ============================================================
    // INDEX — Investments Listing
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('investments')
                    ->leftJoin('communities', 'investments.community_id', '=', 'communities.id')
                    ->leftJoin('properties', 'investments.property_id', '=', 'properties.id')
                    ->select(
                        'investments.id',
                        'investments.title',
                        'investments.slug',
                        'investments.investment_type',
                        'investments.price',
                        'investments.price_currency',
                        'investments.roi_percentage',
                        'investments.gross_yield',
                        'investments.main_image',
                        'communities.name as community_name',
                        'properties.area_sqft as property_area_sqft'
                    )
                    ->where('investments.is_active', 1);

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('investments.title', 'like', '%' . $request->search . '%')
                  ->orWhere('communities.name', 'like', '%' . $request->search . '%')
                  ->orWhere('investments.investment_type', 'like', '%' . $request->search . '%');
            });
        }

        // Investment Type
        if ($request->investment_type) {
            $query->where('investments.investment_type', $request->investment_type);
        }

        // Community
        if ($request->community_id) {
            $query->where('investments.community_id', $request->community_id);
        }

        // Price Range
        if ($request->min_price) {
            $query->where('investments.price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('investments.price', '<=', $request->max_price);
        }

        // Sort
        if ($request->sort == 'price_asc') {
            $query->orderBy('investments.price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->orderBy('investments.price', 'desc');
        } elseif ($request->sort == 'roi_desc') {
            $query->orderBy('investments.roi_percentage', 'desc');
        } else {
            $query->orderBy('investments.created_at', 'desc');
        }

        $investments = $query->paginate(12)->withQueryString();

        $communities      = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();
        $investmentTypes  = DB::table('investments')
                              ->select('investment_type')
                              ->where('is_active', 1)
                              ->whereNotNull('investment_type')
                              ->distinct()
                              ->orderBy('investment_type')
                              ->pluck('investment_type');

        $noindex = $investments->total() === 0;

        return view('website.investments.index', compact(
            'investments',
            'communities',
            'investmentTypes',
            'noindex'
        ));
    }

    // ============================================================
    // SHOW — Single Investment Detail Page
    // ============================================================
    public function show($slug)
    {
        $investment = DB::table('investments')
                         ->leftJoin('communities', 'investments.community_id', '=', 'communities.id')
                         ->leftJoin('properties', 'investments.property_id', '=', 'properties.id')
                         ->select(
                             'investments.*',
                             'communities.name as community_name',
                             'communities.latitude as community_latitude',
                             'communities.longitude as community_longitude',
                             'properties.area_sqft as property_area_sqft',
                             'properties.bedrooms as property_bedrooms',
                             'properties.bathrooms as property_bathrooms',
                             'properties.title as property_title',
                             'properties.slug as property_slug'
                         )
                         ->where('investments.slug', $slug)
                         ->where('investments.is_active', 1)
                         ->first();

        if (!$investment) {
            abort(404);
        }

        // Related investments (same community)
        $relatedInvestments = DB::table('investments')
                                 ->leftJoin('communities', 'investments.community_id', '=', 'communities.id')
                                 ->select(
                                     'investments.id',
                                     'investments.title',
                                     'investments.slug',
                                     'investments.main_image',
                                     'investments.price',
                                     'investments.price_currency',
                                     'investments.investment_type',
                                     'communities.name as community_name'
                                 )
                                 ->where('investments.is_active', 1)
                                 ->where('investments.id', '!=', $investment->id)
                                 ->where('investments.community_id', $investment->community_id)
                                 ->limit(4)
                                 ->get();

        return view('website.investments.show', compact(
            'investment',
            'relatedInvestments'
        ));
    }
}
