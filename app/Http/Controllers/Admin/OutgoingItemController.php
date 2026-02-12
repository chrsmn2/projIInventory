<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OutgoingItem;
use App\Models\OutgoingItemDetail;
use App\Models\Item;
use App\Models\Departement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OutgoingItemController extends Controller
{
    /**
     * Display outgoing items listing with search & sorting
     */
    public function index(Request $request)
    {
        $search  = $request->string('search')->trim();
        $perPage = $request->integer('per_page', 5);
        $sortBy  = $request->get('sort_by', 'outgoing_date');
        $sortDir = $request->get('sort_dir', 'desc');

        // Allowed sort columns
        $allowedSorts = [
            'code',
            'outgoing_date',
            'created_at',
            'departement_name',
        ];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'outgoing_date';
        }

        // Base query with eager loads
        $query = OutgoingItem::with(['admin', 'supervisor', 'departement', 'details.item'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('code', 'like', "%{$search}%")
                        ->orWhereHas('departement', function ($q) use ($search) {
                            $q->where('departement_name', 'like', "%{$search}%");
                        });
                });
            });

        // Handle sorting by related department name
        if ($sortBy === 'departement_name') {
            $query = OutgoingItem::select('outgoing_items.*')
                ->with(['admin', 'supervisor', 'departement', 'details.item'])
                ->leftJoin('departement', 'outgoing_items.departement_id', '=', 'departement.id')
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($sub) use ($search) {
                        $sub->where('outgoing_items.code', 'like', "%{$search}%")
                            ->orWhere('departement.departement_name', 'like', "%{$search}%");
                    });
                })
                ->orderBy('departement.departement_name', $sortDir);
        } else {
            $query = $query->orderBy($sortBy, $sortDir);
        }

        $outgoing = $query->paginate($perPage)->withQueryString();

        return view('admin.outgoing.index', compact(
            'outgoing',
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
        $items        = Item::with('unit')->where('stock', '>', 0)->orderBy('item_name')->get();
        $departements = Departement::where('status', 'active')->orderBy('departement_name')->get();

        return view('admin.outgoing.create', compact('items', 'departements'));
    }

    /**
     * Generate unique outgoing code
     */
    private function generateOutgoingCode(): string
    {
        $prefix = 'OUT-';

        $latest = OutgoingItem::where('code', 'like', $prefix.'%')
            ->orderByDesc('code')
            ->value('code');

        $number = $latest
            ? ((int) substr($latest, strlen($prefix))) + 1
            : 1;

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Store new outgoing item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'outgoing_date'   => 'required|date',
            'departement_id'  => 'required|exists:departement,id',
            'notes'           => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity'=> 'required|integer|min:1',
            'items.*.condition'=> 'nullable|in:normal,damaged',
        ], [
            'items.required' => 'Wajib menambahkan minimal satu barang.',
            'items.*.item_id.required' => 'Barang harus dipilih.',
            'items.*.quantity.min' => 'Jumlah barang minimal 1.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $code = $this->generateOutgoingCode();

                $outgoing = OutgoingItem::create([
                    'code'           => $code,
                    'admin_id'       => Auth::id(),
                    'supervisor_id'  => Auth::id(),
                    'outgoing_date'  => $validated['outgoing_date'],
                    'departement_id' => $validated['departement_id'],
                    'notes'          => $validated['notes'] ?? null,
                ]);

                foreach ($validated['items'] as $itemData) {
                    $item = Item::lockForUpdate()->findOrFail($itemData['item_id']);

                    if ($item->stock < $itemData['quantity']) {
                        throw new \Exception("Stok {$item->item_name} tidak mencukupi. Sisa stok: {$item->stock}");
                    }

                    $item->decrement('stock', $itemData['quantity']);

                    OutgoingItemDetail::create([
                        'outgoing_item_id' => $outgoing->id,
                        'item_id'          => $itemData['item_id'],
                        'quantity'         => $itemData['quantity'],
                        'unit_id'          => $item->unit_id ?? null,
                        'condition'        => $itemData['condition'] ?? 'normal',
                    ]);
                }
            });

            return redirect()->route('admin.outgoing.index')
                ->with('success', '✓ Outgoing items berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error('Outgoing Store Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->back()
                ->with('error', '✗ Gagal menambahkan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show outgoing item details
     */
    public function show($id)
    {
        $outgoing = OutgoingItem::with(['admin', 'supervisor', 'departement', 'details.item.unit'])
            ->findOrFail($id);

        return view('admin.outgoing.show', compact('outgoing'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $outgoing     = OutgoingItem::with(['details.item.unit'])->findOrFail($id);
        $items        = Item::with('unit')->orderBy('item_name')->get();
        $departements = Departement::where('status', 'active')->orderBy('departement_name')->get();

        return view('admin.outgoing.edit', compact('outgoing', 'items', 'departements'));
    }

    /**
     * Update outgoing item
     */
    public function update(Request $request, OutgoingItem $outgoing)
    {
        $validated = $request->validate([
            'outgoing_date'   => 'required|date',
            'departement_id'  => 'required|exists:departement,id',
            'notes'           => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity'=> 'required|integer|min:1',
            'items.*.condition'=> 'nullable|in:normal,damaged',
        ]);

        try {
            DB::transaction(function () use ($validated, $outgoing) {
                // Revert stock lama
                foreach ($outgoing->details as $detail) {
                    Item::where('id', $detail->item_id)->increment('stock', $detail->quantity);
                }

                // Update Header
                $outgoing->update([
                    'outgoing_date'  => $validated['outgoing_date'],
                    'departement_id' => $validated['departement_id'],
                    'notes'          => $validated['notes'] ?? null,
                ]);

                // Delete old details
                $outgoing->details()->delete();

                // Create new details
                foreach ($validated['items'] as $itemData) {
                    $item = Item::lockForUpdate()->findOrFail($itemData['item_id']);

                    if ($item->stock < $itemData['quantity']) {
                        throw new \Exception("Stok {$item->item_name} tidak mencukupi.");
                    }

                    $item->decrement('stock', $itemData['quantity']);

                    OutgoingItemDetail::create([
                        'outgoing_item_id' => $outgoing->id,
                        'item_id'          => $itemData['item_id'],
                        'quantity'         => $itemData['quantity'],
                        'unit_id'          => $item->unit_id ?? null,
                        'condition'        => $itemData['condition'] ?? 'normal',
                    ]);
                }
            });

            return redirect()->route('admin.outgoing.show', $outgoing->id)
                ->with('success', '✓ Outgoing item berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Error updating outgoing item: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', '✗ Gagal update: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete outgoing item
     */
    public function destroy(OutgoingItem $outgoing)
    {
        try {
            DB::transaction(function () use ($outgoing) {
                // Return stock before deletion
                foreach ($outgoing->details as $detail) {
                    Item::where('id', $detail->item_id)->increment('stock', $detail->quantity);
                }

                $outgoing->details()->delete();
                $outgoing->delete();
            });

            return redirect()->route('admin.outgoing.index')
                ->with('success', '✓ Data berhasil dihapus dan stok dikembalikan');
        } catch (\Exception $e) {
            Log::error('Error deleting outgoing item: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', '✗ Gagal menghapus: ' . $e->getMessage());
        }
    }
}
