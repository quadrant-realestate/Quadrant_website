<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    // ============================================================
    // INDEX
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('properties')
                    ->leftJoin('communities', 'properties.community_id', '=', 'communities.id')
                    ->leftJoin('property_types', 'properties.property_type_id', '=', 'property_types.id')
                    ->select(
                        'properties.*',
                        'communities.name as community_name',
                        'property_types.name as type_name'
                    );

        // Filter by listing type
        if ($request->listing_type) {
            $query->where('properties.listing_type', $request->listing_type);
        }

        // Filter by status
        if ($request->status) {
            $query->where('properties.status', $request->status);
        }

        // Filter by community
        if ($request->community_id) {
            $query->where('properties.community_id', $request->community_id);
        }

        // Search by title or reference
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('properties.title', 'like', '%' . $request->search . '%')
                  ->orWhere('properties.reference_no', 'like', '%' . $request->search . '%');
            });
        }

        $properties  = $query->orderBy('properties.created_at', 'desc')->get();
        $communities = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();

        return view('admin.properties.index', compact('properties', 'communities'));
    }

    // ============================================================
    // CREATE
    // ============================================================
    public function create()
    {
        $communities    = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();
        $property_types = DB::table('property_types')->where('is_active', 1)->orderBy('sort_order')->get();
        $amenities      = DB::table('amenities')->orderBy('category')->orderBy('sort_order')->get();

        return view('admin.properties.create', compact('communities', 'property_types', 'amenities'));
    }

    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'listing_type'     => 'required|in:sale,rent,off_plan,international,private',
            'property_type_id' => 'nullable|exists:property_types,id',
            'community_id'     => 'nullable|exists:communities,id',
            'price'            => 'nullable|numeric',
            'bedrooms'         => 'nullable|integer',
            'bathrooms'        => 'nullable|integer',
            'area_sqft'        => 'nullable|numeric',
        ]);

        // Generate reference number
        $reference_no = 'QDR-' . strtoupper(Str::random(6));

        // Handle main image upload
        $mainImage = null;
        if ($request->hasFile('main_image')) {
            $file      = $request->file('main_image');
            $name      = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/properties'), $name);
            $mainImage = 'uploads/properties/' . $name;
        }

        // Handle floor plan upload
        $floorPlan = null;
        if ($request->hasFile('floor_plan_image')) {
            $file      = $request->file('floor_plan_image');
            $name      = time() . '_fp_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/properties'), $name);
            $floorPlan = 'uploads/properties/' . $name;
        }

        // Handle brochure PDF upload
        $brochure = null;
        if ($request->hasFile('brochure_pdf')) {
            $file     = $request->file('brochure_pdf');
            $name     = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/properties/brochures'), $name);
            $brochure = 'uploads/properties/brochures/' . $name;
        }

        // Insert property
        $propertyId = DB::table('properties')->insertGetId([
            'title'             => trim($request->title),
            'slug'              => Str::slug($request->title),
            'reference_no'      => $reference_no,
            'listing_type'      => $request->listing_type,
            'property_type_id'  => $request->property_type_id,
            'community_id'      => $request->community_id,
            'address'           => $request->address,
            'city'              => $request->city ?? 'Dubai',
            'country'           => $request->country ?? 'UAE',
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'google_maps_link'  => $request->google_maps_link,
            'bedrooms'          => $request->bedrooms,
            'bathrooms'         => $request->bathrooms,
            'area_sqft'         => $request->area_sqft,
            'plot_area_sqft'    => $request->plot_area_sqft,
            'floor_number'      => $request->floor_number,
            'total_floors'      => $request->total_floors,
            'parking_spaces'    => $request->parking_spaces,
            'furnished'         => $request->furnished,
            'price'             => $request->price,
            'price_currency'    => $request->price_currency ?? 'AED',
            'price_on_request'  => $request->has('price_on_request') ? 1 : 0,
            'service_charge'    => $request->service_charge,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'main_image'        => $mainImage,
            'video_url'         => $request->video_url,
            'virtual_tour_url'  => $request->virtual_tour_url,
            'floor_plan_image'  => $floorPlan,
            'brochure_pdf'      => $brochure,
            'is_featured'       => $request->has('is_featured') ? 1 : 0,
            'is_exclusive'      => $request->has('is_exclusive') ? 1 : 0,
            'is_new'            => $request->has('is_new') ? 1 : 0,
            'status'            => $request->status ?? 'active',
            'meta_title'        => $request->meta_title,
            'meta_desc'         => $request->meta_desc,
            'meta_keywords'     => $request->meta_keywords,
            'published_at'      => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $file) {
                $name = time() . '_' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/properties/gallery'), $name);
                DB::table('property_images')->insert([
                    'property_id' => $propertyId,
                    'image_path'  => 'uploads/properties/gallery/' . $name,
                    'is_primary'  => $index === 0 ? 1 : 0,
                    'sort_order'  => $index,
                    'created_at'  => now(),
                ]);
            }
        }

        // Handle amenities
        if ($request->amenities) {
            foreach ($request->amenities as $amenityId) {
                DB::table('property_amenities')->insert([
                    'property_id' => $propertyId,
                    'amenity_id'  => $amenityId,
                ]);
            }
        }

        return redirect()->route('admin.properties.index')
                         ->with('success', 'Property added successfully. Reference: ' . $reference_no);
    }

    // ============================================================
    // EDIT
    // ============================================================
    public function edit($id)
    {
        $property = DB::table('properties')->where('id', $id)->first();

        if (!$property) {
            return redirect()->route('admin.properties.index')
                             ->with('error', 'Property not found.');
        }

        $communities    = DB::table('communities')->where('is_active', 1)->orderBy('name')->get();
        $property_types = DB::table('property_types')->where('is_active', 1)->orderBy('sort_order')->get();
        $amenities      = DB::table('amenities')->orderBy('category')->orderBy('sort_order')->get();
        $gallery        = DB::table('property_images')->where('property_id', $id)->orderBy('sort_order')->get();

        // Get selected amenity IDs
        $selectedAmenities = DB::table('property_amenities')
                               ->where('property_id', $id)
                               ->pluck('amenity_id')
                               ->toArray();

        return view('admin.properties.edit', compact(
            'property',
            'communities',
            'property_types',
            'amenities',
            'gallery',
            'selectedAmenities'
        ));
    }

    // ============================================================
    // UPDATE
    // ============================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'listing_type'     => 'required|in:sale,rent,off_plan,international,private',
            'property_type_id' => 'nullable|exists:property_types,id',
            'community_id'     => 'nullable|exists:communities,id',
            'price'            => 'nullable|numeric',
            'bedrooms'         => 'nullable|integer',
            'bathrooms'        => 'nullable|integer',
            'area_sqft'        => 'nullable|numeric',
        ]);

        $property = DB::table('properties')->where('id', $id)->first();

        // Handle main image
        $mainImage = $property->main_image;
        if ($request->hasFile('main_image')) {
            if ($mainImage && file_exists(public_path($mainImage))) {
                unlink(public_path($mainImage));
            }
            $file      = $request->file('main_image');
            $name      = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/properties'), $name);
            $mainImage = 'uploads/properties/' . $name;
        }

        // Handle floor plan
        $floorPlan = $property->floor_plan_image;
        if ($request->hasFile('floor_plan_image')) {
            if ($floorPlan && file_exists(public_path($floorPlan))) {
                unlink(public_path($floorPlan));
            }
            $file      = $request->file('floor_plan_image');
            $name      = time() . '_fp_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/properties'), $name);
            $floorPlan = 'uploads/properties/' . $name;
        }

        // Handle brochure
        $brochure = $property->brochure_pdf;
        if ($request->hasFile('brochure_pdf')) {
            if ($brochure && file_exists(public_path($brochure))) {
                unlink(public_path($brochure));
            }
            $file     = $request->file('brochure_pdf');
            $name     = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/properties/brochures'), $name);
            $brochure = 'uploads/properties/brochures/' . $name;
        }

        DB::table('properties')->where('id', $id)->update([
            'title'             => trim($request->title),
            'slug'              => Str::slug($request->title),
            'listing_type'      => $request->listing_type,
            'property_type_id'  => $request->property_type_id,
            'community_id'      => $request->community_id,
            'address'           => $request->address,
            'city'              => $request->city ?? 'Dubai',
            'country'           => $request->country ?? 'UAE',
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'google_maps_link'  => $request->google_maps_link,
            'bedrooms'          => $request->bedrooms,
            'bathrooms'         => $request->bathrooms,
            'area_sqft'         => $request->area_sqft,
            'plot_area_sqft'    => $request->plot_area_sqft,
            'floor_number'      => $request->floor_number,
            'total_floors'      => $request->total_floors,
            'parking_spaces'    => $request->parking_spaces,
            'furnished'         => $request->furnished,
            'price'             => $request->price,
            'price_currency'    => $request->price_currency ?? 'AED',
            'price_on_request'  => $request->has('price_on_request') ? 1 : 0,
            'service_charge'    => $request->service_charge,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'main_image'        => $mainImage,
            'video_url'         => $request->video_url,
            'virtual_tour_url'  => $request->virtual_tour_url,
            'floor_plan_image'  => $floorPlan,
            'brochure_pdf'      => $brochure,
            'is_featured'       => $request->has('is_featured') ? 1 : 0,
            'is_exclusive'      => $request->has('is_exclusive') ? 1 : 0,
            'is_new'            => $request->has('is_new') ? 1 : 0,
            'status'            => $request->status ?? 'active',
            'meta_title'        => $request->meta_title,
            'meta_desc'         => $request->meta_desc,
            'meta_keywords'     => $request->meta_keywords,
            'updated_at'        => now(),
        ]);

        // Handle new gallery images
        if ($request->hasFile('gallery')) {
            $lastOrder = DB::table('property_images')
                           ->where('property_id', $id)
                           ->max('sort_order') ?? 0;

            foreach ($request->file('gallery') as $index => $file) {
                $name = time() . '_' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/properties/gallery'), $name);
                DB::table('property_images')->insert([
                    'property_id' => $id,
                    'image_path'  => 'uploads/properties/gallery/' . $name,
                    'is_primary'  => 0,
                    'sort_order'  => $lastOrder + $index + 1,
                    'created_at'  => now(),
                ]);
            }
        }

        // Update amenities — delete old, insert new
        DB::table('property_amenities')->where('property_id', $id)->delete();
        if ($request->amenities) {
            foreach ($request->amenities as $amenityId) {
                DB::table('property_amenities')->insert([
                    'property_id' => $id,
                    'amenity_id'  => $amenityId,
                ]);
            }
        }

        return redirect()->route('admin.properties.index')
                         ->with('success', 'Property updated successfully.');
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function delete($id)
    {
        $property = DB::table('properties')->where('id', $id)->first();

        if (!$property) {
            return redirect()->route('admin.properties.index')
                             ->with('error', 'Property not found.');
        }

        // Delete main image
        if ($property->main_image && file_exists(public_path($property->main_image))) {
            unlink(public_path($property->main_image));
        }

        // Delete gallery images
        $gallery = DB::table('property_images')->where('property_id', $id)->get();
        foreach ($gallery as $img) {
            if (file_exists(public_path($img->image_path))) {
                unlink(public_path($img->image_path));
            }
        }

        // Delete from all related tables
        DB::table('property_images')->where('property_id', $id)->delete();
        DB::table('property_amenities')->where('property_id', $id)->delete();
        DB::table('properties')->where('id', $id)->delete();

        return redirect()->route('admin.properties.index')
                         ->with('success', 'Property deleted successfully.');
    }

    // ============================================================
    // DELETE GALLERY IMAGE
    // ============================================================
    public function deleteGalleryImage($id)
    {
        $image = DB::table('property_images')->where('id', $id)->first();

        if ($image) {
            if (file_exists(public_path($image->image_path))) {
                unlink(public_path($image->image_path));
            }
            DB::table('property_images')->where('id', $id)->delete();
        }

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    // ============================================================
    // TOGGLE FEATURED
    // ============================================================
    public function toggleFeatured($id)
    {
        $property = DB::table('properties')->where('id', $id)->first();

        DB::table('properties')->where('id', $id)->update([
            'is_featured' => $property->is_featured ? 0 : 1,
            'updated_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Featured status updated.');
    }

    // ============================================================
    // TOGGLE STATUS
    // ============================================================
    public function toggleStatus($id)
    {
        $property = DB::table('properties')->where('id', $id)->first();

        $newStatus = $property->status === 'active' ? 'inactive' : 'active';

        DB::table('properties')->where('id', $id)->update([
            'status'     => $newStatus,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}