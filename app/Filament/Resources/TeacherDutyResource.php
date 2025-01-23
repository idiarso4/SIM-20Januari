<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherDutyResource\Pages;
use App\Models\TeacherDuty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeacherDutyResource extends Resource
{
    protected static ?string $model = TeacherDuty::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    
    protected static ?string $navigationLabel = 'Jadwal Guru Piket';
    
    protected static ?string $modelLabel = 'Jadwal Piket';
    
    protected static ?string $pluralModelLabel = 'Jadwal Piket';
    
    protected static ?string $navigationGroup = 'Piket & Perizinan';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->label('Guru')
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\Select::make('day')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                    ])
                    ->required()
                    ->label('Hari'),
                    
                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->label('Waktu Mulai'),
                    
                Forms\Components\TimePicker::make('end_time')
                    ->required()
                    ->label('Waktu Selesai'),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Guru')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('day')
                    ->label('Hari')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('day')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                    ])
                    ->label('Hari'),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('day');
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
            'index' => Pages\ListTeacherDuties::route('/'),
            'create' => Pages\CreateTeacherDuty::route('/create'),
            'edit' => Pages\EditTeacherDuty::route('/{record}/edit'),
        ];
    }
} 