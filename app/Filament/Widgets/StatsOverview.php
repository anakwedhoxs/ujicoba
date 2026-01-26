<?php

namespace App\Filament\Widgets;

use App\Models\Sow;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSow      = Sow::count();
        $totalRejected = Sow::where('status', true)->count();
        $totalAccepted = Sow::where('status', false)->count();

        return [
            Stat::make('Jumlah SOW', $totalSow)
                ->icon('heroicon-s-queue-list')
                ->color('primary')
                ->extraAttributes([
                    'style' => 'border: 2px solid #3b82f6; border-radius: 0.80rem;',
                ]),

            Stat::make('Jumlah Accepted', $totalAccepted)
                ->icon('heroicon-s-check-circle')
                ->color('success')
                ->extraAttributes([
                    'style' => 'border: 2px solid #22c55e; border-radius: 0.80rem;',
                ]),

            Stat::make('Jumlah Rejected', $totalRejected)
                ->icon('heroicon-s-x-circle')
                ->color('danger')
                ->extraAttributes([
                    'style' => 'border: 2px solid #ef4444; border-radius: 0.80rem;',
                ]),

        ];
    }
}
