<?php

namespace App\Exports;

use App\Models\SowCpu;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SowCpuExport implements
    FromCollection,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithCustomStartCell,
    WithDrawings
{
    protected ?string $divisi;

    public function __construct(?string $divisi = null)
    {
        $this->divisi = $divisi;
    }

    // Logo perusahaan berdasarkan divisi
    public function drawings()
    {
        $logoPath = match (strtoupper($this->divisi)) {
            'MKM' => public_path('images/mkm.png'),
            'PPG' => public_path('images/ppg.png'),
            'MKP' => public_path('images/MKP.png'),
            'MCP' => public_path('images/MCP.png'),
            'PPM' => public_path('images/PPM.png'),
            default => public_path('images/Logo_cargloss_Paint.png'),
        };

        if (!file_exists($logoPath)) {
            return [];
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo Perusahaan');
        $drawing->setPath($logoPath);
        $drawing->setHeight(40);
        $drawing->setCoordinates('A3');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return [$drawing];
    }

    // Query data SowCpu dengan relasi dan filter divisi jika ada
    public function collection()
    {
        return SowCpu::with([
                'prosesor',
                'ram',
                'motherboard',
                'hostname',
                'pic' // jika ingin ditampilkan juga, bisa ditambahkan mapping
            ])
            ->when($this->divisi, fn (Builder $q) =>
                $q->where('divisi', $this->divisi)
            )
            ->orderBy('tanggal_penggunaan', 'desc')
            ->get();
    }

    // Mapping data tiap baris ke kolom Excel
    public function map($sow): array
    {
        static $no = 1;

        return [
            $no++,
            strtoupper(($sow->prosesor?->Merk ?? '-') . ' ' . ($sow->prosesor?->Seri ?? '')),
            strtoupper(($sow->ram?->Merk ?? '-') . ' ' . ($sow->ram?->Seri ?? '')),
            strtoupper(($sow->motherboard?->Merk ?? '-') . ' ' . ($sow->motherboard?->Seri ?? '')),
            optional($sow->tanggal_perbaikan)->format('d/m/Y') ?? '-',
            optional($sow->tanggal_penggunaan)->format('d/m/Y') ?? '-',
            $sow->helpdesk ? 'V' : '',
            $sow->form ? 'V' : '',
            $sow->nomor_perbaikan ?? '-',
            $sow->hostname?->nama ?? '-',
            $sow->keterangan ?? '-',
        ];
    }

    // Mulai data dari cell A8
    public function startCell(): string
    {
        return 'A8';
    }

    // Styling dan header sheet sesuai gambar
    public function styles(Worksheet $sheet)
    {
        // Judul S.O.W di tengah (merge E3:G3)
        $sheet->mergeCells('E3:G3');
        $sheet->setCellValue('E3', 'HARDWARE: CPU SET');
        $sheet->getStyle('E3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
        ]);

        // Header kolom baris 6 dan 7
        $sheet->setCellValue('A6', 'No');
        $sheet->setCellValue('B6', 'PROCESSOR');
        $sheet->setCellValue('C6', 'RAM');
        $sheet->setCellValue('D6', 'MOTHERBOARD');
        $sheet->setCellValue('E6', 'Tanggal Perbaikan');
        $sheet->setCellValue('F6', 'Tanggal Pemakaian');

        $sheet->mergeCells('G6:H6');
        $sheet->setCellValue('G6', 'SPPI');

        $sheet->setCellValue('I6', 'Nomor Perbaikan');
        $sheet->setCellValue('J6', 'Hostname');
        $sheet->setCellValue('K6', 'Keterangan Perbaikan');

        $sheet->setCellValue('G7', 'Helpdesk');
        $sheet->setCellValue('H7', 'Form');

        // Merge cell yang tidak memiliki sub kolom
        foreach (['A','B','C','D','E','F','I','J','K'] as $col) {
            $sheet->mergeCells("{$col}6:{$col}7");
        }

        // Style header
        $sheet->getStyle('A6:K7')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'E5E7EB']
            ],
        ]);

        // Atur tinggi baris header
        $sheet->getRowDimension(6)->setRowHeight(25);
        $sheet->getRowDimension(7)->setRowHeight(20);

        // Border seluruh tabel data dari A6 sampai kolom K dan baris terakhir
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A6:K{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle('thin');

        // Alignment kolom spesifik
        $sheet->getStyle("A8:A{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("G8:H{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("E8:F{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("I8:I{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }
}