<?php

namespace App\Filament\Resources\PicResource\Pages;

use App\Filament\Resources\PicResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePic extends CreateRecord
{
    protected static string $resource = PicResource::class;

    protected function authorizeAccess(): void
    {
        abort_if(!auth()->user()->hasRole('admin'), 403);
    }
}
