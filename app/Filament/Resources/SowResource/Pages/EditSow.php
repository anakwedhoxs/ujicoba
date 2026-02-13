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

    /**
     * Transfer pic_new ke pic jika dipilih tambah baru
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['pic'] === '__new__' && !empty($data['pic_new'])) {
            $data['pic'] = $data['pic_new'];
        }
        unset($data['pic_new']);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
}
