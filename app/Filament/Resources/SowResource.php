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

    /* ================= FORM ================= */
    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Grid::make(2)->schema([

                /* ================= CREATE & EDIT ================= */

                Forms\Components\Select::make('kategori')
                    ->label('Hardware')
                    ->options(
                        Inventaris::query()
                            ->select('Kategori')
                            ->distinct()
                            ->pluck('Kategori', 'Kategori')
                    )
                    ->default(fn ($record) => $record?->inventaris?->Kategori) 
                    ->live()
                    ->visible(fn ($livewire) =>
                        $livewire instanceof Pages\CreateSOW ||
                        $livewire instanceof Pages\EditSOW
                    )
                    ->afterStateUpdated(function (callable $set) {
                        $set('merk', null);
                        $set('inventaris_id', null);
                    })
                    ->required(),

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
                    ->default(fn ($record) => $record?->inventaris?->Merk)
                    ->live()
                    ->dehydrated(false)
                    ->visible(fn ($livewire) =>
                        $livewire instanceof Pages\CreateSOW ||
                        $livewire instanceof Pages\EditSOW
                    )
                    ->afterStateUpdated(fn (callable $set) =>
                        $set('inventaris_id', null)
                    )
                    ->required(),

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
                    ->required()
                    ->visible(fn ($livewire) =>
                        $livewire instanceof Pages\CreateSOW ||
                        $livewire instanceof Pages\EditSOW
                    ),

                /* ================= VIEW MODE ================= */

                Forms\Components\TextInput::make('hardware_view')
                    ->label('Hardware')
                    ->default(fn ($record) => $record?->inventaris?->Kategori)
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($livewire) =>
                        $livewire instanceof Pages\ViewSOW
                    ),

                Forms\Components\TextInput::make('merk_view')
                    ->label('Merk')
                    ->default(fn ($record) => $record?->inventaris?->Merk)
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($livewire) =>
                        $livewire instanceof Pages\ViewSOW
                    ),

                Forms\Components\TextInput::make('seri_view')
                    ->label('Seri')
                    ->default(fn ($record) => $record?->inventaris?->Seri)
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($livewire) =>
                        $livewire instanceof Pages\ViewSOW
                    ),

                /* ================= FIELD LAIN ================= */

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

            Forms\Components\Textarea::make('keterangan')->columnSpanFull(),

            Forms\Components\FileUpload::make('foto')
            ->label('Foto')
            ->image() // preview gambar
            ->disk('public') // simpan di disk public
            ->directory('uploads') // folder penyimpanan
            ->visibility('public') // agar bisa diakses publik
            ->downloadable() // aktifkan tombol download
            ->columnSpanFull(),

        ]);
    }

    /* ================= TABLE ================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inventaris.Kategori')->label('Hardware'),
                Tables\Columns\TextColumn::make('inventaris.Merk')->label('Merk'),
                Tables\Columns\TextColumn::make('inventaris.Seri')->label('Seri'),
                Tables\Columns\TextColumn::make('tanggal_penggunaan')->date(),
                Tables\Columns\TextColumn::make('tanggal_perbaikan')->date(),
                Tables\Columns\TextColumn::make('nomor_perbaikan'),
                Tables\Columns\TextColumn::make('hostname'),
                Tables\Columns\TextColumn::make('divisi'),
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
        ->disabled(fn () => Sow::whereNull('status')->orWhere('status', true)->exists())
        ->action(function () {

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "data-sow-{$tanggal}.xlsx";

                    return Excel::download(
                        new SowExport(),
                        $namaFile
                    );
                }),

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
        ->disabled(fn () => Sow::whereNull('status')->orWhere('status', true)->exists())
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
                Tables\Actions\ActionGroup::make([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                ])
                ->label('More') 
                ->icon('heroicon-m-ellipsis-vertical') 
                ->color('primary')
            ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSOWS::route('/'),
            'create' => Pages\CreateSOW::route('/create'),
            'view' => Pages\ViewSOW::route('/{record}'),
            'edit' => Pages\EditSOW::route('/{record}/edit'),
        ];
    }
}
