<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PiketResource\Pages;
use Filament\Resources\Resource;
use App\Models\Piket;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;

class PiketResource extends Resource
{
    protected static ?string $model = Piket::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Piket & Perizinan';
    protected static ?int $navigationSort = 3;
    
    public static function getNavigationLabel(): string
    {
        return 'Administrasi Guru Piket';
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                Forms\Components\Select::make('guru_id')
                    ->relationship('guru', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('pending')
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
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Nama Guru')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
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
            'index' => Pages\ListPiket::route('/'),
            'create' => Pages\CreatePiket::route('/create'),
            'edit' => Pages\EditPiket::route('/{record}/edit'),
            'approval' => Pages\ApprovalPiket::route('/approval'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }
} 