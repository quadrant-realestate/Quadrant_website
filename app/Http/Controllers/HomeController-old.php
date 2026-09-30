<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Featured properties for sale carousel
        $featuredProperties = DB::table('properties')
                                ->leftJoin('communities', 'properties.community_id', '=', 'communities.id')
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
                                    'communities.name as community_name',
                                    'property_types.name as type_name'
                                )
                                ->where('properties.status', 'active')
                                ->where('properties.is_featured', 1)
                                ->where('properties.listing_type', 'sale')
                                ->orderBy('properties.created_at', 'desc')
                                ->limit(8)
                                ->get();

        // Private listings
        $privateListings = DB::table('properties')
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
                              ->where('properties.listing_type', 'private')
                              ->orderBy('properties.created_at', 'desc')
                              ->limit(4)
                              ->get();

        // New developments
        $developments = DB::table('developments')
                           ->leftJoin('communities', 'developments.community_id', '=', 'communities.id')
                           ->select(
                               'developments.id',
                               'developments.title',
                               'developments.slug',
                               'developments.main_image',
                               'developments.status',
                               'developments.price_from',
                               'developments.price_currency',
                               'communities.name as community_name'
                           )
                           ->where('developments.is_active', 1)
                           ->where('developments.is_featured', 1)
                           ->orderBy('developments.created_at', 'desc')
                           ->limit(8)
                           ->get();

        // International properties (listing_type = international)
        $internationalProperties = DB::table('properties')
                                     ->where('status', 'active')
                                     ->where('listing_type', 'international')
                                     ->orderBy('created_at', 'desc')
                                     ->limit(6)
                                     ->get();

        // Recognitions / media logos
        $recognitions = DB::table('recognitions')
                           ->where('is_active', 1)
                           ->orderBy('sort_order', 'asc')
                           ->get();

        // Communities
        $communities = DB::table('communities')
                          ->where('is_active', 1)
                          ->where('is_featured', 1)
                          ->orderBy('sort_order', 'asc')
                          ->limit(6)
                          ->get();

        // Property types for search dropdowns
        $propertyTypes = DB::table('property_types')
                            ->where('is_active', 1)
                            ->orderBy('sort_order')
                            ->get();

        // Stats from settings
        $statsProperties = setting('stats_properties', '500+');
        $statsYears      = setting('stats_years', '10+');
        $statsClients    = setting('stats_clients', '1000+');

        // Hero content from settings
        $heroTitle    = setting('hero_title', 'Luxury Homes, Villas, Penthouses & Mansions in Dubai');
        $heroSubtitle = setting('hero_subtitle', 'Luxury real estate experts');
        $heroVideo    = setting('hero_video');

        // About text
        $aboutText = setting('about_section_text');

        return view('website.index', compact(
            'featuredProperties',
            'privateListings',
            'developments',
            'internationalProperties',
            'recognitions',
            'communities',
            'propertyTypes',
            'statsProperties',
            'statsYears',
            'statsClients',
            'heroTitle',
            'heroSubtitle',
            'heroVideo',
            'aboutText'
        ));
    }

    // ============================================================
    // CONTACT PAGE
    // ============================================================
    public function contact()
    {
        return view('website.contact');
    }

    // ============================================================
    // CONTACT FORM SUBMIT — handles first_name + last_name fields
    // ============================================================
    public function contactSubmit(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'phone'      => 'required|string|max:30',
            'message'    => 'required|string',
        ]);

        $fullName = trim($request->first_name . ' ' . $request->last_name);

        DB::table('inquiries')->insert([
            'name'         => $fullName,
            'email'        => trim($request->email),
            'phone'        => $request->phone,
            'message'      => $request->message,
            'inquiry_type' => 'general',
            'status'       => 'new',
            'source'       => 'contact_page',
            'ip_address'   => $request->ip(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->back()->with('success', 'Thank you! We will get back to you shortly.');
    }

    // ============================================================
    // GENERAL INQUIRY SUBMIT — used by property/development/investment
    // "Register Your Interest" forms (single name field)
    // ============================================================
    public function inquirySubmit(Request $request)
    {
        // dd("Good");
        $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:30',
        ]);

        DB::table('inquiries')->insert([
            'property_id'    => $request->property_id,
            'development_id' => $request->development_id,
            'name'           => trim($request->name),
            'email'          => trim($request->email),
            'phone'          => $request->phone,
            'message'        => $request->notes,
            'inquiry_type'   => $request->inquiry_type ?? 'general',
            'status'         => 'new',
            'source'         => $request->source ?? 'website',
            'ip_address'     => $request->ip(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->back()->with('interest_success', 'Thank you! We will be in touch shortly.');
    }
}