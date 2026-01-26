<?php

namespace App\Filament\Resources\SowResource\Pages;

use App\Filament\Resources\SowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;


class ListSows extends ListRecords
{
    protected static string $resource = SowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    
}
