<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search','');
        $perPage = $request->per_page ?? 10;

        $suppliers = Supplier::orderBy('supplier_name', 'asc')->paginate(10);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255|unique:suppliers,supplier_name',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255|unique:suppliers,contact_email',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive',
        ], [
            'supplier_name.unique' => 'This supplier name already exists.',
            'contact_email.unique' => 'This email is already registered.',
            'contact_phone.required' => 'Phone number is required.',
            'contact_email.required' => 'Email is required.',
        ]);

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')
                         ->with('success', '✓ Supplier added successfully!');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255|unique:suppliers,supplier_name,' . $supplier->id,
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255|unique:suppliers,contact_email,' . $supplier->id,
            'address' => 'required|string',
            'status' => 'required|in:active,inactive',
        ], [
            'supplier_name.unique' => 'This supplier name already exists.',
            'contact_email.unique' => 'This email is already registered.',
            'contact_phone.required' => 'Phone number is required.',
            'contact_email.required' => 'Email is required.',
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')
                         ->with('success', '✓ Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
                         ->with('success', '✓ Supplier deleted successfully!');
    }
}