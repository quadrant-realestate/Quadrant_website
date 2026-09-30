<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        // ── Section 3: Featured Off-Plan Projects (centrepiece) ──────────
        $featuredDevelopments = DB::table('developments')
                                   ->leftJoin('communities', 'developments.community_id', '=', 'communities.id')
                                   ->select(
                                       'developments.id',
                                       'developments.title',
                                       'developments.slug',
                                       'developments.main_image',
                                       'developments.status',
                                       'developments.price_from',
                                       'developments.price_currency',
                                       'developments.developer_name',
                                       'developments.handover_date',
                                       'developments.bedroom_range',
                                       'communities.name as community_name'
                                   )
                                   ->where('developments.is_active', 1)
                                   ->where('developments.is_featured', 1)
                                   ->orderBy('developments.created_at', 'desc')
                                   ->limit(8)
                                   ->get();

        // ── Section 5: Featured Communities — the 7 priority communities ──
        $priorityCommunityRecords = DB::table('communities')
                                       ->whereIn('slug', [
                                           'downtown-dubai',
                                           'dubai-marina',
                                           'palm-jebel-ali',
                                           'dubai-creek-harbour',
                                           'dubai-hills-estate',
                                           'jvc',
                                           'dubai-south',
                                       ])
                                       ->select('slug', 'image')
                                       ->get();

        // ── Developer Partners ─────────────────────────────────────────────
        $developerPartners = DB::table('developments')
                               ->where('is_active', 1)
                               ->whereNotNull('developer_name')
                               ->select('developer_name', 'developer_logo')
                               ->distinct()
                               ->limit(6)
                               ->get();

        // ── Section 9: Insights teaser — latest 3 published articles ──────
        // Renders only once blog_posts table + InsightController exist.
        $latestInsights = collect();
        if (Schema::hasTable('blog_posts')) {
            $latestInsights = DB::table('blog_posts')
                                 ->where('is_published', 1)
                                 ->orderBy('published_at', 'desc')
                                 ->limit(3)
                                 ->get();
        }

        return view('website.index', compact(
            'featuredDevelopments',
            'priorityCommunityRecords',
            'developerPartners',
            'latestInsights'
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
        $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|max:255',
            'phone'    => 'required|string|max:30',
            'interest' => 'required|in:off-plan,luxury,investment,selling,general',
            'message'  => 'required|string|max:5000',
        ]);

        DB::table('inquiries')->insert([
            'name'         => trim($request->name),
            'email'        => trim($request->email),
            'phone'        => trim($request->phone),
            'message'      => trim($request->message),
            'inquiry_type' => $request->interest,
            'status'       => 'new',
            'source'       => 'contact_page',
            'ip_address'   => $request->ip(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->back()->with(
            'success',
            'Thank you for contacting us. One of our advisors will get in touch with you shortly.'
        );
    }

    // ============================================================
    // GENERAL INQUIRY SUBMIT — used by property/development/investment
    // "Register Your Interest" forms (single name field)
    // ============================================================
    public function inquirySubmit(Request $request)
    {
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