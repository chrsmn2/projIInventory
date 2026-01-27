<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanMonitorController extends Controller
{
    /**
     * Display loan monitoring dashboard for supervisor
     */
    public function index(Request $request)
    {
        $status = $request->input('status', '');
        $search = $request->input('search', '');
        $perPage = $request->per_page ?? 15;

        $loans = Loan::with(['user', 'item', 'approvedBy'])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('loan_code', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('item', function ($itemQuery) use ($search) {
                          $itemQuery->where('item_name', 'like', "%{$search}%");
                      });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Statistics
        $totalLoans = Loan::count();
        $pendingLoans = Loan::where('status', 'pending')->count();
        $approvedLoans = Loan::where('status', 'approved')->count();
        $rejectedLoans = Loan::where('status', 'rejected')->count();
        $returnedLoans = Loan::where('status', 'returned')->count();

        return view('supervisor.loan-monitor.index', compact(
            'loans',
            'status',
            'search',
            'perPage',
            'totalLoans',
            'pendingLoans',
            'approvedLoans',
            'rejectedLoans',
            'returnedLoans'
        ));
    }
}