<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MutationResource\Pages;
use App\Models\Mutation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class MutationResource extends Resource
{
    protected static ?string $model = Mutation::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    
    protected static ?string $navigationLabel = 'Mutasi Siswa';
    
    protected static ?string $navigationGroup = 'Master Data';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'name')
                    ->label('Nama Siswa')
                    ->required()
                    ->preload()
                    ->searchable(),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                Forms\Components\Select::make('jenis')
                    ->options([
                        'masuk' => 'Mutasi Masuk',
                        'keluar' => 'Mutasi Keluar',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('alasan')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sekolah_asal')
                    ->label('Sekolah Asal')
                    ->required(fn ($get) => $get('jenis') === 'masuk')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sekolah_tujuan')
                    ->label('Sekolah Tujuan')
                    ->required(fn ($get) => $get('jenis') === 'keluar')
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'masuk' => 'success',
                        'keluar' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('sekolah_asal')
                    ->label('Sekolah Asal')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sekolah_tujuan')
                    ->label('Sekolah Tujuan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')
                    ->options([
                        'masuk' => 'Mutasi Masuk',
                        'keluar' => 'Mutasi Keluar',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved']);
                        
                        Notification::make()
                            ->success()
                            ->title('Mutasi disetujui')
                            ->body('Data mutasi siswa berhasil disetujui.')
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);
                        
                        Notification::make()
                            ->success()
                            ->title('Mutasi ditolak')
                            ->body('Data mutasi siswa telah ditolak.')
                            ->send();
                    }),
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMutations::route('/'),
            'create' => Pages\CreateMutation::route('/create'),
            'edit' => Pages\EditMutation::route('/{record}/edit'),
        ];
    }
} 