<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MovementReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    private $movements;
    private $type; // 'incoming' or 'outgoing'

    public function __construct($movements, $type = 'incoming')
    {
        $this->movements = $movements;
        $this->type = $type;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->movements as $index => $movement) {
            if ($this->type === 'incoming') {
                $rows->push([
                    'No' => $index + 1,
                    'Kode' => $movement->code,
                    'Tanggal' => \Carbon\Carbon::parse($movement->incoming_date)->format('d-m-Y'),
                    'Admin' => $movement->admin?->name ?? '-',
                    'Supplier/Requester' => $movement->supplier?->supplier_name ?? '-',
                    'Total Items' => $movement->details?->count() ?? 0,
                    'Item Code' => '',
                    'Item Name' => '',
                    'Unit' => '',
                    'Quantity' => '',
                    'Note' => '',
                ]);

                if ($movement->details && $movement->details->count()) {
                    foreach ($movement->details as $detail) {
                        $rows->push([
                            'No' => '',
                            'Kode' => '',
                            'Tanggal' => '',
                            'Admin' => '',
                            'Supplier/Requester' => '',
                            'Total Items' => '',
                            'Item Code' => $detail->item?->item_code ?? '-',
                            'Item Name' => $detail->item?->item_name ?? '-',
                            'Unit' => $detail->unit?->unit_name ?? '-',
                            'Quantity' => $detail->quantity ?? 0,
                            'Note' => $detail->notes ?? '',
                        ]);
                    }
                }
            } else {
                $rows->push([
                    'No' => $index + 1,
                    'Kode' => $movement->code,
                    'Tanggal' => \Carbon\Carbon::parse($movement->outgoing_date)->format('d-m-Y'),
                    'Admin' => $movement->admin?->name ?? '-',
                    'Supplier/Requester' => $movement->departement?->departement_name ?? $movement->destination ?? '-',
                    'Total Items' => $movement->details?->count() ?? 0,
                    'Item Code' => '',
                    'Item Name' => '',
                    'Unit' => '',
                    'Quantity' => '',
                    'Note' => '',
                ]);

                if ($movement->details && $movement->details->count()) {
                    foreach ($movement->details as $detail) {
                        $rows->push([
                            'No' => '',
                            'Kode' => '',
                            'Tanggal' => '',
                            'Admin' => '',
                            'Supplier/Requester' => '',
                            'Total Items' => '',
                            'Item Code' => $detail->item?->item_code ?? '-',
                            'Item Name' => $detail->item?->item_name ?? '-',
                            'Unit' => $detail->unit?->unit_name ?? '-',
                            'Quantity' => $detail->quantity ?? 0,
                            'Note' => $detail->condition ?? '',
                        ]);
                    }
                }
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['No', 'Kode', 'Tanggal', 'Admin', 'Supplier/Requester', 'Total Items', 'Item Code', 'Item Name', 'Unit', 'Quantity', 'Note'];
    }

    public function styles(Worksheet $sheet)
    {
        // compute last row based on built rows (movements + details)
        $rowsCount = 0;
        foreach ($this->movements as $m) {
            $rowsCount += 1; // movement row
            if ($m->details) {
                $rowsCount += $m->details->count();
            }
        }
        $lastRow = $rowsCount + 1;

        $sheet->getStyle('A1:K1')->applyFromArray([
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

        $sheet->getStyle('A2:K' . $lastRow)->applyFromArray([
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
