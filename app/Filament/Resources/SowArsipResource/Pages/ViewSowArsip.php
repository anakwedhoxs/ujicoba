<?php

namespace App\Filament\Resources\SowArsipResource\Pages;

use App\Filament\Resources\SowArsipResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class ViewSowArsip extends ViewRecord
{
    protected static string $resource = SowArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
