<?php

namespace App\Filament\Resources\RekapArsipResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use App\Exports\RekapArsipItemExport;
use Maatwebsite\Excel\Facades\Excel;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'kategori';

    // ================= FORM =================
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kategori')
                    ->label('Kategori')
                    ->placeholder('Masukkan kategori')
                    ->required(),

                Forms\Components\TextInput::make('merk')
                    ->label('Merk')
                    ->placeholder('Masukkan merk')
                    ->required(),

                Forms\Components\TextInput::make('seri')
                    ->label('Seri')
                    ->placeholder('Masukkan seri')
                    ->required(),

                Forms\Components\TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    // ================= TABLE =================
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kategori')
            ->columns([
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('merk')
                    ->label('Merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seri')
                    ->label('Seri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->numeric(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Export Rekap')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $rekapArsipId = $this->getOwnerRecord()->id;
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\RekapArsipItemExport($rekapArsipId),
                            'rekap_arsip_items.xlsx'
                        );
                    }),
            ]);

    }
}
