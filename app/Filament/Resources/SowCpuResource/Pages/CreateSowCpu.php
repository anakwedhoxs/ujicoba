<?php

namespace App\Filament\Resources\SowCpuResource\Pages;

use App\Filament\Resources\SowCpuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSowCpu extends CreateRecord
{
    protected static string $resource = SowCpuResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // otomatis ke halaman list
    }

}
