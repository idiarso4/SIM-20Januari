<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtracurricularResource\Pages;
use App\Models\Extracurricular;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ExtracurricularResource\RelationManagers\StudentsRelationManager;
use Filament\Tables\Columns\TextColumn;

class ExtracurricularResource extends Resource
{
    protected static ?string $model = Extracurricular::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Ekstrakurikuler';
    protected static ?string $navigationGroup = 'Master Data';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('guru')) {
            $query->whereHas('guru', function($query) {
                $query->where('users.id', auth()->id());
            });
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('guru')
                    ->relationship(
                        name: 'guru',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->whereHas('roles', fn($q) => $q->where('name', 'guru'))
                    )
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('Pembina')
                    ->required(),
                Forms\Components\Select::make('hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                        'Minggu' => 'Minggu',
                    ])
                    ->required(),
                Forms\Components\TimePicker::make('jam_mulai')
                    ->required(),
                Forms\Components\TimePicker::make('jam_selesai')
                    ->required(),
                Forms\Components\TextInput::make('tempat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('status')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isGuru = auth()->user()->hasRole('guru');

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Pembina')
                    ->listWithLineBreaks()
                    ->searchable(),
                Tables\Columns\TextColumn::make('hari')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_selesai')
                    ->time()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guru')
                    ->label('Pembina')
                    ->relationship('guru', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(!$isGuru),
                Tables\Filters\SelectFilter::make('hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non-aktif',
                    ])
                    ->visible(!$isGuru),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->visible(!$isGuru),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->visible(!$isGuru),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExtracurriculars::route('/'),
            'create' => Pages\CreateExtracurricular::route('/create'),
            'edit' => Pages\EditExtracurricular::route('/{record}/edit'),
        ];
    }    
} 