<?php

namespace App\Filament\Resources\RekapArsipResource\Pages;

use App\Filament\Resources\RekapArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekapArsip extends EditRecord
{
    protected static string $resource = RekapArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
