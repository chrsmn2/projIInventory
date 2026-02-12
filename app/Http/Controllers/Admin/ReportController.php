<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Category;
use App\Models\IncomingItem;
use App\Models\OutgoingItem;
use App\Models\Supplier;
use App\Exports\StockReportExport;
use App\Exports\MovementReportExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $search = $request->input('search', '');
        $category = $request->input('category', '');
        $reportType = $request->input('report_type', 'all'); // all, low, out_of_stock, damaged
        $condition = $request->input('condition', ''); // good, damaged
        $perPage = $request->per_page ?? 15;

        // Base query
        $query = Item::with(['category', 'unit']);

        // Apply filters
        $query->when($search, function ($query) use ($search) {
            $query->where('item_name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
        })
        ->when($category, function ($query) use ($category) {
            $query->where('category_id', $category);
        })
        ->when($condition, function ($query) use ($condition) {
            $query->where('condition', $condition);
        })
        ->when($reportType === 'low', function ($query) {
            $query->whereRaw('stock < min_stock AND stock > 0');
        })
        ->when($reportType === 'out_of_stock', function ($query) {
            $query->where('stock', 0);
        })
        ->when($reportType === 'damaged', function ($query) {
            $query->where('condition', 'damaged');
        })
        ->orderBy('item_name');

        // Paginate
        $items = $query->paginate($perPage)->withQueryString();

        // Calculate statistics (from all items for context)
        $allItems = Item::all();
        $totalItems = $allItems->count();
        $totalStock = $allItems->sum('stock');
        $lowStockCount = $allItems->where('stock', '<', $allItems->min('min_stock') ?? 5)
            ->where('stock', '>', 0)->count();
        $outOfStockCount = $allItems->where('stock', 0)->count();
        $damagedCount = $allItems->where('condition', 'damaged')->count();

        $categories = Category::all();

        // Handle export
        if ($request->has('export') && $request->export === 'excel') {
            $exportItems = $query->get();
            return Excel::download(
                new StockReportExport($exportItems, $reportType),
                'stock-report-' . now()->format('Y-m-d-His') . '.xlsx'
            );
        }

        return view('admin.reports.stock', compact(
            'items',
            'search',
            'category',
            'reportType',
            'condition',
            'perPage',
            'categories',
            'totalItems',
            'totalStock',
            'lowStockCount',
            'outOfStockCount',
            'damagedCount'
        ));
    }

    public function movement(Request $request)
    {
        $type = $request->input('type', 'incoming'); // incoming, outgoing
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $supplier = $request->input('supplier', '');
        $perPage = $request->per_page ?? 15;

        if ($type === 'outgoing') {
            $query = OutgoingItem::with(['admin', 'departement', 'details.item', 'details.unit'])
                ->whereMonth('outgoing_date', $month)
                ->whereYear('outgoing_date', $year);
        } else {
            $query = IncomingItem::with(['admin', 'supplier', 'details.item', 'details.unit'])
                ->whereMonth('incoming_date', $month)
                ->whereYear('incoming_date', $year)
                ->when($supplier, function ($query) use ($supplier) {
                    $query->where('supplier_id', $supplier);
                });
        }

        $movements = $query->orderBy(
            $type === 'outgoing' ? 'outgoing_date' : 'incoming_date', 
            'desc'
        )->paginate($perPage)->withQueryString();

        // Get suppliers for filter
        $suppliers = Supplier::orderBy('supplier_name')->get();

        // Handle export
        if ($request->has('export') && $request->export === 'excel') {
            $exportMovements = $query->get();
            return Excel::download(
                new MovementReportExport($exportMovements, $type),
                $type . '-report-' . now()->format('Y-m-d-His') . '.xlsx'
            );
        }

        return view('admin.reports.movement', compact(
            'movements',
            'type',
            'month',
            'year',
            'supplier',
            'suppliers',
            'perPage'
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
