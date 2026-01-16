<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncomingItem;
use App\Models\IncomingItemDetail;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomingItemController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $incoming = IncomingItem::with('admin', 'details.item')
            ->when($search, function ($q) use ($search) {
                $q->where('supplier', 'like', "%{$search}%")
                  ->orWhereHas('details.item', function ($q2) use ($search) {
                      $q2->where('item_name', 'like', "%{$search}%");
                  });
            })
            ->latest()
            ->paginate($perPage);

        return view('admin.incoming.index', compact('incoming', 'search', 'perPage'));
    }

    public function create()
    {
        $items = Item::all();
        $suppliers = Supplier::all(); // TAMBAHKAN INI
        return view('admin.incoming.create', compact('items', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'incoming_date' => 'required|date',
            'suppliers'     => 'required|exists:suppliers,id',
            'item_id.*'     => 'required|exists:items,id',
            'quantity.*'    => 'required|integer|min:1',
        ]);

        // AMBIL NAMA SUPPLIER BERDASARKAN ID
        $supplier = Supplier::findOrFail($request->suppliers);

        $incoming = IncomingItem::create([
            'admin_id'      => Auth::id(),
            'incoming_date' => $request->incoming_date,
            'supplier'      => $supplier->supplier_name,
            'source'        => $supplier->supplier_name, // TAMBAHKAN INI
            'notes'         => $request->notes,
        ]);

        foreach ($request->item_id as $i => $itemId) {
            $qty = $request->quantity[$i];

            IncomingItemDetail::create([
                'incoming_item_id' => $incoming->id,
                'item_id'          => $itemId,
                'quantity'         => $qty,
                'note'             => $request->note[$i] ?? null,
            ]);

            Item::where('id', $itemId)->increment('stock', $qty);
        }

        return redirect()->route('admin.incoming.index')
            ->with('success', 'Incoming items berhasil ditambahkan');
    }

    public function edit(IncomingItem $incoming)
    {
        $incoming->load('details.item');
        $items = Item::orderBy('item_name')->get();
        $suppliers = Supplier::all(); // TAMBAHKAN INI
        $usedItemIds = $incoming->details->pluck('item_id')->toArray();

        return view('admin.incoming.edit', compact('incoming', 'items', 'suppliers', 'usedItemIds'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'incoming_date' => 'required|date',
            'suppliers'     => 'required|exists:suppliers,id',
            'item_id'       => 'required|array|min:1',
            'item_id.*'     => 'required|exists:items,id',
            'quantity'      => 'required|array|min:1',
            'quantity.*'    => 'required|integer|min:1',
        ]);

        if (count($request->item_id) !== count(array_unique($request->item_id))) {
            return back()->withErrors('Item tidak boleh duplikat');
        }

        DB::transaction(function () use ($request, $id) {
            $incoming = IncomingItem::with('details')->findOrFail($id);

            foreach ($incoming->details as $detail) {
                Item::where('id', $detail->item_id)
                    ->decrement('stock', $detail->quantity);
            }

            $supplier = Supplier::findOrFail($request->suppliers);

            $incoming->update([
                'incoming_date' => $request->incoming_date,
                'suppliers'      => $supplier->supplier_id,
                'notes'         => $request->notes ?? null,
            ]);

            $incoming->details()->delete();

            foreach ($request->item_id as $index => $itemId) {
                $qty  = $request->quantity[$index];
                $note = $request->note[$index] ?? null;

                IncomingItemDetail::create([
                    'incoming_item_id' => $incoming->id,
                    'item_id'          => $itemId,
                    'quantity'         => $qty,
                    'note'             => $note,
                ]);

                Item::where('id', $itemId)->increment('stock', $qty);
            }
        });

        return redirect()
            ->route('admin.incoming.index')
            ->with('success', 'Incoming berhasil diperbarui');
    }

    public function destroy(IncomingItem $incoming)
    {
        DB::transaction(function () use ($incoming) {
            foreach ($incoming->details as $detail) {
                Item::where('id', $detail->item_id)
                    ->decrement('stock', $detail->quantity);
            }

            $incoming->details()->delete();
            $incoming->delete();
        });

        return redirect()->route('admin.incoming.index')
            ->with('success', 'Incoming items berhasil dihapus');
    }
}
