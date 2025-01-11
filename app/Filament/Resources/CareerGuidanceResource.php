<?php

namespace App\Filament\Resources;

use App\Models\CareerGuidance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CareerGuidanceResource\Pages;

class CareerGuidanceResource extends Resource
{
    protected static ?string $model = CareerGuidance::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Bimbingan Konseling';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Peminatan & Bimbingan Karir';
    protected static ?string $pluralModelLabel = 'Peminatan & Bimbingan Karir';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'name')
                    ->label('Siswa')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('class_room_id')
                    ->relationship('classRoom', 'name')
                    ->label('Kelas')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Jenis Bimbingan')
                    ->options([
                        'peminatan' => 'Peminatan',
                        'karir' => 'Bimbingan Karir',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('interest_talents')
                    ->label('Minat & Bakat')
                    ->required(),
                Forms\Components\Textarea::make('career_plans')
                    ->label('Rencana Karir')
                    ->required(),
                Forms\Components\Textarea::make('guidance_results')
                    ->label('Hasil Bimbingan')
                    ->required(),
                Forms\Components\Textarea::make('recommendations')
                    ->label('Rekomendasi')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan Tambahan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Bimbingan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'peminatan' => 'success',
                        'karir' => 'info',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCareerGuidances::route('/'),
            'create' => Pages\CreateCareerGuidance::route('/create'),
            'edit' => Pages\EditCareerGuidance::route('/{record}/edit'),
        ];
    }
} 