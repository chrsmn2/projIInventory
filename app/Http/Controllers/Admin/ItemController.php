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
        return view('admin.items.create', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'item_name'   => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'condition'   => 'required|in:normal,damaged',
        'description' => 'nullable|string',
    ]);

    // Generate item code
    $lastItem = Item::latest('id')->first();
    $nextNumber = $lastItem ? $lastItem->id + 1 : 1;
    $itemCode = 'ITM-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    Item::create([
        'item_code'   => $itemCode,
        'item_name'   => $request->item_name, // ⬅️ INI KUNCI
        'category_id' => $request->category_id,
        'condition'   => $request->condition,
        'stock'       => 0, // default
        'description' => $request->description,
    ]);

    return redirect()
        ->route('admin.items.index')
        ->with('success', 'Item berhasil ditambahkan');
}



    public function edit(Item $item) {
    return view('admin.items.edit', [
        'item' => $item,
        'categories' => Category::all()
        ]);
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:normal,damaged',
            'description' => 'nullable|string',
        ]);

        $item->update([
        'item_name'   => $request->item_name,
        'category_id' => $request->category_id,
        'condition'   => $request->condition,
        'description' => $request->description,
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

