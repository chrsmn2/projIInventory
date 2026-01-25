<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requester;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequesterController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 10;
        $search = $request->search;

        $requesters = Requester::with('departement')
            ->when($search, function ($query) use ($search) {
                $query->where('requester_name', 'like', "%{$search}%");
            })
            ->orderBy('requester_name', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.requesters.index', compact('requesters'));
    }

    public function create()
    {
        $departments = Departement::where('is_active', 1)
                        ->orderBy('departement_name', 'asc')
                        ->get();

        return view('admin.requesters.create', compact('departments'));
    }

    public function store(Request $request)
    {
        \Log::info('Request data:', $request->all());
        
        try {
            $validated = $request->validate([
                'requester_name' => 'required|string|max:255|unique:requesters,requester_name',
                'departement_id' => 'required|exists:departement,id',
                'contact_email'  => 'nullable|email|max:255|unique:requesters,contact_email',
                'contact_phone'  => 'nullable|string|max:20',
                'status'         => 'required|in:active,inactive',
            ]);

            \Log::info('Validated data:', $validated);

            $requester = Requester::create($validated);

            \Log::info('Requester created:', $requester->toArray());

            return redirect()->route('admin.requesters.index')
                ->with('success', '✓ Requester berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error adding requester: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '✗ Gagal menambah requester: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Requester $requester)
    {
        $departments = Departement::where('is_active', 1)
            ->orderBy('departement_name', 'asc')
            ->get();
        
        return view('admin.requesters.edit', compact('requester', 'departments'));
    }

    public function update(Request $request, Requester $requester)
    {
        $validated = $request->validate([
            'requester_name' => 'required|string|max:255|unique:requesters,requester_name,' . $requester->id,
            'departement_id' => 'required|exists:departement,id',
            'contact_email'  => 'nullable|email|max:255|unique:requesters,contact_email,' . $requester->id,
            'contact_phone'  => 'nullable|string|max:20',
            'status'         => 'required|in:active,inactive',
        ]);

        $requester->update($validated);

        return redirect()->route('admin.requesters.index')
            ->with('success', '✓ Requester berhasil diperbarui!');
    }

    public function destroy(Requester $requester)
    {
        $requester->delete();
        return redirect()->back()->with('success', '✓ Requester berhasil dihapus!');
    }
}