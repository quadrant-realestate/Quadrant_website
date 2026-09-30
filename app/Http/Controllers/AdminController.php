<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ============================================================
    // SIGNIN — Show login form
    // ============================================================
    public function signin()
    {
        return view('admin.signin');
    }

    // ============================================================
    // DASHBOARD
    // ============================================================
    public function dashboard()
    {
        // ── Property Stats ──────────────────────────────────────
    $totalProperties  = DB::table('properties')->count();
    $activeProperties = DB::table('properties')->where('status', 'active')->count();
    $soldProperties   = DB::table('properties')->where('status', 'sold')->count();
    $rentedProperties = DB::table('properties')->where('status', 'rented')->count();

    // ── Listing Type Breakdown ───────────────────────────────
    $forSale          = DB::table('properties')->where('listing_type', 'sale')->count();
    $forRent          = DB::table('properties')->where('listing_type', 'rent')->count();
    $offPlan          = DB::table('properties')->where('listing_type', 'off_plan')->count();

    // ── Projects ─────────────────────────────────────────────
    $totalDevelopments   = DB::table('developments')->count();
    $totalInvestments    = DB::table('investments')->count();
    $totalBrandedRes     = DB::table('branded_residences')->count();
    $totalCommunities    = DB::table('communities')->count();

    // ── Inquiries ────────────────────────────────────────────
    $totalInquiries    = DB::table('inquiries')->count();
    $newInquiries      = DB::table('inquiries')->where('status', 'new')->count();
    $contactedInquiries= DB::table('inquiries')->where('status', 'contacted')->count();
    $qualifiedInquiries= DB::table('inquiries')->where('status', 'qualified')->count();

    // ── Inquiry Types ────────────────────────────────────────
    $inquiryTypes = DB::table('inquiries')
                      ->select('inquiry_type', DB::raw('count(*) as total'))
                      ->groupBy('inquiry_type')
                      ->get();

    // ── Recent Inquiries ─────────────────────────────────────
    $recentInquiries = DB::table('inquiries')
                         ->leftJoin('properties', 'inquiries.property_id', '=', 'properties.id')
                         ->select(
                             'inquiries.*',
                             'properties.title as property_title'
                         )
                         ->orderBy('inquiries.created_at', 'desc')
                         ->limit(8)
                         ->get();

    // ── Recent Properties ────────────────────────────────────
    $recentProperties = DB::table('properties')
                          ->leftJoin('communities', 'properties.community_id', '=', 'communities.id')
                          ->select(
                              'properties.id',
                              'properties.title',
                              'properties.reference_no',
                              'properties.listing_type',
                              'properties.price',
                              'properties.price_currency',
                              'properties.price_on_request',
                              'properties.bedrooms',
                              'properties.status',
                              'properties.main_image',
                              'properties.created_at',
                              'communities.name as community_name'
                          )
                          ->orderBy('properties.created_at', 'desc')
                          ->limit(6)
                          ->get();

    // ── Featured Counts ──────────────────────────────────────
    $featuredProperties  = DB::table('properties')->where('is_featured', 1)->count();
    $featuredDevelopments= DB::table('developments')->where('is_featured', 1)->count();

    // ── Development Status Breakdown ─────────────────────────
    $devByStatus = DB::table('developments')
                     ->select('status', DB::raw('count(*) as total'))
                     ->groupBy('status')
                     ->get();

    return view('admin.index', compact(
        'totalProperties',
        'activeProperties',
        'soldProperties',
        'rentedProperties',
        'forSale',
        'forRent',
        'offPlan',
        'totalDevelopments',
        'totalInvestments',
        'totalBrandedRes',
        'totalCommunities',
        'totalInquiries',
        'newInquiries',
        'contactedInquiries',
        'qualifiedInquiries',
        'inquiryTypes',
        'recentInquiries',
        'recentProperties',
        'featuredProperties',
        'featuredDevelopments',
        'devByStatus'
    ));
    }

    // ============================================================
    // PROFILE — Show profile page
    // ============================================================
    public function profile()
    {
        $admin = DB::table('admins')
                    ->where('id', session('id'))
                    ->first();

        if (!$admin) {
            return redirect()->route('signin');
        }

        return view('admin.profile.index', compact('admin'));
    }

    // ============================================================
    // PROFILE UPDATE — Save profile changes
    // ============================================================
    public function profileUpdate(Request $request)
    {
         $adminId = session('id');

        $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:admins,email,' . $adminId,
            'phone'            => 'nullable|string|max:20',
            'current_password' => 'nullable|string',
            'new_password'     => 'nullable|string|min:6|confirmed',
        ]);

        $admin = DB::table('admins')->where('id', $adminId)->first();

        // Handle avatar upload
        $avatar = $admin->avatar;
        if ($request->hasFile('avatar')) {
            if ($avatar && file_exists(public_path($avatar))) {
                unlink(public_path($avatar));
            }
            $file   = $request->file('avatar');
            $name   = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/admin'), $name);
            $avatar = 'uploads/admin/' . $name;
        }

        // Handle profile_photo upload
        $profilePhoto = $admin->profile_photo;
        if ($request->hasFile('profile_photo')) {
            if ($profilePhoto && file_exists(public_path($profilePhoto))) {
                unlink(public_path($profilePhoto));
            }
            $file         = $request->file('profile_photo');
            $name         = time() . '_photo_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/admin'), $name);
            $profilePhoto = 'uploads/admin/' . $name;
        }

        // Build update data
        $updateData = [
            'name'          => trim($request->name),
            'email'         => trim($request->email),
            'phone'         => $request->phone,
            'avatar'        => $avatar,
            'profile_photo' => $profilePhoto,
            'updated_at'    => now(),
        ];

        // Handle password change
        if ($request->filled('current_password')) {
            if (sha1($request->current_password) !== $admin->password) {
                return redirect()->back()
                                ->with('error', 'Current password is incorrect.')
                                ->withInput();
            }
            if ($request->filled('new_password')) {
                $updateData['password'] = sha1($request->new_password);
            }
        }

        DB::table('admins')->where('id', $adminId)->update($updateData);

        // Update session
        session(['name' => $request->name]);
        session(['email' => $request->email]);

        return redirect()->route('admin.profile')
                        ->with('success', 'Profile updated successfully.');
    }
}