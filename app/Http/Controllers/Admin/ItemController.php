<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Unit; 
use App\Imports\ItemsImport;
use App\Models\ImportRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->string('search')->trim();
        $perPage = $request->integer('per_page', 5);
        $sortBy  = $request->get('sort_by', 'item_name');
        $sortDir = $request->get('sort_dir', 'asc');

    $allowedSorts = [
        'item_code',
        'item_name',
        'stock',
        'min_stock',
        'category_name',
        'unit_name',
    ];

    if (!in_array($sortBy, $allowedSorts)) {
        $sortBy = 'item_name';
    }

    $items = Item::query()
        ->select('items.*')
        ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
        ->leftJoin('units', 'items.unit_id', '=', 'units.id')
        ->with(['category', 'unit'])

        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('items.item_name', 'like', "%{$search}%")
                  ->orWhere('items.item_code', 'like', "%{$search}%")
                  ->orWhere('categories.category_name', 'like', "%{$search}%")
                  ->orWhere('units.unit_name', 'like', "%{$search}%");
            });
        })

        ->orderBy(
            match ($sortBy) {
                'category_name' => 'categories.category_name',
                'unit_name'     => 'units.unit_name',
                default         => "items.$sortBy",
            },
            $sortDir
        )

        ->paginate($perPage)
        ->withQueryString();

    return view('admin.items.index', compact(
        'items', 'search', 'perPage', 'sortBy', 'sortDir'
    ));
}


    public function create()
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('admin.items.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name'   => 'required|string|max:255|unique:items,item_name',
            'category_id' => 'required|exists:categories,id',
            'unit_id'     => 'required|exists:units,id',
            'condition'   => 'required|in:good,damaged',
            'min_stock'   => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        //Database Transaction untuk keamanan data
        return DB::transaction(function () use ($validated) {
            $category = Category::findOrFail($validated['category_id']);
            $categoryCode = $category->category_code;

            //item terakhir untuk generate nomor urut
            $latestItem = Item::where('item_code', 'like', "%-ITM-%")
                ->orderBy('id', 'desc')
                ->first();

            $number = 1;
            if ($latestItem) {
                // Mengambil angka di belakang prefix '-ITM-'
                $parts = explode('-ITM-', $latestItem->item_code);
                $lastNumber = intval(end($parts));
                $number = $lastNumber + 1;
            }

            $code = $categoryCode . '-ITM-' . str_pad($number, 3, '0', STR_PAD_LEFT);

            Item::create([
                'item_code'   => $code,
                'item_name'   => $validated['item_name'],
                'category_id' => $validated['category_id'],
                'unit_id'     => $validated['unit_id'],
                'condition'   => $validated['condition'],
                'min_stock'   => $validated['min_stock'],
                'stock'       => 0,
                'description' => $validated['description'],
            ]);

            return redirect()->route('admin.items.index')
                ->with('success', 'Item berhasil ditambahkan dengan kode: ' . $code);
        });
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        $units = Unit::all();
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
        ]);

        $item->update($validated);

        return redirect()->route('admin.items.index')
            ->with('success', 'Item updated successfully');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return back()->with('success', 'Item deleted successfully');
    }

    /**
     * Search Categories for Select2
     */
    public function searchCategories(Request $request)
    {
        $search = $request->input('q', '');

        $query = Category::query();

        if (!empty($search)) {
            $query->where('category_name', 'like', "%{$search}%");
        }

        $categories = $query
            ->orderBy('category_name')
            ->limit(20)
            ->get();

        $results = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'text' => $category->category_name,
            ];
        });

        return response()->json([
            'results' => $results,
        ]);
    }

    /**
     * Search Units for Select2
     */
    public function searchUnits(Request $request)
    {
        $search = $request->input('q', '');

        $query = Unit::query();

        if (!empty($search)) {
            $query->where('unit_name', 'like', "%{$search}%");
        }

        $units = $query
            ->orderBy('unit_name')
            ->limit(20)
            ->get();

        $results = $units->map(function ($unit) {
            return [
                'id' => $unit->id,
                'text' => $unit->unit_name,
            ];
        });

        return response()->json([
            'results' => $results,
        ]);
    }

    /**
     * Search Items for Select2
     */
    public function searchItems(Request $request)
{
    $search = $request->input('q', '');
    $perPage = $request->per_page ?? 10;
    $exclude = $request->input('exclude', '');
    $excludeIds = $exclude ? explode(',', $exclude) : [];
    $sortBy  = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowedSorts = ['category_code', 'category_name', 'created_at'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }


    $query = Item::query()->with('unit');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('item_name', 'like', "%{$search}%")
              ->orWhere('item_code', 'like', "%{$search}%")
              ->orWhere('category_id', 'like', "%{$search}%")
              ->orWhere('unit_id', 'like', "%{$search}%");
        })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.items.index', compact('items', 'search', 'perPage', 'sortBy', 'sortDir'));;
    }

    if (!empty($excludeIds)) {
        $query->whereNotIn('id', $excludeIds);
    }

    $items = $query
        ->orderBy('item_name')
        ->limit(20)
        ->get();

    return response()->json([
        'results' => $items->map(function ($item) {
            return [
                'id'      => $item->id,
                'text'    => $item->item_name,
                'stock'   => $item->stock,
                'unit'    => $item->unit ? $item->unit->unit_name : 'N/A',
                'unit_id' => $item->unit_id,
            ];
        }),
    ]);
}


    public function showImport()
    {
        return view('admin.items.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB
        ], [
            'file.required' => 'File harus dipilih',
            'file.file'     => 'File tidak valid',
            'file.max'      => 'Ukuran file maksimal 10MB',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (! in_array($ext, ['xlsx', 'xls', 'csv'])) {
            return back()->withErrors(['file' => 'File harus bertipe XLSX, XLS, atau CSV']);
        }

        // Hitung checksum untuk mencegah import duplikat
        $fileHash = sha1_file($file->getRealPath());

        // Cek file sudah pernah di-import apa belum
        $existing = ImportRecord::where('hash', $fileHash)->first();
        if ($existing) {
            return back()->withErrors([
                'file' => 'File ini sudah pernah diimpor pada ' . $existing->created_at->format('Y-m-d H:i:s') . '.'
            ]);
        }

        try {
            Log::info('File import:', [
                'name' => $file->getClientOriginalName(),
                'ext'  => $ext,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'hash' => $fileHash,
            ]);

            // Quick validation untuk XLSX/XLS (cek ZIP magic + struktur minimal)
            if (in_array($ext, ['xlsx', 'xls'])) {
                $handle = fopen($file->getRealPath(), 'rb');
                $magic = $handle ? fread($handle, 2) : null;
                if ($handle) fclose($handle);

                if ($magic !== 'PK') {
                    return back()->withErrors(['file' => 'File XLSX/XLS tampaknya corrupt atau bukan file Excel yang valid.']);
                }

                $zip = new \ZipArchive();
                if ($zip->open($file->getRealPath()) !== true) {
                    return back()->withErrors(['file' => 'Tidak dapat membuka file ZIP—file mungkin corrupt.']);
                }
                if ($zip->locateName('_rels/.rels') === false) {
                    $zip->close();
                    return back()->withErrors(['file' => 'File tidak memiliki struktur Excel yang valid (_rels/.rels tidak ditemukan).']);
                }
                $zip->close();
            }

            //import
            $import = new ItemsImport();
            Excel::import($import, $file);

            $importedCount = method_exists($import, 'getImportedCount') ? $import->getImportedCount() : 0;
            $errors = method_exists($import, 'getErrors') ? $import->getErrors() : [];

            // Simpan record import untuk mencegah duplikat
            ImportRecord::create([
                'filename' => $file->getClientOriginalName(),
                'hash'     => $fileHash,
                'user_id'  => $request->user() ? $request->user()->id : null,
            ]);

            if (!empty($errors)) {
                return back()
                    ->with('warning', "{$importedCount} items imported with " . count($errors) . " errors")
                    ->with('import_count', $importedCount)
                    ->with('import_errors', $errors);
            }

            return back()
                ->with('success', "{$importedCount} items imported successfully")
                ->with('import_count', $importedCount);

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            Log::error('Excel parse error', ['error' => $e->getMessage()]);
            return back()->withErrors(['file' => 'File XLSX/XLS corrupt atau tidak valid: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('Import failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }
}
