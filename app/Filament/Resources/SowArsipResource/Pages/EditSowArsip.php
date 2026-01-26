<?php

namespace App\Filament\Resources\SowArsipResource\Pages;

use App\Filament\Resources\SowArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSowArsip extends EditRecord
{
    protected static string $resource = SowArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
