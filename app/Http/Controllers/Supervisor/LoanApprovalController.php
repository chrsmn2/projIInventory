<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanApproval;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoanApprovalController extends Controller
{
    /**
     * ===============================
     * LIST PENDING LOANS
     * ===============================
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $approvals = LoanApproval::with(['loan.details.item', 'supervisor'])
            ->where('supervisor_id', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->whereHas('loan', function ($q) use ($search) {
                    $q->where('loan_code', 'like', "%{$search}%")
                      ->orWhere('requester_name', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('supervisor.loan-approvals.index', compact('approvals', 'search', 'status'));
    }

    /**
     * ===============================
     * VIEW LOAN DETAIL
     * ===============================
     */
    public function show($id)
    {
        $approval = LoanApproval::with(['loan.details.item', 'supervisor'])
            ->findOrFail($id);
        
        // Check if user is supervisor
        if ($approval->supervisor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('supervisor.loan-approvals.show', compact('approval'));
    }

    /**
     * ===============================
     * APPROVE LOAN
     * ===============================
     */
    public function approve(Request $request, $id)
    {
        $approval = LoanApproval::findOrFail($id);

        // Validasi supervisor
        if ($approval->supervisor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Cegah double approve
        if ($approval->status !== 'pending') {
            return back()->with('error', 'Loan sudah diproses');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($approval, $request) {
            $loan = $approval->loan;

            // Validasi stok sebelum approve
            foreach ($loan->details as $detail) {
                $item = Item::lockForUpdate()->find($detail->item_id);

                if ($item->stock < $detail->quantity) {
                    throw new \Exception(
                        "Stok tidak cukup untuk {$item->item_name}. " .
                        "Tersedia: {$item->stock}, Diminta: {$detail->quantity}"
                    );
                }

                // Kurangi stok
                $item->decrement('stock', $detail->quantity);
            }

            // Update approval
            $approval->update([
                'status' => 'approved',
                'notes' => $request->notes,
                'approved_at' => now(),
            ]);

            // Update loan status
            $loan->update([
                'status' => 'approved',
            ]);
        });

        return redirect()->route('supervisor.loan-approvals.index')
                       ->with('success', 'Peminjaman berhasil disetujui');
    }

    /**
     * ===============================
     * REJECT LOAN
     * ===============================
     */
    public function reject(Request $request, $id)
    {
        $approval = LoanApproval::findOrFail($id);

        // Validasi supervisor
        if ($approval->supervisor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Cegah double reject
        if ($approval->status !== 'pending') {
            return back()->with('error', 'Loan sudah diproses');
        }

        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($approval, $request) {
            $approval->update([
                'status' => 'rejected',
                'notes' => $request->notes,
                'approved_at' => now(),
            ]);

            // Update loan status
            $approval->loan->update([
                'status' => 'rejected',
            ]);
        });

        return redirect()->route('supervisor.loan-approvals.index')
                       ->with('success', 'Peminjaman ditolak');
    }
}
