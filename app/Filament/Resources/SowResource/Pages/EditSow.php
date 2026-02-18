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
     * Load kategori dan merk dari inventaris_id saat form di-mount
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (!empty($data['inventaris_id'])) {
            $inventaris = \App\Models\Inventaris::find($data['inventaris_id']);
            if ($inventaris) {
                $data['kategori'] = $inventaris->Kategori;
                $data['merk'] = $inventaris->Merk;
            }
        }
        return $data;
    }

    /**
     * Transfer pic_new ke pic_id jika dipilih tambah baru
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['pic_id']) && $data['pic_id'] === '__new__' && !empty($data['pic_new'])) {
            $data['pic_id'] = $data['pic_new'];
        }
        if (isset($data['pic_new'])) {
            unset($data['pic_new']);
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
}
