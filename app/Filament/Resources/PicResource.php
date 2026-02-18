<?php


namespace App\Filament\Resources;


use App\Filament\Resources\PicResource\Pages;
use App\Models\Pic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class PicResource extends Resource
{
    protected static ?string $model = Pic::class;


    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Data PIC';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 3;



    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama')
                ->required()
                ->maxLength(255)
                ->disabled(fn () => !auth()->user()->hasRole('admin')),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPics::route('/'),
            'create' => Pages\CreatePic::route('/create'),
            'edit' => Pages\EditPic::route('/{record}/edit'),
        ];
    }
}





