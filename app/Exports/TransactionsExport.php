<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransactionsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return Transaction::with([
            'user',
            'items.product'
        ])
            ->filter($this->request)
            ->latest()
            ->get();
    }

    public function map($trx): array
    {
        $products = $trx->items
            ->map(function ($item) {
                return $item->product->name .
                    ' (' . $item->qty . 'x)';
            })
            ->implode(", \n");

        return [
            $trx->id,
            $trx->user->name,
            $products,
            ucfirst($trx->status),
            $trx->resi ?? '-',
            'Rp ' . number_format(
                $trx->total,
                0,
                ',',
                '.'
            ),
            $trx->receiver_name,
            $trx->phone,
            $trx->created_at->format(
                'd-m-Y H:i'
            )
        ];
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Pembeli',
            'Produk Dibeli',
            'Status',
            'Resi',
            'Total',
            'Penerima',
            'Telepon',
            'Tanggal'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // Header style
        $sheet->getStyle('A1:I1')
            ->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => [
                        'rgb' => '16A34A'
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

        // Border semua cell
        $sheet->getStyle(
            'A1:I' . $highestRow
        )->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin'
                ]
            ]
        ]);

        // Vertical center
        $sheet->getStyle(
            'A:I'
        )->getAlignment()->setVertical(
            Alignment::VERTICAL_CENTER
        );

        // Wrap text produk
        $sheet->getStyle(
            'C:C'
        )->getAlignment()->setWrapText(true);

        // Width produk
        $sheet->getColumnDimension('C')
            ->setWidth(45);

        // Tinggi row otomatis
        foreach (
            range(
                2,
                $highestRow
            ) as $row
        ) {
            $sheet
                ->getRowDimension($row)
                ->setRowHeight(-1);
        }

        return [];
    }
}
