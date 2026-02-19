<?php

namespace App\Filament\Resources\PicResource\Pages;

use App\Filament\Resources\PicResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;

class ViewPic extends ViewRecord
{
    protected static string $resource = PicResource::class;

    /**
     * Schema form untuk menampilkan detail PIC
     */
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('nama')
                ->label('Nama PIC')
                ->disabled(), // hanya tampil, tidak bisa diubah
        ];
    }
}
