<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InternshipResource\Pages;
use App\Models\Internship;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InternshipResource extends Resource
{
    protected static ?string $model = Internship::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'PKL Management';
    protected static ?string $navigationLabel = 'Data PKL';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('siswa_id')
                    ->label('Siswa')
                    ->relationship(
                        name: 'siswa',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->whereHas(
                            'roles', 
                            fn($q) => $q->where('name', 'siswa')
                        )
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('office')
                    ->label('Office')
                    ->options([
                        'office1' => 'Office 1',
                        'office2' => 'Office 2',
                        // tambahkan opsi sesuai kebutuhan
                    ])
                    ->required(),
                Forms\Components\TextInput::make('jenis_perusahaan')
                    ->label('Jenis Perusahaan')
                    ->required(),
                Forms\Components\Textarea::make('deskripsi_perusahaan')
                    ->label('Deskripsi Perusahaan')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->required(),
                Forms\Components\TextInput::make('telepon')
                    ->label('Telepon')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->required(),
                Forms\Components\TextInput::make('nama_dudi')
                    ->label('Nama DUDI/Perusahaan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alamat_dudi')
                    ->label('Alamat DUDI')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'selesai' => 'Selesai',
                        'berhenti' => 'Berhenti',
                    ])
                    ->default('aktif')
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.kelas.name')
                    ->label('Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_dudi')
                    ->label('Nama DUDI')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'selesai' => 'info',
                        'berhenti' => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'selesai' => 'Selesai',
                        'berhenti' => 'Berhenti',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternships::route('/'),
            'create' => Pages\CreateInternship::route('/create'),
            'edit' => Pages\EditInternship::route('/{record}/edit'),
        ];
    }
} 