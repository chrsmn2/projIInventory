<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;

class ItemsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    private int $importedCount = 0;
    private array $errors = [];

    public function model(array $row)
    {
        if (empty($row['item_name'])) {
            return null;
        }

        try {
            $this->validateRow($row);

            DB::beginTransaction();

            /* ================= CATEGORY ================= */
            $category = Category::firstOrCreate(
                ['category_name' => trim($row['category_name'])],
                [
                    'category_code' => $this->generateNextCategoryCode(),
                    'category_description' => $row['category_description'] ?? null,
                ]
            );

            /* ================= UNIT ================= */
            $unit = Unit::firstOrCreate(
                ['unit_name' => trim($row['unit_name'])],
                [
                    'unit_code' => $this->generateNextUnitCode(),
                    'unit_description' => $row['unit_description'] ?? null,
                ]
            );

            /* ================= ITEM ================= */
            $itemName = trim($row['item_name']);
            $incomingStock = is_numeric($row['stock'] ?? null) ? (int)$row['stock'] : 0;

            $existingItem = Item::where('item_name', $itemName)->first();

            if ($existingItem) {
                // Item sudah ada, tambah stok saja (jangan buat item baru)
                $existingItem->increment('stock', $incomingStock);

                DB::commit();
                $this->importedCount++;

                return $existingItem;
            }

            /* ===== JIKA ITEM BARU ===== */
            $condition = strtolower(trim($row['condition']));
            if (!in_array($condition, ['good', 'damaged', 'normal', 'lost'])) {
                throw new \Exception("Invalid condition: {$condition}");
            }

            $minStock = (int)$row['min_stock'];
            if ($minStock < 0) {
                throw new \Exception("Min stock cannot be negative");
            }

            $item = Item::create([
                'item_code'   => $this->generateItemCode($category),
                'item_name'   => $itemName,
                'category_id' => $category->id,
                'unit_id'     => $unit->id,
                'condition'   => $condition,
                'min_stock'   => $minStock,
                'stock'       => $incomingStock,
                'description' => $row['description'] ?? null,
            ]);

            DB::commit();
            $this->importedCount++;

            return $item;

        } catch (\Exception $e) {
            DB::rollBack();

            $this->errors[] = [
                'item_name' => $row['item_name'] ?? 'Unknown',
                'error' => $e->getMessage(),
            ];

            return null;
        }
    }

    /* ================= VALIDATION ================= */
    private function validateRow(array $row): void
    {
        //cek field yang wajib diisi
        foreach (['item_name','category_name','unit_name','condition','min_stock'] as $field) {
            if (!isset($row[$field]) || trim((string)$row[$field]) === '') {
                throw new \Exception("Missing required field: {$field}");
            }
        }

        if (!is_numeric($row['min_stock']) || (int)$row['min_stock'] < 0) {
            throw new \Exception("Invalid min_stock value");
        }
    }

    /* ================= AUTO GENERATE CODE ================= */
    private function generateNextCategoryCode(): string
    {
        $prefix = 'CAT-';

        $last = Category::where('category_code', 'like', $prefix.'%')
            ->orderBy('category_code', 'desc')
            ->lockForUpdate()
            ->first();

        $num = $last ? intval(substr($last->category_code, 4)) + 1 : 1;

        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    private function generateNextUnitCode(): string
    {
        $prefix = 'UNT-';

        $last = Unit::where('unit_code', 'like', $prefix.'%')
            ->orderBy('unit_code', 'desc')
            ->lockForUpdate()
            ->first();

        $num = $last ? intval(substr($last->unit_code, 4)) + 1 : 1;

        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    private function generateItemCode(Category $category): string
    {
        $prefix = $category->category_code . '-ITM-';

        $last = Item::where('item_code', 'like', $prefix.'%')
            ->orderBy('item_code', 'desc')
            ->lockForUpdate()
            ->first();

        $num = $last ? intval(substr($last->item_code, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    /* ================= EXCEL ================= */
    public function chunkSize(): int
    {
        return 100;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
