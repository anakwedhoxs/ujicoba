<?php

namespace App\Filament\Resources\SowPcResource\Pages;

use App\Filament\Resources\SowPcResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSowPc extends CreateRecord
{
    protected static string $resource = SowPcResource::class;

    // Redirect setelah berhasil membuat data
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // otomatis ke halaman list
    }
}