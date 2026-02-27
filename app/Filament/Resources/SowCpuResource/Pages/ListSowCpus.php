<?php

namespace App\Filament\Resources\SowCpuResource\Pages;

use App\Filament\Resources\SowCpuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSowCpus extends ListRecords
{
    protected static string $resource = SowCpuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
