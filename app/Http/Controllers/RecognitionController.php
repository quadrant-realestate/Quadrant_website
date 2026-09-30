<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecognitionController extends Controller
{
    // ============================================================
    // INDEX
    // ============================================================
    public function index()
    {
        $recognitions = DB::table('recognitions')
                           ->orderBy('sort_order', 'asc')
                           ->orderBy('id', 'desc')
                           ->get();

        return view('admin.recognitions.index', compact('recognitions'));
    }

    // ============================================================
    // CREATE
    // ============================================================
    public function create()
    {
        return view('admin.recognitions.create');
    }

    // ============================================================
    // STORE
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'media_name' => 'required|string|max:150',
            'url'        => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        // Logo upload
        $logo = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = time() . '_logo_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/recognitions'), $name);
            $logo = 'uploads/recognitions/' . $name;
        }

        DB::table('recognitions')->insert([
            'media_name' => trim($request->media_name),
            'logo'       => $logo,
            'url'        => $request->url,
            'is_active'  => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.recognitions.index')
                         ->with('success', 'Recognition added successfully.');
    }

    // ============================================================
    // EDIT
    // ============================================================
    public function edit($id)
    {
        $recognition = DB::table('recognitions')->where('id', $id)->first();

        if (!$recognition) {
            return redirect()->route('admin.recognitions.index')
                             ->with('error', 'Recognition not found.');
        }

        return view('admin.recognitions.edit', compact('recognition'));
    }

    // ============================================================
    // UPDATE
    // ============================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'media_name' => 'required|string|max:150',
            'url'        => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $recognition = DB::table('recognitions')->where('id', $id)->first();

        // Handle logo upload
        $logo = $recognition->logo;
        if ($request->hasFile('logo')) {
            if ($logo && file_exists(public_path($logo))) {
                unlink(public_path($logo));
            }
            $file = $request->file('logo');
            $name = time() . '_logo_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/recognitions'), $name);
            $logo = 'uploads/recognitions/' . $name;
        }

        DB::table('recognitions')->where('id', $id)->update([
            'media_name' => trim($request->media_name),
            'logo'       => $logo,
            'url'        => $request->url,
            'is_active'  => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.recognitions.index')
                         ->with('success', 'Recognition updated successfully.');
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function delete($id)
    {
        $recognition = DB::table('recognitions')->where('id', $id)->first();

        if (!$recognition) {
            return redirect()->route('admin.recognitions.index')
                             ->with('error', 'Recognition not found.');
        }

        if ($recognition->logo && file_exists(public_path($recognition->logo))) {
            unlink(public_path($recognition->logo));
        }

        DB::table('recognitions')->where('id', $id)->delete();

        return redirect()->route('admin.recognitions.index')
                         ->with('success', 'Recognition deleted successfully.');
    }

    // ============================================================
    // TOGGLE STATUS
    // ============================================================
    public function toggleStatus($id)
    {
        $recognition = DB::table('recognitions')->where('id', $id)->first();

        if (!$recognition) {
            return redirect()->route('admin.recognitions.index')
                             ->with('error', 'Recognition not found.');
        }

        DB::table('recognitions')->where('id', $id)->update([
            'is_active' => $recognition->is_active ? 0 : 1,
        ]);

        return redirect()->route('admin.recognitions.index')
                         ->with('success', 'Status updated successfully.');
    }
}