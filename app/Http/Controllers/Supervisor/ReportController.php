<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Category;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display loan report for supervisor
     */
    public function loanReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', '');
        $perPage = $request->per_page ?? 15;

        $loans = Loan::with(['user', 'item', 'approvedBy'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Statistics
        $totalLoans = $loans->total();
        $approvedLoans = $loans->where('status', 'approved')->count();
        $rejectedLoans = $loans->where('status', 'rejected')->count();
        $pendingLoans = $loans->where('status', 'pending')->count();

        return view('supervisor.reports.loan', compact(
            'loans',
            'startDate',
            'endDate',
            'status',
            'perPage',
            'totalLoans',
            'approvedLoans',
            'rejectedLoans',
            'pendingLoans'
        ));
    }

    /**
     * Display stock report for supervisor
     */
    public function stockReport(Request $request)
    {
        $category = $request->input('category', '');
        $condition = $request->input('condition', '');
        $perPage = $request->per_page ?? 15;

        $items = Item::with('category')
            ->when($category, function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->when($condition, function ($query) use ($condition) {
                $query->where('condition', $condition);
            })
            ->orderBy('item_name')
            ->paginate($perPage)
            ->withQueryString();

        $categories = Category::all();

        // Statistics
        $totalItems = $items->total();
        $totalStock = $items->sum('stock');
        $lowStockCount = $items->where('stock', '<', 5)->where('stock', '>', 0)->count();
        $outOfStockCount = $items->where('stock', 0)->count();

        return view('supervisor.reports.stock', compact(
            'items',
            'category',
            'condition',
            'perPage',
            'categories',
            'totalItems',
            'totalStock',
            'lowStockCount',
            'outOfStockCount'
        ));
    }
}