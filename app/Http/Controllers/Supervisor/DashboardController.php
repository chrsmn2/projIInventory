<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Item;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingLoans = Loan::where('status', 'pending')
            ->with(['borrower', 'details.item'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $pendingCount = Loan::where('status', 'pending')->count();

        $lowStockItems = Item::whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        $totalItems = Item::count();

        return view('supervisor.dashboard', compact(
            'pendingLoans',
            'pendingCount',
            'lowStockItems',
            'totalItems'
        ));
    }
}
