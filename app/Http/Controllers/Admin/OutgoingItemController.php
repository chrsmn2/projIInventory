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
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        $outgoing = OutgoingItem::with(['admin', 'supervisor', 'departement', 'details.item'])
            ->when($search, function ($query) use ($search) {
                $query->where('code', 'like', "%{$search}%")
                      ->orWhereHas('departement', function ($q) use ($search) {
                          $q->where('departement_name', 'like', "%{$search}%");
                      });
            })
            ->orderBy('outgoing_date', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.outgoing.index', compact('outgoing', 'search', 'perPage'));
    }

    public function create()
    {
        // REVISI: Menggunakan kolom 'stock' sesuai database Anda
        $items = Item::with('unit')->where('stock', '>', 0)->get();
        $departements = Departement::where('is_active', 1)->get();
        
        return view('admin.outgoing.create', compact('items', 'departements'));
    }

    private function generateOutgoingCode()
    {
         // Menghasilkan kode unik, misalnya: OUT-001, OUT-002, dll.
        $latest = OutgoingItem::orderBy('created_at', 'desc')->first();
        $number = $latest ? intval(substr($latest->code, -3)) + 1 : 1; // Ambil nomor terakhir dan tambahkan 1
        return 'OUT-' . str_pad($number, 3, '0', STR_PAD_LEFT); // Format kode
    }

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

        // Aktifkan dd($validated) di bawah ini jika ingin cek data yang masuk sebelum diproses
        // dd($validated);

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

    public function show($id)
    {
        $outgoing = OutgoingItem::with(['admin', 'supervisor', 'departement', 'details.item.unit'])->findOrFail($id);
        return view('admin.outgoing.show', compact('outgoing'));
    }

    public function edit($id)
    {
        $outgoing = OutgoingItem::with(['details.item.unit'])->findOrFail($id);
        $items = Item::with('unit')->get();
        $departements = Departement::where('is_active', 1)->get();

        return view('admin.outgoing.edit', compact('outgoing', 'items', 'departements'));
    }

    public function update(Request $request, $id)
    {
        $outgoing = OutgoingItem::findOrFail($id);

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
                // 1. Revert (Kembalikan stok lama ke kolom 'stock')
                foreach ($outgoing->details as $detail) {
                    Item::where('id', $detail->item_id)->increment('stock', $detail->quantity);
                }

                // 2. Update Header
                $outgoing->update([
                    'outgoing_date'  => $validated['outgoing_date'],
                    'departement_id' => $validated['departement_id'],
                    'notes'          => $validated['notes'] ?? null,
                ]);

                // 3. Re-create Details
                $outgoing->details()->delete();

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
            return redirect()->back()
                ->with('error', '✗ Gagal update: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $outgoing = OutgoingItem::findOrFail($id);

        try {
            DB::transaction(function () use ($outgoing) {
                // Kembalikan stok ke kolom 'stock' sebelum dihapus
                foreach ($outgoing->details as $detail) {
                    Item::where('id', $detail->item_id)->increment('stock', $detail->quantity);
                }

                $outgoing->details()->delete();
                $outgoing->delete();
            });

            return redirect()->route('admin.outgoing.index')
                ->with('success', '✓ Data berhasil dihapus dan stok dikembalikan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '✗ Gagal menghapus: ' . $e->getMessage());
        }
    }
}