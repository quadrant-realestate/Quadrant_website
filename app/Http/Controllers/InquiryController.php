<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InquiryController extends Controller
{
    // ============================================================
    // INDEX
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('inquiries')
                    ->leftJoin('properties', 'inquiries.property_id', '=', 'properties.id')
                    ->select(
                        'inquiries.*',
                        'properties.title as property_title',
                        'properties.reference_no as property_ref'
                    );

        // Filter by status
        if ($request->status) {
            $query->where('inquiries.status', $request->status);
        }

        // Filter by type
        if ($request->inquiry_type) {
            $query->where('inquiries.inquiry_type', $request->inquiry_type);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('inquiries.name', 'like', '%' . $request->search . '%')
                  ->orWhere('inquiries.email', 'like', '%' . $request->search . '%')
                  ->orWhere('inquiries.phone', 'like', '%' . $request->search . '%');
            });
        }

        $inquiries = $query->orderBy('inquiries.created_at', 'desc')->get();

        // Count by status for badges
        $counts = [
            'all'        => DB::table('inquiries')->count(),
            'new'        => DB::table('inquiries')->where('status', 'new')->count(),
            'contacted'  => DB::table('inquiries')->where('status', 'contacted')->count(),
            'qualified'  => DB::table('inquiries')->where('status', 'qualified')->count(),
            'closed'     => DB::table('inquiries')->where('status', 'closed')->count(),
        ];

        return view('admin.inquiries.index', compact('inquiries', 'counts'));
    }

    // ============================================================
    // SHOW
    // ============================================================
    public function show($id)
    {
        $inquiry = DB::table('inquiries')
                      ->leftJoin('properties', 'inquiries.property_id', '=', 'properties.id')
                      ->select(
                          'inquiries.*',
                          'properties.title as property_title',
                          'properties.reference_no as property_ref',
                          'properties.main_image as property_image'
                      )
                      ->where('inquiries.id', $id)
                      ->first();

        if (!$inquiry) {
            return redirect()->route('admin.inquiries.index')
                             ->with('error', 'Inquiry not found.');
        }

        // Mark as contacted if still new
        if ($inquiry->status === 'new') {
            DB::table('inquiries')->where('id', $id)->update([
                'status'     => 'contacted',
                'updated_at' => now(),
            ]);
            $inquiry->status = 'contacted';
        }

        return view('admin.inquiries.show', compact('inquiry'));
    }

    // ============================================================
    // UPDATE STATUS
    // ============================================================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,qualified,closed',
        ]);

        DB::table('inquiries')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Inquiry status updated successfully.');
    }

    // ============================================================
    // UPDATE NOTES
    // ============================================================
    public function updateNotes(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        DB::table('inquiries')->where('id', $id)->update([
            'notes'      => $request->notes,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Notes saved successfully.');
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function delete($id)
    {
        DB::table('inquiries')->where('id', $id)->delete();

        return redirect()->route('admin.inquiries.index')
                         ->with('success', 'Inquiry deleted successfully.');
    }
}