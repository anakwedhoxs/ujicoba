<?php

namespace App\Filament\Resources\DokumentasiArsipResource\Pages;

use App\Filament\Resources\DokumentasiArsipResource;
use Filament\Resources\Pages\ViewRecord;

class ViewDokumentasiArsip extends ViewRecord
{
    protected static string $resource = DokumentasiArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
