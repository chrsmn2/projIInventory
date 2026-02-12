<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search  = $request->string('search')->trim();
        $perPage = $request->integer('per_page', 5);
        $sortBy  = $request->get('sort_by', 'departement_name');
        $sortDir = $request->get('sort_dir', 'asc');

        $allowedSorts = [
            'code_dept',
            'departement_name',
            'created_at',
        ];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'departement_name';
        }

        $departements = Departement::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('departement_name', 'like', "%{$search}%")
                        ->orWhere('code_dept', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.departement.index', compact(
            'departements',
            'search',
            'perPage',
            'sortBy',
            'sortDir'
        ));
    }

    public function create()
    {
        return view('admin.departement.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'departement_name' => 'required|string|max:255|unique:departement,departement_name',
            'description'      => 'nullable|string',
            'status'           => 'required|in:active,inactive',
        ]);

        $validated['code_dept'] = $this->generateCode();

        Departement::create($validated);

        return redirect()
            ->route('admin.departement.index')
            ->with('success', 'Departement added successfully');
    }

    public function edit(Departement $departement)
    {
        return view('admin.departement.edit', compact('departement'));
    }

    public function update(Request $request, Departement $departement)
    {
        $validated = $request->validate([
            'departement_name' => 'required|string|max:255|unique:departement,departement_name,' . $departement->id,
            'description'      => 'nullable|string',
            'status'           => 'required|in:active,inactive',
        ]);

        $departement->update($validated);

        return redirect()
            ->route('admin.departement.index')
            ->with('success', 'Departement updated successfully!');
    }

    public function destroy(Departement $departement)
    {
        $departement->delete();

        return redirect()
            ->route('admin.departement.index')
            ->with('success', 'Departement delete successfully!.');
    }

    /**
     * SELECT2 AJAX
     */
    public function searchDepartements(Request $request)
    {
        $search = $request->input('q');

        $departements = Departement::query()
            ->where('status', 'active')
            ->when($search, function ($q) use ($search) {
                $q->where('departement_name', 'like', "%{$search}%")
                  ->orWhere('code_dept', 'like', "%{$search}%");
            })
            ->orderBy('departement_name')
            ->limit(20)
            ->get();

        return response()->json([
            'results' => $departements->map(fn ($d) => [
                'id'   => $d->id,
                'text' => $d->departement_name,
                'code' => $d->code_dept,
            ]),
        ]);
    }

    /**
     * Helper generate code
     */
    private function generateCode(): string
    {
        $prefix = 'DEPT-';

        $last = Departement::where('code_dept', 'like', $prefix.'%')
            ->orderByDesc('code_dept')
            ->value('code_dept');

        $number = $last
            ? ((int) substr($last, strlen($prefix))) + 1
            : 1;

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
