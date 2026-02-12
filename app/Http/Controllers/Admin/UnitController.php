<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->string('search')->trim();
        $perPage = $request->integer('per_page', 5);
        $sortBy  = $request->get('sort_by', 'unit_name');
        $sortDir = $request->get('sort_dir', 'asc');

        $allowedSorts = ['unit_code', 'unit_name', 'created_at'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'unit_name';
        }

        $units = Unit::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('unit_name', 'like', "%{$search}%")
                        ->orWhere('unit_code', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.units.index', compact(
            'units',
            'search',
            'perPage',
            'sortBy',
            'sortDir'
        ));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_name' => 'required|string|max:100|unique:units,unit_name',
            'unit_description' => 'nullable|string',
        ], [
            'unit_name.unique' => 'Unit name already exists. Please choose a different name.',
            'unit_name.required' => 'Unit name is required.',
            'unit_name.max' => 'Unit name cannot exceed 100 characters.',
        ]);

        // 1. Ambil prefix dari departement_name (karena $request->name tidak ada di form Anda)
        // 2. TENTUKAN PREFIX PERMANEN
        $prefix = 'UNT-';

        // 3. CARI NOMOR URUT TERAKHIR
        // Mengambil supplier dengan kode berawalan VEN yang urutannya paling besar
        $lastunit = Unit::where('unit_code', 'like', $prefix . '%')
            ->orderBy('unit_code', 'desc')
            ->first();

        if ($lastunit) {
            // Mengambil angka setelah 'UNT' (karakter ke-4 dan seterusnya)
            $lastNumber = intval(substr($lastunit->unit_code, 4));
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data sama sekali di database
            $nextNumber = 1;
        }

        // 4. FORMAT MENJADI 6 KARAKTER (UNT + 001)
        $finalCode = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        Unit::create([
            'unit_code' => $finalCode,
            'unit_name' => $request->unit_name,
            'unit_description' => $request->unit_description,
        ]);

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit added successfully');
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'unit_name' => 'required|string|max:100|unique:units,unit_name,' . $unit->id,
            'unit_description' => 'nullable|string',
        ], [
            'unit_name.unique' => 'Unit name already exists. Please choose a different name.',
            'unit_name.required' => 'Unit name is required.',
            'unit_name.max' => 'Unit name cannot exceed 100 characters.',
        ]);

        // CODE TIDAK DIUPDATE
        $unit->update([
            'unit_name' => $request->unit_name,
            'unit_description' => $request->unit_description,
        ]);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit updated successfully');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit deleted successfully');
    }

    public function checkUnitName(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'id'   => 'nullable|integer',
        ]);

        $name = $request->input('name');
        $id   = $request->input('id');

        $exists = Unit::where('unit_name', $name)
            ->when($id, fn($q) => $q->where('id', '!=', $id))
            ->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists 
                ? 'Unit name already exists. Please choose a different name' 
                : 'Unit name available',
        ]);
    }
}