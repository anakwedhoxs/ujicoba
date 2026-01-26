<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SowArsipResource\Pages;
use App\Models\SowArsip;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SowArsipResource extends Resource
{
    protected static ?string $model = SowArsip::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Arsip SOW';
    protected static ?string $navigationGroup = 'Arsip SOW';


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Arsip')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->counts('items'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Arsip')
                    ->dateTime(),
            ])
            
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\DeleteAction::make()
                ->label('Hapus Arsip')
                ->requiresConfirmation()
                ->modalHeading('Hapus Arsip SOW')
                ->modalDescription('Semua data di dalam arsip ini juga akan ikut terhapus.')
                ->modalSubmitActionLabel('Ya, Hapus'),
                
            ]);
    }

    // ⬇️ PAKAI RELATION MANAGER
    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\SowArsipResource\RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSowArsips::route('/'),
            'view'  => Pages\ViewSowArsip::route('/{record}'),
        ];
    }
}
