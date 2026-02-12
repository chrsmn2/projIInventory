<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->string('search')->trim();
        $perPage = $request->integer('per_page', 5);
        $sortBy  = $request->get('sort_by', 'category_name');
        $sortDir = $request->get('sort_dir', 'asc');

        $allowedSorts = ['category_code', 'category_name', 'created_at'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'category_name';
        }

        $categories = Category::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('category_name', 'like', "%{$search}%")
                        ->orWhere('category_code', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.categories.index', compact(
            'categories',
            'search',
            'perPage',
            'sortBy',
            'sortDir'
        ));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:100|unique:categories,category_name',
            'category_description' => 'nullable|string',
        ], [
            'category_name.unique' => 'Category name already exists.',
            'category_name.required' => 'Category name is required.',
            'category_name.max' => 'Category name cannot exceed 100 characters.',
        ]);

        // AUTO GENERATE CODE: CAT-001
        $prefix = 'CAT-';

        $last = Category::where('category_code', 'like', $prefix . '%')
            ->orderBy('category_code', 'desc')
            ->first();

        $number = $last ? intval(substr($last->category_code, 4)) + 1 : 1;
        $code = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

        Category::create([
            'category_code' => $code,
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category added successfully');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:100|unique:categories,category_name,' . $category->id,
            'category_description' => 'nullable|string',
        ], [
            'category_name.unique' => 'Category name already exists.',
            'category_name.required' => 'Category name is required.',
        ]);

        // CODE TIDAK DIUBAH
        $category->update([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }

    public function checkCategoryName(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'id'   => 'nullable|integer',
        ]);

        $name = $request->input('name');
        $id   = $request->input('id');

        $exists = Category::where('category_name', $name)
            ->when($id, fn($q) => $q->where('id', '!=', $id))
            ->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists
                ? 'Category name already exists. Please choose a different name'
                : 'Category name available',
        ]);
    }
}
