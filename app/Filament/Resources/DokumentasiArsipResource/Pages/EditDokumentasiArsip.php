<?php

namespace App\Filament\Resources\DokumentasiArsipResource\Pages;

use App\Filament\Resources\DokumentasiArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDokumentasiArsip extends EditRecord
{
    protected static string $resource = DokumentasiArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
