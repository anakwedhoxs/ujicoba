<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SOWResource\Pages;
use App\Models\Sow;
use App\Models\Inventaris;
use App\Exports\SowExport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class SOWResource extends Resource
{
    protected static ?string $model = Sow::class;

    protected static ?string $navigationIcon = 'heroicon-s-queue-list';
    protected static ?string $navigationLabel = 'Data SOW';
    protected static ?string $navigationGroup = 'SOW';
    protected static ?int $navigationSort = 1;

    /* ================= FORM ================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                // Hardware (Kategori)
                Forms\Components\Select::make('kategori')
                    ->label('Hardware')
                    ->options(
                        Inventaris::query()
                            ->select('Kategori')
                            ->distinct()
                            ->pluck('Kategori', 'Kategori')
                    )
                    ->live()
                    ->afterStateUpdated(function (callable $set) {
                        $set('merk', null);
                        $set('inventaris_id', null);
                    })
                        ->afterStateHydrated(function (...$args) {
                            $set = null;
                            $record = null;
                            foreach ($args as $a) {
                                if (is_callable($a)) $set = $a;
                                if (is_object($a) && method_exists($a, 'getKey')) $record = $a;
                            }
                            if ($set && $record) {
                                $set('kategori', $record->inventaris->Kategori ?? null);
                            }
                        })
                    ->required(),

                // Merk
                Forms\Components\Select::make('merk')
                    ->label('Merk')
                    ->options(fn (callable $get) =>
                        $get('kategori')
                            ? Inventaris::where('Kategori', $get('kategori'))
                                ->select('Merk')
                                ->distinct()
                                ->pluck('Merk', 'Merk')
                            : []
                    )
                    ->live()
                    ->afterStateUpdated(function (callable $set) {
                        $set('inventaris_id', null);
                    })
                        ->afterStateHydrated(function (...$args) {
                            $set = null;
                            $record = null;
                            foreach ($args as $a) {
                                if (is_callable($a)) $set = $a;
                                if (is_object($a) && method_exists($a, 'getKey')) $record = $a;
                            }
                            if ($set && $record) {
                                $set('merk', $record->inventaris->Merk ?? null);
                            }
                        })
                    ->required(),

                // Seri (Inventaris)
                Forms\Components\Select::make('inventaris_id')
                    ->label('Seri')
                    ->options(fn (callable $get) =>
                        $get('kategori') && $get('merk')
                            ? Inventaris::where('Kategori', $get('kategori'))
                                ->where('Merk', $get('merk'))
                                ->pluck('Seri', 'id')
                            : []
                    )
                    ->searchable()
                    ->required(),

                // PIC
                Forms\Components\Select::make('pic_id')
                    ->label('PIC')
                    ->relationship('pic', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_penggunaan'),
                Forms\Components\DatePicker::make('tanggal_perbaikan'),
                Forms\Components\TextInput::make('nomor_perbaikan'),
                Forms\Components\Checkbox::make('helpdesk'),
                Forms\Components\Checkbox::make('form'),
                Forms\Components\TextInput::make('hostname'),

                Forms\Components\Select::make('divisi')
                    ->options([
                        'MCP' => 'MCP',
                        'MKM' => 'MKM',
                        'PPG' => 'PPG',
                        'MKP' => 'MKP',
                        'PPM' => 'PPM',
                    ])
                    ->required(),
            ]),

            Forms\Components\Textarea::make('keterangan')
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('foto')
                ->image()
                ->disk('public')
                ->directory('uploads')
                ->visibility('public')
                ->columnSpanFull(),
        ]);
    }

    /* ================= TABLE ================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inventaris.Kategori')->label('Hardware')->searchable(),
                Tables\Columns\TextColumn::make('inventaris.Merk')->label('Merk')->searchable(),
                Tables\Columns\TextColumn::make('inventaris.Seri')->label('Seri')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_penggunaan')->date(),
                Tables\Columns\TextColumn::make('tanggal_perbaikan')->date(),
                Tables\Columns\IconColumn::make('helpdesk')->boolean(),
                Tables\Columns\IconColumn::make('form')->boolean(),
                Tables\Columns\TextColumn::make('nomor_perbaikan')->searchable(),
                Tables\Columns\TextColumn::make('hostname')->searchable(),
                Tables\Columns\TextColumn::make('divisi')->searchable(),
                Tables\Columns\TextColumn::make('pic.nama')->label('PIC')->default('-')->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => false,
                        'danger' => true,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Rejected' : 'Accept'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('divisi')
                    ->options([
                        'MCP' => 'MCP',
                        'MKM' => 'MKM',
                        'PPG' => 'PPG',
                        'MKP' => 'MKP',
                        'PPM' => 'PPM',
                    ]),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->action(fn () => Excel::download(new SowExport, 'data-sow.xlsx')),

                Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function () {
                        Sow::query()->update(['status' => false]);
                        Notification::make()
                            ->title('Semua data berhasil di Accept')
                            ->success()
                            ->send();
                    }),

                Action::make('arsip')
                    ->label('Arsip')
                    ->icon('heroicon-o-archive-box')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function () {
                        Sow::query()->update([
                            'is_archived' => true,
                            'arsip_at' => now(),
                        ]);
                        Notification::make()
                            ->title('Semua data berhasil di Arsipkan')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSOWS::route('/'),
            'create' => Pages\CreateSOW::route('/create'),
            'edit' => Pages\EditSOW::route('/{record}/edit'),
        ];
    }
}
