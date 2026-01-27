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

        $suppliers = Supplier::where('supplier_name', 'like', "%$search%")
        ->orWhere('supplier_code', 'like', "%$search%")
        ->orderBy('supplier_name', 'asc')
        ->paginate($perPage);
        return view('admin.suppliers.index', compact('suppliers','search'));
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
            'supplier_name.unique' => 'This supplier name already exists. Please choose a different name.',
            'supplier_name.required' => 'Supplier name is required.',
            'supplier_name.max' => 'Supplier name cannot exceed 255 characters.',
            'contact_phone.required' => 'Contact phone is required.',
            'contact_phone.max' => 'Contact phone cannot exceed 20 characters.',
            'contact_email.required' => 'Contact email is required.',
            'contact_email.email' => 'Please enter a valid email address.',
            'contact_email.max' => 'Contact email cannot exceed 255 characters.',
            'contact_email.unique' => 'This email is already registered. Please use a different email.',
            'address.required' => 'Address is required.',
            'status.required' => 'Please select supplier status.',
            'status.in' => 'Invalid status selected.',
        ]);

        $validated['is_active'] = ($request->condition === 'normal') ? 1 : 0;

        // 2. TENTUKAN PREFIX PERMANEN
        $prefix = 'VEN-';

        // 3. CARI NOMOR URUT TERAKHIR
        // Mengambil supplier dengan kode berawalan VEN yang urutannya paling besar
        $lastSupplier = Supplier::where('supplier_code', 'like', $prefix . '%')
            ->orderBy('supplier_code', 'desc')
            ->first();

        if ($lastSupplier) {
            // Mengambil angka setelah 'VEN' (karakter ke-4 dan seterusnya)
            $lastNumber = intval(substr($lastSupplier->supplier_code, 4));
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data sama sekali di database
            $nextNumber = 1;
        }

        // 4. FORMAT MENJADI 6 KARAKTER (VEN + 001)
        $finalCode = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Supplier::create([
            'supplier_code' => $finalCode,
            'supplier_name' => $request->supplier_name,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Vendors added successfully');
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

        // Konversi input radio 'condition' ke kolom 'is_active'
        $validated['is_active'] = ($request->condition === 'normal') ? 1 : 0;

        $supplier->update([
            'supplier_name' => $request->supplier_name,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.suppliers.index')
                         ->with('success', '✓ Vendors updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
                         ->with('success', '✓ Vendors deleted successfully!');
    }
}
