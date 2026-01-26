<?php

namespace App\Filament\Resources\SowArsipResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Isi Arsip';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inventaris.Kategori')
                    ->label('Hardware')
                    ->searchable(),

                Tables\Columns\TextColumn::make('inventaris.Merk')
                    ->label('Merk')
                    ->searchable(),

                Tables\Columns\TextColumn::make('inventaris.Seri')
                    ->label('Seri')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_penggunaan')->date(),
                Tables\Columns\TextColumn::make('tanggal_perbaikan')->date(),

                Tables\Columns\IconColumn::make('helpdesk')->boolean(),
                Tables\Columns\IconColumn::make('form')->boolean(),

                Tables\Columns\TextColumn::make('nomor_perbaikan')->searchable(),
                Tables\Columns\TextColumn::make('hostname')->searchable(),
                Tables\Columns\TextColumn::make('divisi')->searchable(),
                Tables\Columns\TextColumn::make('keterangan')->wrap(),

                // ✅ FOTO SEBAGAI GAMBAR
                Tables\Columns\ImageColumn::make('foto')
                    ->disk('public')
                    ->height(50),
            ])
            ->defaultSort('id', 'desc')
            ->paginated([10, 25, 50]);
    }
}
