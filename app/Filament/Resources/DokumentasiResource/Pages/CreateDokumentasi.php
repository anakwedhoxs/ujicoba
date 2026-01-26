<?php

namespace App\Filament\Resources\DokumentasiResource\Pages;

use App\Filament\Resources\DokumentasiResource;
use Filament\Actions\Action; // <-- tambahkan ini
use Filament\Resources\Pages\CreateRecord;

class CreateDokumentasi extends CreateRecord
{
    protected static string $resource = DokumentasiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            // ✅ CANCEL
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
