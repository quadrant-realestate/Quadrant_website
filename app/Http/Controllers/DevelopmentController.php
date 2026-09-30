<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevelopmentController extends Controller
{
    // ============================================================
    // INDEX
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('developments')
                    ->leftJoin('communities', 'developments.community_id', '=', 'communities.id')
                    ->select(
                        'developments.*',
                        'communities.name as community_name'
                    );

        // Filter by status
        if ($request->status) {
            $query->where('developments.status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('developments.title', 'like', '%' . $request->search . '%')
                  ->orWhere('developments.developer_name', 'like', '%' . $request->search . '%');
            });
        }

        $developments = $query->orderBy('developments.created_at', 'desc')->get();
        $communities  = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('admin.developments.index', compact('developments', 'communities'));
    }

    // ============================================================
    // CREATE
    // ============================================================
    public function create()
    {
        $communities = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('admin.developments.create', compact('communities'));
    }

    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255|unique:developments,title',
            'developer_name' => 'nullable|string|max:150',
            'community_id'   => 'nullable|exists:communities,id',
            'price_from'     => 'nullable|numeric',
            'handover_date'  => 'nullable|date',
        ]);

        // Main Image
        $mainImage = null;
        if ($request->hasFile('main_image')) {
            $file      = $request->file('main_image');
            $name      = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments'), $name);
            $mainImage = 'uploads/developments/' . $name;
        }

        // Banner Image
        $bannerImage = null;
        if ($request->hasFile('banner_image')) {
            $file        = $request->file('banner_image');
            $name        = time() . '_banner_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments'), $name);
            $bannerImage = 'uploads/developments/' . $name;
        }

        // Developer Logo
        $developerLogo = null;
        if ($request->hasFile('developer_logo')) {
            $file          = $request->file('developer_logo');
            $name          = time() . '_logo_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments/logos'), $name);
            $developerLogo = 'uploads/developments/logos/' . $name;
        }

        // Brochure PDF
        $brochure = null;
        if ($request->hasFile('brochure_pdf')) {
            $file     = $request->file('brochure_pdf');
            $name     = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments/brochures'), $name);
            $brochure = 'uploads/developments/brochures/' . $name;
        }

        // RERA QR Code Image
        $reraQrImage = null;
        if ($request->hasFile('rera_qr_image')) {
            $file        = $request->file('rera_qr_image');
            $name        = time() . '_rera_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments/rera'), $name);
            $reraQrImage = 'uploads/developments/rera/' . $name;
        }

        // OG Image
        $ogImage = null;
        if ($request->hasFile('og_image')) {
            $file    = $request->file('og_image');
            $name    = time() . '_og_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments/og'), $name);
            $ogImage = 'uploads/developments/og/' . $name;
        }

        $developmentId = DB::table('developments')->insertGetId([
            'title'             => trim($request->title),
            'slug'              => Str::slug($request->title),
            'developer_name'    => $request->developer_name,
            'developer_logo'    => $developerLogo,
            'community_id'      => $request->community_id,
            'description'       => $request->description,
            'short_description' => $request->short_description,
            'property_types'    => $request->property_types,
            'total_units'       => $request->total_units,
            'floors'            => $request->floors,
            'price_from'        => $request->price_from,
            'price_currency'    => $request->price_currency ?? 'AED',
            'payment_plan'      => $request->payment_plan,
            'down_payment_pct'  => $request->down_payment_pct,
            'handover_date'     => $request->handover_date,
            'completion_pct'    => $request->completion_pct,
            'main_image'        => $mainImage,
            'banner_image'      => $bannerImage,
            'brochure_pdf'      => $brochure,
            'video_url'         => $request->video_url,
            'status'            => $request->status ?? 'launched',
            'is_featured'       => $request->has('is_featured') ? 1 : 0,
            'is_active'         => $request->has('is_active') ? 1 : 0,
            'meta_title'        => $request->meta_title,
            'meta_desc'         => $request->meta_desc,
            'created_at'        => now(),
            'updated_at'        => now(),
            'rera_permit'       => $request->rera_permit,
            'rera_qr_image'     => $reraQrImage,
            'quadrant_view'     => $request->quadrant_view,
            'bedroom_range'     => $request->bedroom_range,
            'size_from'         => $request->size_from,
            'size_to'           => $request->size_to,
            'og_image' => $ogImage,
            'nearby_landmarks' => $request->nearby_landmarks,

        ]);

        // Gallery Images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $file) {
                $name = time() . '_' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/developments/gallery'), $name);
                DB::table('development_images')->insert([
                    'development_id' => $developmentId,
                    'image_path'     => 'uploads/developments/gallery/' . $name,
                    'sort_order'     => $index,
                ]);
            }
        }

        return redirect()->route('admin.developments.index')
                         ->with('success', 'Development added successfully.');
    }

    // ============================================================
    // EDIT
    // ============================================================
    public function edit($id)
    {
        $development = DB::table('developments')->where('id', $id)->first();

        if (!$development) {
            return redirect()->route('admin.developments.index')
                             ->with('error', 'Development not found.');
        }

        $communities = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();
        $gallery     = DB::table('development_images')->where('development_id', $id)->orderBy('sort_order')->get();

        return view('admin.developments.edit', compact('development', 'communities', 'gallery'));
    }

    // ============================================================
    // UPDATE
    // ============================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'          => 'required|string|max:255|unique:developments,title,' . $id,
            'developer_name' => 'nullable|string|max:150',
            'community_id'   => 'nullable|exists:communities,id',
            'price_from'     => 'nullable|numeric',
            'handover_date'  => 'nullable|date',
        ]);

        $development = DB::table('developments')->where('id', $id)->first();

        // Main Image
        $mainImage = $development->main_image;
        if ($request->hasFile('main_image')) {
            if ($mainImage && file_exists(public_path($mainImage))) {
                unlink(public_path($mainImage));
            }
            $file      = $request->file('main_image');
            $name      = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments'), $name);
            $mainImage = 'uploads/developments/' . $name;
        }

        // Banner Image
        $bannerImage = $development->banner_image;
        if ($request->hasFile('banner_image')) {
            if ($bannerImage && file_exists(public_path($bannerImage))) {
                unlink(public_path($bannerImage));
            }
            $file        = $request->file('banner_image');
            $name        = time() . '_banner_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments'), $name);
            $bannerImage = 'uploads/developments/' . $name;
        }

        // Developer Logo
        $developerLogo = $development->developer_logo;
        if ($request->hasFile('developer_logo')) {
            if ($developerLogo && file_exists(public_path($developerLogo))) {
                unlink(public_path($developerLogo));
            }
            $file          = $request->file('developer_logo');
            $name          = time() . '_logo_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments/logos'), $name);
            $developerLogo = 'uploads/developments/logos/' . $name;
        }

        // Brochure
        $brochure = $development->brochure_pdf;
        if ($request->hasFile('brochure_pdf')) {
            if ($brochure && file_exists(public_path($brochure))) {
                unlink(public_path($brochure));
            }
            $file     = $request->file('brochure_pdf');
            $name     = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments/brochures'), $name);
            $brochure = 'uploads/developments/brochures/' . $name;
        }

        // RERA QR Code Image
        $reraQrImage = $development->rera_qr_image;
        if ($request->hasFile('rera_qr_image')) {
            if ($reraQrImage && file_exists(public_path($reraQrImage))) {
                unlink(public_path($reraQrImage));
            }
            $file        = $request->file('rera_qr_image');
            $name        = time() . '_rera_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments/rera'), $name);
            $reraQrImage = 'uploads/developments/rera/' . $name;
        }

        // OG Image
        $ogImage = $development->og_image;
        if ($request->hasFile('og_image')) {
            if ($ogImage && file_exists(public_path($ogImage))) {
                unlink(public_path($ogImage));
            }
            $file    = $request->file('og_image');
            $name    = time() . '_og_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/developments/og'), $name);
            $ogImage = 'uploads/developments/og/' . $name;
        }

        DB::table('developments')->where('id', $id)->update([
            'title'             => trim($request->title),
            'slug'              => Str::slug($request->title),
            'developer_name'    => $request->developer_name,
            'developer_logo'    => $developerLogo,
            'community_id'      => $request->community_id,
            'description'       => $request->description,
            'short_description' => $request->short_description,
            'property_types'    => $request->property_types,
            'total_units'       => $request->total_units,
            'floors'            => $request->floors,
            'price_from'        => $request->price_from,
            'price_currency'    => $request->price_currency ?? 'AED',
            'payment_plan'      => $request->payment_plan,
            'down_payment_pct'  => $request->down_payment_pct,
            'handover_date'     => $request->handover_date,
            'completion_pct'    => $request->completion_pct,
            'main_image'        => $mainImage,
            'banner_image'      => $bannerImage,
            'brochure_pdf'      => $brochure,
            'video_url'         => $request->video_url,
            'status'            => $request->status ?? 'launched',
            'is_featured'       => $request->has('is_featured') ? 1 : 0,
            'is_active'         => $request->has('is_active') ? 1 : 0,
            'meta_title'        => $request->meta_title,
            'meta_desc'         => $request->meta_desc,
            'updated_at'        => now(),
            'rera_permit'       => $request->rera_permit,
            'rera_qr_image'     => $reraQrImage,
            'quadrant_view'     => $request->quadrant_view,
            'bedroom_range'     => $request->bedroom_range,
            'size_from'         => $request->size_from,
            'size_to'           => $request->size_to,
            'og_image' => $ogImage,
            'nearby_landmarks' => $request->nearby_landmarks,

        ]);

        // New Gallery Images
        if ($request->hasFile('gallery')) {
            $lastOrder = DB::table('development_images')
                           ->where('development_id', $id)
                           ->max('sort_order') ?? 0;

            foreach ($request->file('gallery') as $index => $file) {
                $name = time() . '_' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/developments/gallery'), $name);
                DB::table('development_images')->insert([
                    'development_id' => $id,
                    'image_path'     => 'uploads/developments/gallery/' . $name,
                    'sort_order'     => $lastOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.developments.index')
                         ->with('success', 'Development updated successfully.');
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function delete($id)
    {
        $development = DB::table('developments')->where('id', $id)->first();

        if (!$development) {
            return redirect()->route('admin.developments.index')
                             ->with('error', 'Development not found.');
        }

        // Delete images from server
        if ($development->main_image && file_exists(public_path($development->main_image))) {
            unlink(public_path($development->main_image));
        }
        if ($development->banner_image && file_exists(public_path($development->banner_image))) {
            unlink(public_path($development->banner_image));
        }
        if ($development->developer_logo && file_exists(public_path($development->developer_logo))) {
            unlink(public_path($development->developer_logo));
        }
        if ($development->brochure_pdf && file_exists(public_path($development->brochure_pdf))) {
            unlink(public_path($development->brochure_pdf));
        }
        if ($development->rera_qr_image && file_exists(public_path($development->rera_qr_image))) {
            unlink(public_path($development->rera_qr_image));
        }

        if ($development->og_image && file_exists(public_path($development->og_image))) {
            unlink(public_path($development->og_image));
        }

        // Delete gallery images
        $gallery = DB::table('development_images')->where('development_id', $id)->get();
        foreach ($gallery as $img) {
            if (file_exists(public_path($img->image_path))) {
                unlink(public_path($img->image_path));
            }
        }

        DB::table('development_images')->where('development_id', $id)->delete();
        DB::table('developments')->where('id', $id)->delete();

        return redirect()->route('admin.developments.index')
                         ->with('success', 'Development deleted successfully.');
    }

    // ============================================================
    // DELETE GALLERY IMAGE
    // ============================================================
    public function deleteGalleryImage($id)
    {
        $image = DB::table('development_images')->where('id', $id)->first();

        if ($image) {
            if (file_exists(public_path($image->image_path))) {
                unlink(public_path($image->image_path));
            }
            DB::table('development_images')->where('id', $id)->delete();
        }

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    // ============================================================
    // TOGGLE FEATURED
    // ============================================================
    public function toggleFeatured($id)
    {
        $development = DB::table('developments')->where('id', $id)->first();

        DB::table('developments')->where('id', $id)->update([
            'is_featured' => $development->is_featured ? 0 : 1,
            'updated_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Featured status updated.');
    }

    // ============================================================
    // TOGGLE STATUS
    // ============================================================
    public function toggleStatus($id)
    {
        $development = DB::table('developments')->where('id', $id)->first();

        DB::table('developments')->where('id', $id)->update([
            'is_active'  => $development->is_active ? 0 : 1,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}