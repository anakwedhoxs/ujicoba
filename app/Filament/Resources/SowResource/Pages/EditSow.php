<?php

namespace App\Filament\Resources\SOWResource\Pages;

use App\Filament\Resources\SOWResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSOW extends EditRecord
{
    protected static string $resource = SOWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
}
