<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OutgoingItem;
use App\Models\OutgoingItemDetail;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutgoingItemController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $outgoing = OutgoingItem::with('admin', 'supervisor', 'details.item')
            ->when($search, function ($q) use ($search) {
                $q->where('destination', 'like', "%{$search}%")
                  ->orWhereHas('details.item', function ($q2) use ($search) {
                      $q2->where('item_name', 'like', "%{$search}%");
                  });
            })
            ->latest()
            ->paginate($perPage);

        return view('admin.outgoing.index', compact(
            'outgoing', 'search', 'perPage'
        ));
    }

    public function create()
    {
        $items = Item::all();
        return view('admin.outgoing.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'outgoing_date' => 'required|date',
            'destination'   => 'required|string',
            'item_id.*'     => 'required|exists:items,id',
            'quantity.*'    => 'required|integer|min:1',
            'notes'         => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $outgoing = OutgoingItem::create([
                'admin_id'      => Auth::id(),
                'outgoing_date' => $request->outgoing_date,
                'destination'   => $request->destination,
                'status'        => 'pending',
                'notes'         => $request->notes,
            ]);

            foreach ($request->item_id as $i => $itemId) {
                $qty = $request->quantity[$i];
                $item = Item::find($itemId);

                if ($item->stock < $qty) {
                    DB::rollBack();
                    return back()->with('error', "Insufficient stock for {$item->item_name}");
                }

                OutgoingItemDetail::create([
                    'outgoing_item_id' => $outgoing->id,
                    'item_id'          => $itemId,
                    'quantity'         => $qty,
                    'condition'        => $request->input("condition.{$i}", 'normal'),
                ]);

                // Update stock
                $item->decrement('stock', $qty);
            }

            DB::commit();
            return redirect()->route('admin.outgoing.index')
                           ->with('success', 'Outgoing item created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create outgoing item: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $outgoing = OutgoingItem::with('admin', 'supervisor', 'details.item')->findOrFail($id);
        return view('admin.outgoing.show', compact('outgoing'));
    }

    public function edit($id)
    {
        $outgoing = OutgoingItem::with('details.item')->findOrFail($id);
        $items = Item::all();
        return view('admin.outgoing.edit', compact('outgoing', 'items'));
    }

    public function update(Request $request, $id)
    {
        $outgoing = OutgoingItem::findOrFail($id);

        $request->validate([
            'outgoing_date' => 'required|date',
            'destination'   => 'required|string',
            'status'        => 'required|in:pending,completed,cancelled',
            'notes'         => 'nullable|string',
        ]);

        $outgoing->update([
            'outgoing_date' => $request->outgoing_date,
            'destination'   => $request->destination,
            'status'        => $request->status,
            'notes'         => $request->notes,
        ]);

        return redirect()->route('admin.outgoing.show', $outgoing->id)
                       ->with('success', 'Outgoing item updated successfully');
    }

    public function destroy($id)
    {
        $outgoing = OutgoingItem::findOrFail($id);

        DB::beginTransaction();

        try {
            foreach ($outgoing->details as $detail) {
                $detail->item->increment('stock', $detail->quantity);
                $detail->delete();
            }

            $outgoing->delete();

            DB::commit();
            return redirect()->route('admin.outgoing.index')
                           ->with('success', 'Outgoing item deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete outgoing item: ' . $e->getMessage());
        }
    }
}
