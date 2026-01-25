<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\IncomingItem;
use App\Models\OutgoingItem;
use App\Models\Departement;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Main Stats
        $totalItems = Item::count();
        $totalIncoming = IncomingItem::count();
        $totalOutgoing = OutgoingItem::count();
        
        // Stock Alert - items dibawah minimum stok
        $lowStockCount = Item::where('stock', '<', \DB::raw('min_stock'))
            ->count();
        
        // Total nilai stok yang rusak/hilang
        $damagedItems = Item::where('condition', 'damaged')->count();
        $lostItems = Item::where('condition', 'lost')->count();

        // Master Data Stats
        $totalCategories = Category::count();
        $totalDepartments = Departement::where('is_active', 1)->count();
        $totalVendors = Supplier::count();
        $totalUnits = Unit::count();

        // Recent Data
        $recentIncoming = IncomingItem::with('details')
            ->orderBy('incoming_date', 'desc')
            ->limit(8)
            ->get();

        $recentOutgoing = OutgoingItem::with('details')
            ->orderBy('outgoing_date', 'desc')
            ->limit(8)
            ->get();

        // Stock Alert Items - stok dibawah minimum
        $stockAlertItems = Item::with('category', 'unit')
            ->where('stock', '<', \DB::raw('min_stock'))
            ->orderBy('stock', 'asc')
            ->limit(15)
            ->get();

        // Stock Summary by Category
        $stockByCategory = Item::selectRaw('category_id, COUNT(*) as item_count, SUM(stock) as total_stock, SUM(min_stock) as total_min_stock')
            ->with('category')
            ->groupBy('category_id')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalItems',
            'totalIncoming',
            'totalOutgoing',
            'lowStockCount',
            'damagedItems',
            'lostItems',
            'totalCategories',
            'totalDepartments',
            'totalVendors',
            'totalUnits',
            'recentIncoming',
            'recentOutgoing',
            'stockAlertItems',
            'stockByCategory'
        ));
    }
}
