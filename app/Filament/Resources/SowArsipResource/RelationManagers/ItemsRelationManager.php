<?php

namespace App\Filament\Resources\SowArsipResource\RelationManagers;

use App\Exports\SowArsipExport;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Isi Arsip';

    public function table(Table $table): Table
    {
        return $table
            /* ================= FILTER ================= */
            ->filters([
                Tables\Filters\SelectFilter::make('divisi')
                    ->label('Divisi')
                    ->options([
                        'MKM' => 'MKM',
                        'PPG' => 'PPG',
                        'MKP' => 'MKP',
                        'MCP' => 'MCP',
                        'PPM' => 'PPM',
                    ]),
            ])

            /* ================= EXPORT BUTTON ================= */
            ->headerActions([
                Action::make('export')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        // Ambil state filter aktif
                        $filters = $this->getTableFiltersForm()->getState();

                        // Ambil divisi dari filter
                        $divisi = $filters['divisi'] ?? null;
                        if (is_array($divisi)) {
                            $divisi = reset($divisi);
                        }

                        // Ambil parent record ID
                        $arsipId = $this->getOwnerRecord()->id;

                        return Excel::download(
                            new SowArsipExport($arsipId, $divisi),
                            'sow-arsip.xlsx'
                        );
                    }),
            ])


            /* ================= COLUMNS ================= */
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

                Tables\Columns\TextColumn::make('tanggal_penggunaan')
                    ->label('Tanggal Penggunaan')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('tanggal_perbaikan')
                    ->label('Tanggal Perbaikan')
                    ->date('d/m/Y'),

                Tables\Columns\IconColumn::make('helpdesk')->boolean(),
                Tables\Columns\IconColumn::make('form')->boolean(),

                Tables\Columns\TextColumn::make('nomor_perbaikan')->searchable(),
                Tables\Columns\TextColumn::make('hostname')->searchable(),
                Tables\Columns\TextColumn::make('divisi')->searchable(),
                Tables\Columns\TextColumn::make('keterangan')->wrap(),
                Tables\Columns\TextColumn::make('pic')->searchable(),

                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->height(80)
                    ->extraImgAttributes([
                        'style' => 'object-fit: cover;',
                    ]),
            ])
            ->defaultSort('id', 'desc')
            ->paginated([10, 25, 50]);
    }
}
