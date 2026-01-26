<?php

namespace App\Filament\Widgets;

use App\Models\Sow;
use Filament\Widgets\ChartWidget;

class JumlahKategorisChart extends ChartWidget
{
   protected static ?string $heading = ' ';

    protected function getData(): array
    {
        $kategoriCounts = Sow::with('inventaris')
            ->get()
            ->filter(fn ($sow) => filled($sow->inventaris?->Kategori))
            ->groupBy(fn ($sow) => $sow->inventaris->Kategori)
            ->map(fn ($group) => $group->count());

        return [
            'datasets' => [
                [
                    'data' => $kategoriCounts->values()->toArray(),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#22c55e',
                        '#ef4444',
                        '#f59e0b',
                        '#6366f1',
                    ],
                    'borderWidth' => 0,
                    'borderColor' => 'transparent',
                ],
            ],
            'labels' => $kategoriCounts->keys()->toArray(),
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
                    'text' => "Jumlah per Kategori", 
                    'align' => 'center', // judul di tengah 
                    'font' => [ 'size' => 18, 'weight' => 'bold', ],
                ],
            ],
        ];
    }
}
