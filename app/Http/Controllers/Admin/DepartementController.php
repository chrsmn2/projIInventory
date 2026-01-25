<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\departement; // Pastikan nama file di Models adalah departement.php
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->per_page ?? 10;

        $items = departement::when($search, function ($query) use ($search) {
                $query->where('departement_name', 'like', "%{$search}%");
            })
            ->orderBy('departement_name')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.departement.index', compact('items', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.departement.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'departement_name' => 'required|string|max:255|unique:departement,departement_name',
            'description'      => 'nullable|string',
            'condition'        => 'required|in:normal,broken',
        ]);

        // 1. Ambil prefix dari departement_name (karena $request->name tidak ada di form Anda)
        // 2. TENTUKAN PREFIX PERMANEN
        $prefix = 'DEPT';

        // 3. CARI NOMOR URUT TERAKHIR
        // Mengambil supplier dengan kode berawalan VEN yang urutannya paling besar
        $lastdepartement = departement::where('code_dept', 'like', $prefix . '%')
            ->orderBy('code_dept', 'desc')
            ->first();

        if ($lastdepartement) {
            // Mengambil angka setelah 'DEPT' (karakter ke-4 dan seterusnya)
            $lastNumber = intval(substr($lastdepartement->code_dept, 4));
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data sama sekali di database
            $nextNumber = 1;
        }

        // 4. FORMAT MENJADI 6 KARAKTER (DEPT + 001)
        $finalCode = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // 4. Simpan ke database
        departement::create([
            'code_dept'        => $finalCode,
            'departement_name' => $request->departement_name,
            'description'      => $request->description,
            'is_active'        => ($request->condition === 'normal') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.departement.index')
            ->with('success', 'Departement added successfully');
    }

    public function show(departement $departement)
    {
        return view('admin.departement.show', compact('departement'));
    }

    public function edit(departement $departement)
    {
        return view('admin.departement.edit', compact('departement'));
    }

    public function update(Request $request, departement $departement)
    {
        $request->validate([
            'departement_name' => 'required|string|max:255|unique:departement,departement_name,' . $departement->id,
            'description'      => 'nullable|string',
            'condition'        => 'required|in:normal,damaged,broken', // Sesuaikan dengan value di view
        ]);

        // Update data
        $departement->update([
            'departement_name' => $request->departement_name,
            'description'      => $request->description,
            'is_active'        => ($request->condition === 'normal') ? 1 : 0,
        ]);

        return redirect()->route('admin.departement.index')
            ->with('success', 'Departement updated successfully');
    }

    public function destroy(departement $departement)
    {

        $departement->delete();

        return redirect()->route('admin.departement.index')->with('success', 'Master Departement berhasil dihapus.');
    }
}