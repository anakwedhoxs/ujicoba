<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SowCpuResource\Pages;
use App\Filament\Resources\SowCpuResource\RelationManagers;
use App\Models\SowCpu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SowCpuExport;
use Filament\Tables\Actions\Action;


class SowCpuResource extends Resource
{
    protected static ?string $model = SowCpu::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Data SOW CPU';
    protected static ?string $navigationGroup = 'SOW';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('prosesor_id')
                        ->label('PROCESSOR')
                    ->options(
                        \App\Models\Inventaris::query()
                            ->where('kategori', 'PROCESSOR')
                            ->get()
                            ->mapWithKeys(function ($item) {
                                return [$item->id => "{$item->Merk} {$item->Seri}"];
                            })
                            ->toArray()
                    )
                    ->searchable()
                    ->required(),


                Forms\Components\Select::make('ram_id')
                    ->label('RAM')
                    ->options(
                        \App\Models\Inventaris::query()
                            ->where('kategori', 'RAM')
                            ->get()
                            ->mapWithKeys(function ($item) {
                                return [$item->id => "{$item->Merk} {$item->Seri}"];
                            })
                            ->toArray()
                    )
                    ->searchable()
                    ->required(),


                Forms\Components\Select::make('motherboard_id')
                    ->label('MOTHERBOARD')
                    ->options(
                        \App\Models\Inventaris::query()
                            ->where('kategori', 'MOTHERBOARD')
                            ->get()
                            ->mapWithKeys(function ($item) {
                                return [$item->id => "{$item->Merk} {$item->Seri}"];
                            })
                            ->toArray()
                    )
                    ->searchable()
                    ->required(),


                    Forms\Components\Select::make('pic_id')
                    ->label('PIC')
                    ->relationship('pic', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),


                Forms\Components\DatePicker::make('tanggal_penggunaan'),
                Forms\Components\DatePicker::make('tanggal_perbaikan'),
                Forms\Components\Grid::make(2)
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\Checkbox::make('form'),
                        Forms\Components\Checkbox::make('helpdesk'),
                    ]),
                Forms\Components\TextInput::make('nomor_perbaikan'),
                Forms\Components\Select::make('hostname_id')
                    ->label('Hostname')
                    ->relationship('hostname', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),


                Forms\Components\Select::make('divisi')
                    ->options([
                        'MCP' => 'MCP',
                        'MKM' => 'MKM',
                        'PPG' => 'PPG',
                        'MKP' => 'MKP',
                        'PPM' => 'PPM',
                    ])
                    ->required(),
           
            Forms\Components\Textarea::make('keterangan')->columnSpanFull(),


            Forms\Components\FileUpload::make('foto')
            ->label('Foto')
            ->image() // preview gambar
            ->disk('public') // simpan di disk public
            ->directory('uploads') // folder penyimpanan
            ->visibility('public') // agar bisa diakses publik
            ->downloadable() // aktifkan tombol download
            ->columnSpanFull(),


             Forms\Components\Toggle::make('status')
                ->label('Rejected')
                ->helperText('ON = Rejected | OFF = Accept')
                ->onColor('danger')
                ->offColor('success')
                ->default(false)
                ->visible(fn ($record) => auth()->user()?->hasRole('super_admin') && $record !== null),


       


                   
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('prosesor.Merk')
                    ->label('Prosesor'),


                Tables\Columns\TextColumn::make('ram.Merk')
                    ->label('RAM'),


                Tables\Columns\TextColumn::make('motherboard.Merk')
                    ->label('Motherboard'),
                    Tables\Columns\TextColumn::make('tanggal_penggunaan')->date(),
                Tables\Columns\TextColumn::make('tanggal_perbaikan')->date(),
                Tables\Columns\TextColumn::make('nomor_perbaikan'),
                Tables\Columns\TextColumn::make('hostname.nama')
                    ->label('Hostname')
                    ->default('-')
                    ->searchable(),
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
                ->disabled(fn () => SowCpu::whereNull('status')->orWhere('status', true)->exists())
                ->color('primary')
                //->disabled(fn () => Sow::whereNull('status')->orWhere('status', true)->exists())
                ->action(function () {


                            $tanggal = now()->format('d-m-Y');
                            $namaFile = "data-sow-CPU-{$tanggal}.xlsx";


                            return Excel::download(
                                new SowCpuExport(),
                                $namaFile
                            );
                        }),
                         Action::make('accept')
                ->label('Accept')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    SowPc::query()->update(['status' => false]);
                    Notification::make()
                        ->title('Semua data berhasil di Accept')
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
            'index' => Pages\ListSowCpus::route('/'),
            'create' => Pages\CreateSowCpu::route('/create'),
            'edit' => Pages\EditSowCpu::route('/{record}/edit'),
        ];
    }
}
