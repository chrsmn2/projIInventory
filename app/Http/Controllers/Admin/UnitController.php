<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->per_page ?? 10;

        $units = Unit::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.units.index', compact('units', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:units,name',
            'description' => 'nullable|string',
        ], [
            'name.unique' => 'Unit name already exists. Please choose a different name.',
            'name.required' => 'Unit name is required.',
            'name.max' => 'Unit name cannot exceed 100 characters.',
        ]);

        // 1. Ambil prefix dari departement_name (karena $request->name tidak ada di form Anda)
        // 2. TENTUKAN PREFIX PERMANEN
        $prefix = 'UNT-';

        // 3. CARI NOMOR URUT TERAKHIR
        // Mengambil supplier dengan kode berawalan VEN yang urutannya paling besar
        $lastunit = Unit::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastunit) {
            // Mengambil angka setelah 'UNT' (karakter ke-4 dan seterusnya)
            $lastNumber = intval(substr($lastunit->code, 4));
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data sama sekali di database
            $nextNumber = 1;
        }

        // 4. FORMAT MENJADI 6 KARAKTER (UNT + 001)
        $finalCode = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        Unit::create([
            'code' => $finalCode,
            'name' => $request->name,
            'description' => $request->description,
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
            'name' => 'required|string|max:100|unique:units,name,' . $unit->id,
            'description' => 'nullable|string',
        ], [
            'name.unique' => 'Unit name already exists. Please choose a different name.',
            'name.required' => 'Unit name is required.',
            'name.max' => 'Unit name cannot exceed 100 characters.',
        ]);

        // CODE TIDAK DIUPDATE
        $unit->update([
            'name' => $request->name,
            'description' => $request->description,
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
}
