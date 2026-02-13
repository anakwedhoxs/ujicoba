<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SOWResource\Pages;
use App\Models\Sow;
use App\Models\Inventaris;
use App\Models\SowArsip;
use App\Models\SowArsipItem;
use App\Exports\SowExport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class SOWResource extends Resource
{
    protected static ?string $model = Sow::class;
    protected static ?string $navigationIcon = 'heroicon-s-queue-list';
    protected static ?string $navigationLabel = 'Data SOW';
    protected static ?string $navigationGroup = 'SOW';
    protected static ?int $navigationSort = 1 ;

    /* ================= FORM ================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([

                /* ===== HIDDEN HYDRATOR ===== */
                Forms\Components\Hidden::make('hydrator')
                    ->afterStateHydrated(function (callable $set, $record) {
                        if (! $record) return;
                        $inv = Inventaris::find($record->inventaris_id);
                        if ($inv) {
                            $set('kategori', $inv->Kategori);
                            $set('merk', $inv->Merk);
                        }
                    }),

                /* ===== KATEGORI ===== */
                Forms\Components\Select::make('kategori')
                ->label('Hardware')
                ->options(
                    Inventaris::query()
                        ->select('Kategori')
                        ->distinct()
                        ->pluck('Kategori', 'Kategori')
                )
                ->live()
                ->afterStateUpdated(function (callable $set, $state, $livewire) {
                    if ($livewire instanceof Pages\EditSOW) return;
                    $set('merk', null);
                    $set('inventaris_id', null);
                })
                ->required(),


                /* ===== MERK ===== */
                Forms\Components\Select::make('merk')
                    ->label('Merk')
                    ->options(fn (callable $get) =>
                        $get('kategori')
                            ? Inventaris::where('Kategori', $get('kategori'))->select('Merk')->distinct()->pluck('Merk', 'Merk')
                            : []
                    )
                    ->default(fn ($record) => $record?->inventaris?->Merk)
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(function (callable $set, $state, $livewire) {
                        if ($livewire instanceof Pages\EditSOW) return;
                        $set('inventaris_id', null);
                    })
                    ->required(),

                /* ===== SERI ===== */
                Forms\Components\Select::make('inventaris_id')
                    ->label('Seri')
                    ->options(fn (callable $get) =>
                        $get('kategori') && $get('merk')
                            ? Inventaris::where('Kategori', $get('kategori'))
                                ->where('Merk', $get('merk'))
                                ->pluck('Seri', 'id')
                            : []
                    )
                    ->default(fn ($record) => $record?->inventaris_id)
                    ->searchable()
                    ->required(),

                /* ===== TANGGAL & INFO ===== */
                Forms\Components\DatePicker::make('tanggal_penggunaan')
                    ->displayFormat('d F Y')
                    ->locale('fr'),
                Forms\Components\DatePicker::make('tanggal_perbaikan')
                    ->displayFormat('d F Y')
                    ->locale('fr'),
                Forms\Components\TextInput::make('nomor_perbaikan'),
                Forms\Components\Checkbox::make('helpdesk'),
                Forms\Components\Checkbox::make('form'),
                Forms\Components\TextInput::make('hostname'),

                /* ===== DIVISI ===== */
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

            /* ===== KETERANGAN ===== */
            Forms\Components\Textarea::make('keterangan')->columnSpanFull(),

            /* ===== PIC ===== */
            Forms\Components\TextInput::make('pic') 
            ->label('PIC') 
            ->placeholder('Nama penanggung jawab') 
            ->maxLength(255) 
            ->required(),

            /* ===== FOTO ===== */
            Forms\Components\FileUpload::make('foto')
                ->image()
                ->disk('public')
                ->directory('uploads')
                ->visibility('public')
                ->enableDownload()
                ->enableOpen()
                ->columnSpanFull(),

            /* ===== STATUS ===== */
            Forms\Components\Toggle::make('status')
                ->label('Rejected')
                ->helperText('ON = Rejected | OFF = Accept')
                ->onColor('danger')
                ->offColor('success')
                ->default(false)
                ->visible(fn ($record) => auth()->user()?->hasRole('super_admin') && $record !== null),
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
                Tables\Columns\TextColumn::make('tanggal_penggunaan') ->label('Tanggal Penggunaan') ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('tanggal_perbaikan') ->label('Tanggal Perbaikan') ->date('d/m/Y'),
                Tables\Columns\IconColumn::make('helpdesk')->boolean(),
                Tables\Columns\IconColumn::make('form')->boolean(),
                Tables\Columns\TextColumn::make('nomor_perbaikan')->searchable(),
                Tables\Columns\TextColumn::make('hostname')->searchable(),
                Tables\Columns\TextColumn::make('divisi')->searchable(),
                Tables\Columns\TextColumn::make('pic')->label('PIC')->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => true,
                        'success' => false,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Rejected' : 'Accept'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('divisi')->options([
                    'MCP' => 'MCP',
                    'MKM' => 'MKM',
                    'PPG' => 'PPG',
                    'MKP' => 'MKP',
                    'PPM' => 'PPM',
                ]),
            ])
            ->headerActions([

                /* ===== EXPORT ===== */
                
                Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->disabled(fn () => !Sow::where('status', false)->exists())
                ->action(function (Tables\Table $table) {

                    $filters = $table->getFiltersForm()->getState();

                    $divisi = $filters['divisi'] ?? null;

                    if (is_array($divisi)) {
                        $divisi = reset($divisi);
                    }

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "data-sow-{$tanggal}.xlsx";

                    return Excel::download(
                        new SowExport($divisi),
                        $namaFile
                    );
                }),


                            /* ===== ACCEPT ALL ===== */
                Action::make('accept_all')
                    ->label('Accept All')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn () => Sow::query()->update(['status' => false]))
                    ->visible(fn () => auth()->user()?->hasRole('super_admin')),

                /* ===== ARSIPKAN ===== */
                Action::make('arsipkan')
                    ->label('Arsipkan')
                    ->icon('heroicon-o-archive-box')
                    ->disabled(fn () => !Sow::where('status', false)->exists())
                    ->color('warning')
                    ->form([
                        Forms\Components\TextInput::make('judul')->label('Judul Arsip') ->placeholder('ex:SOWx-blnthn') ->required(),
                    ])
                    ->requiresConfirmation()
                    ->action(function (array $data) {
                        if (Sow::count() === 0) {
                            Notification::make()->title('Data SOW kosong')->danger()->send();
                            return;
                        }

                        $arsip = SowArsip::create(['judul' => $data['judul']]);

                        Sow::chunk(50, function ($sows) use ($arsip) {
                            foreach ($sows as $sow) {
                                SowArsipItem::create([
                                    'sow_arsip_id' => $arsip->id,
                                    'inventaris_id' => $sow->inventaris_id,
                                    'tanggal_penggunaan' => $sow->tanggal_penggunaan,
                                    'tanggal_perbaikan' => $sow->tanggal_perbaikan,
                                    'helpdesk' => $sow->helpdesk,
                                    'form' => $sow->form,
                                    'nomor_perbaikan' => $sow->nomor_perbaikan,
                                    'hostname' => $sow->hostname,
                                    'divisi' => $sow->divisi,
                                    'keterangan' => $sow->keterangan,
                                    'pic' => $sow->pic,
                                    'foto' => $sow->foto,
                                ]);
                            }
                        });

                        Sow::truncate();

                        Notification::make()->title('Data berhasil diarsipkan')->success()->send();
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'edit' => Pages\EditSOW::route('/{record}/edit'),
        ];
    }
}
