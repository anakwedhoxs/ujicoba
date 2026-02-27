<?php

namespace App\Exports;

use App\Models\SowPc;
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

class SowPcExport implements
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

    /*
    |--------------------------------------------------------------------------
    | LOGO
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | DATA QUERY
    |--------------------------------------------------------------------------
    */
    public function collection()
    {
        return SowPc::with([
                'case',
                'psu',
                'prosesor',
                'ram',
                'motherboard',
                'hostname'
            ])
            ->when($this->divisi, fn (Builder $q) =>
                $q->where('divisi', $this->divisi)
            )
            ->orderBy('tanggal_penggunaan', 'desc')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | MAPPING DATA KE KOLOM
    |--------------------------------------------------------------------------
    */
    public function map($sow): array
{
    static $no = 1;

    return [
        $no++,

        // CASHING DAN PSU (Gabung Merk dan Seri)
        strtoupper(
            ($sow->case?->Merk ?? '-') . ' / ' .
            ($sow->psu?->Merk ?? '-') . ' ' . 
            ($sow->psu?->Seri ?? '') // Gabung merk dan seri untuk PSU
        ),

        // PROSESOR DAN RAM (Gabung Merk dan Seri)
        strtoupper(
            ($sow->prosesor?->Merk ?? '-') . ' ' .
            ($sow->prosesor?->Seri ?? '') . ' / ' .
            ($sow->ram?->Merk ?? '-') . ' ' . 
            ($sow->ram?->Seri ?? '')
        ),

        // MOTHERBOARD (Gabung Merk dan Seri)
        strtoupper($sow->motherboard?->Merk ?? '-' . ' ' . $sow->motherboard?->Seri ?? '-'),

        optional($sow->tanggal_perbaikan)->format('d/m/Y'),
        optional($sow->tanggal_penggunaan)->format('Y'),

        $sow->helpdesk ? 'V' : '',
        $sow->form ? 'V' : '',

        $sow->nomor_perbaikan ?? '-',
        $sow->hostname?->nama ?? '-',
        $sow->keterangan ?? '-',
    ];
}

    /*
    |--------------------------------------------------------------------------
    | DATA MULAI DARI BARIS 8
    |--------------------------------------------------------------------------
    */
    public function startCell(): string
    {
        return 'A8';
    }

    /*
    |--------------------------------------------------------------------------
    | STYLING & HEADER
    |--------------------------------------------------------------------------
    */
    public function styles(Worksheet $sheet)
    {
        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */
        $sheet->mergeCells('E3:G3');
        $sheet->setCellValue('E3', 'S.O.W (Stock Out Work)');
        $sheet->getStyle('E3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | HEADER TABEL (BARIS 6–7)
        |--------------------------------------------------------------------------
        */
        $sheet->setCellValue('A6', 'No');
        $sheet->setCellValue('B6', 'CASHING DAN PSU');
        $sheet->setCellValue('C6', 'PROSESOR DAN RAM');
        $sheet->setCellValue('D6', 'MOTHERBOARD');
        $sheet->setCellValue('E6', 'Tanggal Perbaikan');
        $sheet->setCellValue('F6', 'Tanggal Pemakaian');

       //$sheet->getColumnDimension('B')->setWidth(0.83);

        $sheet->mergeCells('G6:H6');
        $sheet->setCellValue('G6', 'SPPI');

        $sheet->setCellValue('I6', 'Nomor Perbaikan');
        $sheet->setCellValue('J6', 'Hostname');
        $sheet->setCellValue('K6', 'Keterangan Perbaikan');

        $sheet->setCellValue('G7', 'Helpdesk');
        $sheet->setCellValue('H7', 'Form');

        foreach (['A','B','C','D','E','F','I','J','K'] as $col) {
            $sheet->mergeCells("{$col}6:{$col}7");
        }

        /*
        |--------------------------------------------------------------------------
        | STYLE HEADER
        |--------------------------------------------------------------------------
        */
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

        $sheet->getRowDimension(6)->setRowHeight(25);
        $sheet->getRowDimension(7)->setRowHeight(20);

        /*
        |--------------------------------------------------------------------------
        | BORDER SEMUA TABEL
        |--------------------------------------------------------------------------
        */
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A6:K{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle('thin');

        /*
        |--------------------------------------------------------------------------
        | ALIGNMENT DATA
        |--------------------------------------------------------------------------
        */
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