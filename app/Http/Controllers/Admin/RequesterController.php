<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequesterController extends Controller
{
    public function index(Request $request)
    {
       $perPage = $request->per_page ?? 10;

        $requesters = Requester::orderBy('requester_name', 'asc')
            ->paginate($perPage);

        return view('admin.requesters.index', compact('requesters'));
    }

    public function create()
    {
        return view('admin.requesters.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'requester_name' => 'required|string|max:255|unique:requesters,requester_name',
    'department' => 'nullable|string|max:255',
    'contact_email' => 'nullable|email|max:255|unique:requesters,contact_email',
    'contact_phone' => 'nullable|string|max:20',
    'status' => 'required|in:active,inactive',
    ]);

    try {
        $requester = Requester::create($validated);

        return redirect()->route('admin.requesters.index')
            ->with('success', '✓ Requester added successfully!');
    } catch (\Exception $e) {
        Log::error($e);

        return redirect()->back()
            ->with('error', '✗ Failed to add requester')
            ->withInput();
    }
}

    public function edit(Requester $requester)
    {
        return view('admin.requesters.edit', compact('requester'));
    }

    public function update(Request $request, Requester $requester)
    {
        $validated = $request->validate([
            'requester_name' => 'required|string|max:255|unique:requesters,requester_name,' . $requester->id,
            'department' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:requesters,email,' . $requester->id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ], [
            'requester_name.required' => 'Requester name is required.',
            'requester_name.unique' => 'This requester name already exists.',
            'email.email' => 'Email format is invalid.',
            'email.unique' => 'This email is already registered.',
            'status.required' => 'Status is required.',
        ]);

        try {
            Log::info('Updating requester:', ['id' => $requester->id, 'data' => $validated]);
            
            $requester->update($validated);
            
            Log::info('Requester updated successfully');

            return redirect()->route('admin.requesters.index')
                             ->with('success', '✓ Requester updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating requester:', ['message' => $e->getMessage()]);
            
            return redirect()->back()
                             ->with('error', '✗ Failed to update requester: ' . $e->getMessage())
                             ->withInput();
        }
    }

    public function destroy(Requester $requester)
    {
        try {
            Log::info('Deleting requester:', ['id' => $requester->id]);
            
            $requester->delete();

            return redirect()->route('admin.requesters.index')
                             ->with('success', '✓ Requester deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting requester:', ['message' => $e->getMessage()]);
            
            return redirect()->back()
                             ->with('error', '✗ Failed to delete requester: ' . $e->getMessage());
        }
    }
}
