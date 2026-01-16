<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Category;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $search = $request->input('search', '');
        $category = $request->input('category', '');
        $perPage = $request->per_page ?? 15;

        $items = Item::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('item_name', 'like', "%{$search}%")
                      ->orWhere('item_code', 'like', "%{$search}%");
            })
            ->when($category, callback: function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->orderBy('item_name')
            ->paginate($perPage)
            ->withQueryString();

        // Calculate statistics
        $allItems = Item::all();
        $totalItems = $allItems->count();
        $totalStock = $allItems->sum('stock');
        $lowStockCount = $allItems->where('stock', '<', 5)->where('stock', '>', 0)->count();
        $outOfStockCount = $allItems->where('stock', 0)->count();

        $categories = Category::all();

        return view('admin.reports.stock', compact(
            'items',
            'search',
            'category',
            'perPage',
            'categories',
            'totalItems',
            'totalStock',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function loan(Request $request)
    {
        $status = $request->input('status', '');
        $perPage = $request->per_page ?? 15;

        $loans = Loan::with(['admin', 'details'])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('loan_date', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.reports.loan', compact('loans', 'status', 'perPage'));
    }
}
