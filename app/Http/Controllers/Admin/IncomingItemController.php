<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncomingItem;
use App\Models\IncomingItemDetail;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncomingItemController extends Controller
{
    /**
     * Display incoming items listing with search & sorting
     */
    public function index(Request $request)
    {
        $search  = $request->string('search')->trim();
        $perPage = $request->integer('per_page', 5);
        $sortBy  = $request->get('sort_by', 'incoming_date');
        $sortDir = $request->get('sort_dir', 'desc');

        // Allowed sort columns
        $allowedSorts = [
            'code',
            'incoming_date',
            'created_at',
            'supplier_name',
        ];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'incoming_date';
        }

        // Base query with eager loads
        $query = IncomingItem::with([
                'admin',
                'supplier',
                'details.item',
                'details.unit'
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->whereHas('supplier', function ($q) use ($search) {
                        $q->where('supplier_name', 'like', "%{$search}%");
                    })
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('incoming_date', 'like', "%{$search}%");
                });
            });

        // Handle sorting by related supplier name
        if ($sortBy === 'supplier_name') {
            $query = IncomingItem::select('incoming_items.*')
                ->with(['admin', 'supplier', 'details.item', 'details.unit'])
                ->leftJoin('suppliers', 'incoming_items.supplier_id', '=', 'suppliers.id')
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($sub) use ($search) {
                        $sub->where('suppliers.supplier_name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%")
                            ->orWhere('incoming_date', 'like', "%{$search}%");
                    });
                })
                ->orderBy('suppliers.supplier_name', $sortDir);
        } else {
            $query = $query->orderBy($sortBy, $sortDir);
        }

        $incoming = $query->paginate($perPage)->withQueryString();

        return view('admin.incoming.index', compact(
            'incoming',
            'search',
            'perPage',
            'sortBy',
            'sortDir'
        ));
    }



    /**
     * Show create form
     */
    public function create()
    {
        $items     = Item::with('unit')->whereHas('unit')->orderBy('item_name')->get();
        $units     = Unit::orderBy('unit_name')->get();
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('admin.incoming.create', compact('items', 'units', 'suppliers'));
    }

    /**
     * Generate unique incoming code
     */
    private function generateIncomingCode(): string
    {
        $prefix = 'IN-';

        $latest = IncomingItem::where('code', 'like', $prefix.'%')
            ->orderByDesc('code')
            ->value('code');

        $number = $latest
            ? ((int) substr($latest, strlen($prefix))) + 1
            : 1;

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Store new incoming item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'incoming_date'    => 'required|date',
            'supplier_id'      => 'required|exists:suppliers,id',
            'notes'            => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.unit_id'  => 'required|exists:units,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Generate unique code
                $code = $this->generateIncomingCode();

                $incoming = IncomingItem::create([
                    'admin_id'      => Auth::id(),
                    'incoming_date' => $validated['incoming_date'],
                    'supplier_id'   => $validated['supplier_id'],
                    'notes'         => $validated['notes'] ?? null,
                    'code'          => $code,
                ]);

                if ($incoming === null) {
                    throw new \Exception('Gagal menyimpan data incoming item.');
                }

                foreach ($validated['items'] as $item) {
                    if (empty($item['unit_id'])) {
                        throw new \Exception('Unit belum dipilih untuk salah satu item.');
                    }

                    IncomingItemDetail::create([
                        'incoming_item_id' => $incoming->id,
                        'item_id'          => $item['item_id'],
                        'unit_id'          => $item['unit_id'],
                        'quantity'         => $item['quantity'],
                    ]);

                    Item::where('id', $item['item_id'])
                        ->increment('stock', $item['quantity']);
                }
            });

            return redirect()->route('admin.incoming.index')
                ->with('success', '✓ Incoming items berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error('Error adding incoming item: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', '✗ Gagal menambahkan incoming: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show incoming item details
     */
    public function show(IncomingItem $incoming)
    {
        $incoming->load([
            'admin',
            'supplier',
            'details.item.category',
            'details.unit'
        ]);

        return view('admin.incoming.show', compact('incoming'));
    }

    /**
     * Show edit form
     */
    public function edit(IncomingItem $incoming)
    {
        $incoming->load(['details.item', 'details.unit']);
        $items     = Item::with('unit')->whereHas('unit')->orderBy('item_name')->get();
        $units     = Unit::orderBy('unit_name')->get();
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('admin.incoming.edit', compact('incoming', 'items', 'units', 'suppliers'));
    }

    /**
     * Update incoming item
     */
    public function update(Request $request, IncomingItem $incoming)
    {
        $validated = $request->validate([
            'incoming_date'        => 'required|date',
            'supplier_id'          => 'required|exists:suppliers,id',
            'notes'                => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.item_id'      => 'required|exists:items,id',
            'items.*.unit_id'      => 'required|exists:units,id',
            'items.*.quantity'     => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated, $incoming) {
                // Revert stock lama
                foreach ($incoming->details as $detail) {
                    Item::where('id', $detail->item_id)
                        ->decrement('stock', $detail->quantity);
                }

                // Update incoming item
                $incoming->update([
                    'incoming_date' => $validated['incoming_date'],
                    'supplier_id'   => $validated['supplier_id'],
                    'notes'         => $validated['notes'] ?? null,
                ]);

                // Hapus detail lama
                $incoming->details()->delete();

                // Tambah detail baru
                foreach ($validated['items'] as $item) {
                    IncomingItemDetail::create([
                        'incoming_item_id' => $incoming->id,
                        'item_id'          => $item['item_id'],
                        'unit_id'          => $item['unit_id'],
                        'quantity'         => $item['quantity'],
                    ]);

                    Item::where('id', $item['item_id'])
                        ->increment('stock', $item['quantity']);
                }
            });

            return redirect()->route('admin.incoming.index')
                ->with('success', '✓ Incoming item berhasil diupdate');

        } catch (\Exception $e) {
            Log::error('Error updating incoming item: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', '✗ Gagal mengupdate incoming: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete incoming item
     */
    public function destroy(IncomingItem $incoming)
    {
        try {
            DB::transaction(function () use ($incoming) {
                foreach ($incoming->details as $detail) {
                    Item::where('id', $detail->item_id)
                        ->decrement('stock', $detail->quantity);
                }

                $incoming->details()->delete();
                $incoming->delete();
            });

            return redirect()->route('admin.incoming.index')
                ->with('success', '✓ Incoming items berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting incoming item: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', '✗ Gagal menghapus incoming: ' . $e->getMessage());
        }
    }
}
