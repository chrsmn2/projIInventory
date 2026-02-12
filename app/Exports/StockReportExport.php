<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StockReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    private $items;
    private $reportType; // 'all', 'low', 'out_of_stock', 'damaged'

    public function __construct($items, $reportType = 'all')
    {
        $this->items = $items;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        return collect($this->items)->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Kode Barang' => $item->item_code,
                'Nama Barang' => $item->item_name,
                'Kategori' => $item->category?->name ?? '-',
                'Kondisi' => ucfirst($item->condition),
                'Stok' => $item->stock,
                'Stok Minimum' => $item->min_stock,
                'Unit' => $item->unit?->name ?? '-',
                'Deskripsi' => $item->description ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Kondisi', 'Stok', 'Stok Minimum', 'Unit', 'Deskripsi'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '374151'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        $sheet->getStyle('A2:I' . ($this->items->count() + 1))->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        return [];
    }
}
