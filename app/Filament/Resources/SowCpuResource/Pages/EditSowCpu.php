<?php

namespace App\Filament\Resources\SowCpuResource\Pages;

use App\Filament\Resources\SowCpuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSowCpu extends EditRecord
{
    protected static string $resource = SowCpuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
