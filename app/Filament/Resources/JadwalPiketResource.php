<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalPiketResource\Pages;
use App\Models\JadwalPiket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JadwalPiketResource extends Resource
{
    protected static ?string $model = JadwalPiket::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Piket & Perizinan';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return 'Jadwal Guru Piket';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('guru_id')
                    ->label('Guru Piket')
                    ->relationship('guru', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                    ]),
                Forms\Components\Select::make('hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                    ])
                    ->required(),
                Forms\Components\TimePicker::make('jam_mulai')
                    ->required()
                    ->seconds(false),
                Forms\Components\TimePicker::make('jam_selesai')
                    ->required()
                    ->seconds(false),
                Forms\Components\TextInput::make('semester')
                    ->required(),
                Forms\Components\TextInput::make('tahun_ajaran')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Guru Piket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hari')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_selesai')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                    ]),
                Tables\Filters\SelectFilter::make('guru')
                    ->relationship('guru', 'name')
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
            'index' => Pages\ListJadwalPikets::route('/'),
            'create' => Pages\CreateJadwalPiket::route('/create'),
            'edit' => Pages\EditJadwalPiket::route('/{record}/edit'),
        ];
    }
} 