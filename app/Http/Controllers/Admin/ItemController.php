<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search','');
        $perPage = $request->per_page ?? 10;

        $items = Item::with('category')
            ->when($search, function ($query) use ($search) {
            $query->where('item_name', 'like', "%{$search}%");
            })
            ->orderBy('item_name')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.items.index', compact('items', 'search', 'perPage'));
    }

    public function create()
    {
        $categories = Category::all();
        $units = \App\Models\Unit::all();
        return view('admin.items.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name'   => 'required|string|max:255|unique:items,item_name',
            'category_id' => 'required|exists:categories,id',
            'unit_id'     => 'required|exists:units,id',
            // Migration defines condition as: ['good','damaged']
            'condition'   => 'required|in:good,damaged',
            'min_stock'   => 'required|integer|min:0',
            'description' => 'nullable|string',
        ], [
            'item_name.unique' => 'Item name already exists. Please choose a different name.',
            'item_name.required' => 'Item name is required.',
            'item_name.max' => 'Item name cannot exceed 255 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',
            'unit_id.required' => 'Please select a unit.',
            'unit_id.exists' => 'Selected unit does not exist.',
            'condition.required' => 'Please select item condition.',
            'condition.in' => 'Invalid condition selected.',
            'min_stock.required' => 'Minimum stock is required.',
            'min_stock.integer' => 'Minimum stock must be a number.',
            'min_stock.min' => 'Minimum stock cannot be negative.',
        ]);

        // Get category untuk prefix code
        $category = Category::findOrFail($validated['category_id']);
        $categoryCode = $category->code;

        // Compute the maximum ITM number globally across all items
        $maxNumber = Item::where('item_code', 'like', '%-ITM-%')
            ->selectRaw('MAX(CAST(substr(item_code, INSTR(item_code, \'-ITM-\') + 5) AS INTEGER)) as max_num')
            ->value('max_num') ?? 0;

        $number = intval($maxNumber) + 1;
        $code = $categoryCode . '-ITM-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        Item::create([
            'item_code'       => $code,
            'item_name'       => $validated['item_name'],
            'category_id'     => $validated['category_id'],
            'unit_id'         => $validated['unit_id'],
            'condition'       => $validated['condition'],
            'min_stock'       => $validated['min_stock'],
            'stock'           => 0,
            'description'     => $validated['description'],
        ]);

        return redirect()->route('admin.items.index')
            ->with('success', 'Item berhasil ditambahkan');
    }

    public function edit(Item $item) {
        $categories = Category::all();
        $units = \App\Models\Unit::all();
        return view('admin.items.edit', compact('item', 'categories', 'units'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'item_name'   => 'required|string|max:255|unique:items,item_name,' . $item->id,
            'category_id' => 'required|exists:categories,id',
            'unit_id'     => 'required|exists:units,id',
            'condition'   => 'required|in:good,damaged',
            'min_stock'   => 'required|integer|min:0',
            'description' => 'nullable|string',
        ], [
            'item_name.unique' => 'Item name already exists. Please choose a different name.',
            'item_name.required' => 'Item name is required.',
            'item_name.max' => 'Item name cannot exceed 255 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',
            'unit_id.required' => 'Please select a unit.',
            'unit_id.exists' => 'Selected unit does not exist.',
            'condition.required' => 'Please select item condition.',
            'condition.in' => 'Invalid condition selected.',
            'min_stock.required' => 'Minimum stock is required.',
            'min_stock.integer' => 'Minimum stock must be a number.',
            'min_stock.min' => 'Minimum stock cannot be negative.',
        ]);

        // 🔒 Code dan Stock TIDAK BISA diubah
        $item->update([
            'item_name'       => $validated['item_name'],
            'category_id'     => $validated['category_id'],
            'unit_id'         => $validated['unit_id'],
            'condition'       => $validated['condition'],
            'min_stock'       => $validated['min_stock'],
            'description'     => $validated['description'],
        ]);

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Item updated successfully');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return back()->with('success', 'Item deleted');
    }
}

