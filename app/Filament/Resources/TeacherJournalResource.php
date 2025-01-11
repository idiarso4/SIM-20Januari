<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherJournalResource\Pages;
use App\Models\TeacherJournal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeacherJournalResource extends Resource
{
    protected static ?string $model = TeacherJournal::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    
    protected static ?string $modelLabel = 'Agenda Harian Guru';
    
    protected static ?string $pluralModelLabel = 'Agenda Harian Guru';
    
    protected static ?string $navigationLabel = 'Agenda Harian Guru';
    
    protected static ?string $navigationGroup = 'Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('guru_id')
                    ->default(fn () => auth()->id())
                    ->disabled(fn () => !auth()->user()->hasRole('super_admin'))
                    ->required(),
                Forms\Components\Select::make('class_room_id')
                    ->relationship('classRoom', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('mata_pelajaran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('materi')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                Forms\Components\TextInput::make('jam_ke_mulai')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(12),
                Forms\Components\TextInput::make('jam_ke_selesai')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(12),
                Forms\Components\TimePicker::make('jam_mulai')
                    ->required(),
                Forms\Components\TimePicker::make('jam_selesai')
                    ->required(),
                Forms\Components\Textarea::make('media_dan_alat')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('important_notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('kegiatan')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('hasil')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('hambatan')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('pemecahan_masalah')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('draft')
                    ->required(),
                Forms\Components\Textarea::make('catatan_waka')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->visible(fn () => auth()->user()->hasRole('waka')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guru.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mata_pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('materi')
                    ->limit(50),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_ke_mulai'),
                Tables\Columns\TextColumn::make('jam_ke_selesai'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (TeacherJournal $record): bool => 
                        auth()->user()->hasRole('super_admin') || 
                        auth()->id() === $record->guru_id
                    ),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (TeacherJournal $record): bool => 
                        auth()->user()->hasRole('super_admin') || 
                        auth()->id() === $record->guru_id
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeacherJournals::route('/'),
            'create' => Pages\CreateTeacherJournal::route('/create'),
            'edit' => Pages\EditTeacherJournal::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('guru')) {
            $query->where('guru_id', auth()->id());
        }

        return $query;
    }
} 