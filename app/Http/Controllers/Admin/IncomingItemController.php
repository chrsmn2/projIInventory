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
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        $incoming = IncomingItem::with([
                'admin',
                'supplier',
                'details.item',
                'details.unit'
            ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('supplier', function ($q) use ($search) {
                    $q->where('supplier_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('incoming_date', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.incoming.index', compact('incoming', 'search', 'perPage'));
    }

    

    public function create()
    {
        $items = Item::with('unit')->whereHas('unit')->orderBy('item_name')->get();
        $units = Unit::orderBy('name')->get(); 
        $suppliers = Supplier::all();

        return view('admin.incoming.create', compact('items', 'units', 'suppliers'));
    }

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
                    'code'          => $code, // Menyimpan kode yang dihasilkan
                ]);

                if ($incoming === null) {
                    throw new \Exception('Gagal menyimpan data incoming item.');
                }  

                foreach ($validated['items'] as $item) {
                    if (empty($item['unit_id'])) {
                        throw new \Exception('Unit belum dipilih untuk salah satu item.');
                    }

                    $store_item_detail = IncomingItemDetail::create([
                        'incoming_item_id' => $incoming->id,
                        'item_id'          => $item['item_id'],
                        'unit_id'          => $item['unit_id'],
                        'quantity'         => $item['quantity'],
                    ]);

                    if ($store_item_detail === null) {
                        throw new \Exception('Gagal menyimpan detail item masuk.');
                    }

                    // Gunakan 'stock' bukan 'quantity'
                    Item::where('id', $item['item_id'])
                        ->increment('stock', $item['quantity']);
                }
            });

            return redirect()->route('admin.incoming.index')
                ->with('success', '✓ Incoming items berhasil ditambahkan');

        } catch (\Exception $e) {
            // Tambahkan logging untuk debugging
            Log::error('Error adding incoming item: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', '✗ Gagal menambahkan incoming: '.$e->getMessage())
                ->withInput();
        }
    }

    private function generateIncomingCode()
    {
        // Menghasilkan kode unik, misalnya: IN-001, IN-002, dll.
        $latest = IncomingItem::orderBy('created_at', 'desc')->first();
        $number = $latest ? intval(substr($latest->code, -3)) + 1 : 1; // Ambil nomor terakhir dan tambahkan 1
        return 'IN-' . str_pad($number, 3, '0', STR_PAD_LEFT); // Format kode
    }


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

    public function edit(IncomingItem $incoming)
    {
        $incoming->load(['details.item', 'details.unit']);
        $items = Item::with('unit')->whereHas('unit')->orderBy('item_name')->get();
        $units = Unit::orderBy('name')->get(); // ✅
        $suppliers = Supplier::all();

        return view('admin.incoming.edit', compact('incoming', 'items', 'units', 'suppliers'));
    }

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
        ], [
            'incoming_date.required' => 'Tanggal incoming wajib diisi.',
            'incoming_date.date' => 'Tanggal incoming harus berupa tanggal yang valid.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'notes.string' => 'Catatan harus berupa teks.',
            'items.required' => 'Minimal satu item harus ditambahkan.',
            'items.array' => 'Data item harus berupa array.',
            'items.min' => 'Minimal satu item harus ditambahkan.',
            'items.*.item_id.required' => 'Item wajib dipilih untuk setiap baris.',
            'items.*.item_id.exists' => 'Item yang dipilih tidak valid.',
            'items.*.unit_id.required' => 'Unit wajib diisi untuk setiap item.',
            'items.*.unit_id.exists' => 'Unit yang dipilih tidak valid.',
            'items.*.quantity.required' => 'Quantity wajib diisi untuk setiap item.',
            'items.*.quantity.integer' => 'Quantity harus berupa angka bulat.',
            'items.*.quantity.min' => 'Quantity minimal 1.',
        ]);

        try {
            DB::transaction(function () use ($validated, $incoming) {
                // Rollback stock lama
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

                    // Tambah stock baru
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

    public function destroy(IncomingItem $incoming)
    {
        try {
            DB::transaction(function () use ($incoming) {
                foreach ($incoming->details as $detail) {
                    // Kurangi stock saat menghapus
                    Item::where('id', $detail->item_id)
                        ->decrement('stock', $detail->quantity);
                }

                $incoming->details()->delete();
                $incoming->delete();
            });

            return redirect()->route('admin.incoming.index')
                ->with('success', '✓ Incoming items berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '✗ Gagal menghapus incoming: ' . $e->getMessage());
        }
    }
}
