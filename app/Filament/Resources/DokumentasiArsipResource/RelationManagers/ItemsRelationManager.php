<?php

namespace App\Filament\Resources\DokumentasiArsipResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DokumentasiArsipExport;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Item Dokumentasi';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->wrap()
                    ->searchable(),

                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->height(80)
                    ->extraImgAttributes([
                        'style' => 'object-fit: cover;',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

                Action::make('export')
                    ->label('Export Dokumentasi Arsip')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        return Excel::download(
                            new DokumentasiArsipExport($this->ownerRecord->id),
                            'dokumentasi_arsip.xlsx'
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->paginated(false);
    }
}
