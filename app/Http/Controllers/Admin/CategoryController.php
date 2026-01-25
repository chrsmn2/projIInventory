<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->per_page ?? 10;

        $categories = Category::when($search, function ($query) use ($search) {
        $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        // AUTO GENERATE CODE
        $prefix = strtoupper(substr($request->name, 0, 3));

        $last = Category::where('code', 'like', $prefix.'%')
            ->orderBy('code', 'desc')
            ->first();

        $number = $last ? intval(substr($last->code, 3)) + 1 : 1;

        $code = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

        Category::create([
            'code' => $code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category added successfully');
    }


    public function update(Request $request, Category $category)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'description'     => 'nullable|string',
        ]);

        // CODE TIDAK DIUPDATE
        $category->update([
            'name' => $request->name,
            'description'     => $request->description,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
