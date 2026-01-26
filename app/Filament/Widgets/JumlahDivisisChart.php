<?php

namespace App\Filament\Widgets;

use App\Models\Sow;
use Filament\Widgets\ChartWidget;

class JumlahDivisisChart extends ChartWidget
{
    protected static ?string $heading = ' ';

    protected function getData(): array
    {
        // Hitung jumlah SOW per divisi
        $divisiCounts = Sow::query()
            ->select('divisi')
            ->get()
            ->filter(fn ($sow) => filled($sow->divisi))
            ->groupBy('divisi')
            ->map(fn ($group) => $group->count());

        // Mapping warna sesuai divisi
        $colorMap = [
            'MCP' => '#ef4444', // merah
            'MKM' => '#3b82f6', // biru
            'MKP' => '#f97316', // oranye
            'PPM' => '#22c55e', // hijau
            'PPG' => '#facc15', // kuning
        ];

        $labels = $divisiCounts->keys()->toArray();
        $data   = $divisiCounts->values()->toArray();
        $colors = collect($labels)->map(fn ($divisi) => $colorMap[$divisi] ?? '#9ca3af')->toArray();

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                    'borderColor' => 'transparent',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                 'title' => [ 
                    'display' => true, 
                    'text' => "Jumlah per Divisi", 
                    'align' => 'center', // judul di tengah 
                    'font' => [ 'size' => 18, 'weight' => 'bold', ],
                ],
            ],
        ];
    }
}
