<?php

namespace App\Filament\Resources\SOWResource\Pages;

use App\Filament\Resources\SOWResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateSOW extends CreateRecord
{
    protected static string $resource = SOWResource::class;

    /**
     * Transfer pic_new ke pic jika dipilih tambah baru
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['pic'] === '__new__' && !empty($data['pic_new'])) {
            $data['pic'] = $data['pic_new'];
        }
        unset($data['pic_new']);
        return $data;
    }

    /**
     * Setelah save, kembali ke index
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Tombol di bawah form (Cancel + Save)
     */
    protected function getFormActions(): array
    {
        return [
            // ✅ CANCEL (tanpa icon, di samping Save)
            Action::make('cancel')
                ->label('Cancel')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),

            // ✅ SAVE + KONFIRMASI
            Action::make('save')
                ->label('Save')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('DATA AKAN DITAMBAH')
                ->modalDescription('Apakah kamu yakin ingin menambahkan data baru?')
                ->modalSubmitActionLabel('OK')
                ->modalCancelActionLabel('Cancel')
                ->action(fn () => $this->create()),
        ];
    }
}
