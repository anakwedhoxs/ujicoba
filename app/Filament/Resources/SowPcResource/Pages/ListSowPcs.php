<?php

namespace App\Filament\Resources\SowPcResource\Pages;

use App\Filament\Resources\SowPcResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSowPcs extends ListRecords
{
    protected static string $resource = SowPcResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
