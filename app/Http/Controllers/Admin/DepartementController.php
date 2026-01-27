<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departement; // gunakan PascalCase (best practice)
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search  = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        // LIST + SEARCH + PAGINATION
        $departements = Departement::when($search, function ($query) use ($search) {
                $query->where('departement_name', 'like', "%{$search}%");
            })
            ->orderBy('departement_name', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.departement.index', compact(
            'departements',
            'search',
            'perPage'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.departement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'departement_name' => 'required|string|max:255|unique:departement,departement_name',
            'description'      => 'nullable|string',
            'condition'        => 'required|in:normal,broken',
        ]);

        /*
         |--------------------------------------------------------------------------
         | AUTO GENERATE CODE DEPARTMENT
         | Format: DEPT001, DEPT002, dst
         |--------------------------------------------------------------------------
         */
        $prefix = 'DEPT-';

        $lastDepartement = Departement::where('code_dept', 'like', $prefix . '%')
            ->orderBy('code_dept', 'desc')
            ->first();

        if ($lastDepartement) {
            $lastNumber = (int) substr($lastDepartement->code_dept, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $finalCode = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // SIMPAN DATA
        Departement::create([
            'code_dept'        => $finalCode,
            'departement_name' => $request->departement_name,
            'description'      => $request->description,
            'is_active'        => $request->condition === 'normal' ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.departement.index')
            ->with('success', 'Departement added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement)
    {
        return view('admin.departement.show', compact('departement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departement $departement)
    {
        return view('admin.departement.edit', compact('departement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departement $departement)
    {
        $request->validate([
            'departement_name' => 'required|string|max:255|unique:departement,departement_name,' . $departement->id,
            'description'      => 'nullable|string',
            'condition'        => 'required|in:normal,broken',
        ]);

        $departement->update([
            'departement_name' => $request->departement_name,
            'description'      => $request->description,
            'is_active'        => $request->condition === 'normal' ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.departement.index')
            ->with('success', 'Departement updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departement $departement)
    {
        $departement->delete();

        return redirect()
            ->route('admin.departement.index')
            ->with('success', 'Master Departement berhasil dihapus.');
    }
}
