<?php

namespace App\Filament\Resources\PicResource\Pages;

use App\Filament\Resources\PicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPic extends EditRecord
{
    protected static string $resource = PicResource::class;

    protected function authorizeAccess(): void
    {
        abort_if(!auth()->user()->hasRole('admin'), 403);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
