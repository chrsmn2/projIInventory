<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display stock monitoring for supervisor
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $category = $request->input('category', '');
        $perPage = $request->per_page ?? 15;

        $items = Item::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('item_name', 'like', "%{$search}%")
                      ->orWhere('item_code', 'like', "%{$search}%");
            })
            ->when($category, function ($query) use ($category) {
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

        return view('supervisor.stock.index', compact(
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
}