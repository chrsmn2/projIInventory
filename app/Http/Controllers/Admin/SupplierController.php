<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers
     */
    public function index(Request $request)
    {
        $search  = $request->string('search')->trim();
        $perPage = $request->integer('per_page', 5);
        $sortBy  = $request->get('sort_by', 'supplier_name');
        $sortDir = $request->get('sort_dir', 'asc');

        $allowedSorts = ['supplier_code', 'supplier_name', 'created_at'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'supplier_name';
        }

        $suppliers = Supplier::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('supplier_name', 'like', "%{$search}%")
                        ->orWhere('supplier_code', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.suppliers.index', compact(
            'suppliers',
            'search',
            'perPage',
            'sortBy',
            'sortDir'
        ));
    }

    /**
     * Show the form for creating a new supplier
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created supplier in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'supplier_name' => 'required|string|max:255|unique:suppliers,supplier_name',
        'contact_phone' => 'required|string|max:20',
        'contact_email' => 'required|email|max:255|unique:suppliers,contact_email',
        'address'       => 'nullable|string',
        'status'        => 'required|in:active,inactive',
    ]);


        // ===== Generate Supplier Code (VEN-001) =====
        $prefix = 'VEN-';

        $lastSupplier = Supplier::where('supplier_code', 'like', $prefix . '%')
            ->orderBy('supplier_code', 'desc')
            ->first();

        $nextNumber = $lastSupplier
            ? intval(substr($lastSupplier->supplier_code, 4)) + 1
            : 1;

        $supplierCode = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Supplier::create([
            'supplier_code' => $supplierCode,
            'supplier_name' => $validated['supplier_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'address'       => $validated['address'],
            'status'        => $validated['status'],
        ]);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', '✓ Vendor added successfully');
    }

    /**
     * Show the form for editing the specified supplier
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'supplier_name'  => 'required|string|max:255|unique:suppliers,supplier_name,' . $supplier->id,
            'contact_phone'  => 'required|string|max:20',
            'contact_email'  => 'required|email|max:255|unique:suppliers,contact_email,' . $supplier->id,
            'address'        => 'required|string',
            'status'         => 'required|in:active,inactive',
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', '✓ Vendor updated successfully');
    }

    /**
     * Remove the specified supplier from storage
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', '✓ Vendor deleted successfully');
    }

    /**
     * AJAX Select2 - Search Suppliers
     */
    public function searchSuppliers(Request $request)
    {
        $search = $request->input('q', '');

        $suppliers = Supplier::where('status', 'active')
            ->when($search, function ($q) use ($search) {
                $q->where('supplier_name', 'like', "%{$search}%")
                  ->orWhere('supplier_code', 'like', "%{$search}%");
            })
            ->orderBy('supplier_name')
            ->limit(20)
            ->get();

        return response()->json([
            'results' => $suppliers->map(function ($supplier) {
                return [
                    'id'   => $supplier->id,
                    'text' => $supplier->supplier_name,
                    'code' => $supplier->supplier_code,
                ];
            })
        ]);
    }

    /**
     * AJAX check supplier name (real-time validation)
     */
    public function checkSupplierName(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'id'   => 'nullable|integer',
        ]);

        $exists = Supplier::where('supplier_name', $request->name)
            ->when($request->id, fn ($q) => $q->where('id', '!=', $request->id))
            ->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists
                ? 'Vendor name already exists. Please choose a different name.'
                : 'Vendor name available.',
        ]);
    }
}
