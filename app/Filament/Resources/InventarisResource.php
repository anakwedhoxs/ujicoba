<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarisResource\Pages;
use App\Models\Inventaris;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InventarisResource extends Resource
{
    protected static ?string $model = Inventaris::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Inventaris';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('Kategori')
                            ->label('Hardware')
                            ->placeholder('Masukkan Hardware')
                            ->required(),

                        Forms\Components\TextInput::make('Merk')
                            ->label('Merk')
                            ->placeholder('Masukkan Merk')
                            ->required(),

                        Forms\Components\TextInput::make('Seri')
                            ->label('Seri')
                            ->placeholder('Masukkan Seri')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Kategori')->searchable(),
                Tables\Columns\TextColumn::make('Merk')->searchable(),
                Tables\Columns\TextColumn::make('Seri')->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventaris::route('/'),
            'create' => Pages\CreateInventaris::route('/create'),
            'edit' => Pages\EditInventaris::route('/{record}/edit'),
        ];
    }
}
