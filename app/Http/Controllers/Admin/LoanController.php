<?php

namespace App\Http\Controllers\Admin;

use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\Item;
use App\Models\Requester;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $loans = Loan::with(['admin']) 
            ->when($search, function ($query) use ($search) {
                $query->where('loan_code', 'like', "%{$search}%")
                      ->orWhere('requester_name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.loans.index', compact('loans', 'search'));
    }

    public function create()
    {
        $items = Item::where('stock', '>', 0)->get();
        $requesters = Requester::where('status', 'active')->get(); // TAMBAHKAN INI
        return view('admin.loans.create', compact('items', 'requesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_date'       => 'required|date',
            'requester_name'   => 'required|string|max:100',
            'department'      => 'required|string|max:100',
            'purpose'         => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity'=> 'required|integer|min:1',
        ]);

        // Check stock availability for all items
        foreach ($request->items as $item) {
            if (!isset($item['item_id']) || !isset($item['quantity'])) {
                continue;
            }

            $itemModel = Item::find($item['item_id']);
            if ($itemModel->stock < $item['quantity']) {
                return back()->withInput()->with('error', 
                    "Stok tidak cukup untuk barang: {$itemModel->item_name}. Tersedia: {$itemModel->stock}, Diminta: {$item['quantity']}"
                );
            }
        }

        try {
            DB::transaction(function () use ($request) {
                // 1. Simpan Data Header Pinjaman
                $loan = Loan::create([
                    'loan_code'     => 'LOAN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                    'loan_date'     => $request->loan_date,
                    'return_date'   => null,
                    'requester_name' => $request->requester_name,
                    'department'    => $request->department,
                    'purpose'       => $request->purpose,
                    'status'        => 'pending',
                    'admin_id'      => Auth::id(),
                ]);

                // 2. Simpan Detail Pinjaman
                foreach ($request->items as $item) {
                    if (isset($item['item_id']) && $item['quantity'] > 0) {
                        LoanDetail::create([
                            'loan_id'           => $loan->id,
                            'item_id'           => $item['item_id'],
                            'quantity'          => $item['quantity'],
                            'returned_quantity' => 0,
                            'condition_out'     => $item['condition_out'] ?? 'good',
                            'status'            => 'borrowed',
                        ]);
                    }
                }
            });

            return redirect()
                ->route('admin.loans.index')
                ->with('success', 'Pengajuan pinjaman berhasil dibuat');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $loan = Loan::with(['admin', 'details.item'])->findOrFail($id);
        return view('admin.loans.show', compact('loan'));
    }

    public function edit($id)
    {
        $loan = Loan::with('details.item')->findOrFail($id);
        $items = Item::all();
        $requesters = Requester::where('status', 'active')->get(); // TAMBAHKAN INI
        return view('admin.loans.edit', compact('loan', 'items', 'requesters'));
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        // Validasi
        $request->validate([
            'loan_date'      => 'required|date',
            'requester_name' => 'required|string|max:100',
            'department'     => 'required|string|max:100',
            'purpose'        => 'nullable|string',
            'items'          => 'nullable|array',
            'items.*.item_id' => 'nullable|exists:items,id',
            'items.*.quantity' => 'nullable|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $loan) {
                // 1. Update data loan header
                $loan->update([
                    'loan_date'      => $request->loan_date,
                    'requester_name' => $request->requester_name,
                    'department'     => $request->department,
                    'purpose'        => $request->purpose,
                ]);

                // 2. Simpan items baru jika ada
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $item) {
                        // Skip jika item_id atau quantity kosong
                        if (empty($item['item_id']) || empty($item['quantity'])) {
                            continue;
                        }

                        // Check stock availability
                        $itemModel = Item::find($item['item_id']);
                        if ($itemModel->stock < $item['quantity']) {
                            throw new \Exception(
                                "Stok tidak cukup untuk barang: {$itemModel->item_name}. " .
                                "Tersedia: {$itemModel->stock}, Diminta: {$item['quantity']}"
                            );
                        }

                        // Cek apakah item sudah ada di loan ini
                        $existingDetail = $loan->details()
                            ->where('item_id', $item['item_id'])
                            ->first();

                        if (!$existingDetail) {
                            // Tambah item baru ke loan
                            LoanDetail::create([
                                'loan_id'           => $loan->id,
                                'item_id'           => $item['item_id'],
                                'quantity'          => $item['quantity'],
                                'returned_quantity' => 0,
                                'condition_out'     => $item['condition_out'] ?? 'good',
                                'status'            => 'borrowed',
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('admin.loans.index')
                           ->with('success', 'Data pinjaman berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Hanya pinjaman dengan status pending yang dapat disetujui');
        }

        DB::transaction(function () use ($loan) {
            $loan->update(['status' => 'approved']);

            foreach ($loan->details as $detail) {
                $detail->item->decrement('stock', $detail->quantity);
            }
        });

        return redirect()->route('admin.loans.index')
                       ->with('success', 'Pinjaman berhasil disetujui dan stok telah dikurangi');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Hanya pinjaman dengan status pending yang dapat ditolak');
        }

        $loan->update(['status' => 'rejected']);

        return redirect()->route('admin.loans.index')
                       ->with('success', 'Pinjaman telah ditolak');
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status === 'approved') {
            return back()->with('error', 'Tidak dapat menghapus pinjaman yang sudah disetujui');
        }

        DB::transaction(function () use ($loan) {
            foreach ($loan->details as $detail) {
                $detail->delete();
            }
            $loan->delete();
        });

        return redirect()->route('admin.loans.index')
                       ->with('success', 'Pinjaman berhasil dihapus');
    }
}