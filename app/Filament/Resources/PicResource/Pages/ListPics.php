<?php

namespace App\Filament\Resources\PicResource\Pages;

use App\Filament\Resources\PicResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Tables;

class ListPics extends ListRecords
{
    protected static string $resource = PicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(), // tombol New PIC
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }
}
