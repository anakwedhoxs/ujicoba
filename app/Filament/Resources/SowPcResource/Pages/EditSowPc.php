<?php

namespace App\Filament\Resources\SowPcResource\Pages;

use App\Filament\Resources\SowPcResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSowPc extends EditRecord
{
    protected static string $resource = SowPcResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
