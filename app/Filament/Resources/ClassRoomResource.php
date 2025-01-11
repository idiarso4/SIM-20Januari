<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassRoomResource\Pages;
use App\Models\ClassRoom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Department;
use App\Models\User;
use App\Models\SchoolYear;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ViewAction;
use Maatwebsite\Excel\Facades\Excel;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use App\Filament\Resources\ClassRoomResource\RelationManagers;
use App\Imports\ClassRoomsImport;

class ClassRoomResource extends Resource
{
    protected static ?string $model = ClassRoom::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $modelLabel = 'Kelas';
    
    protected static ?string $pluralModelLabel = 'Kelas';
    
    protected static ?string $navigationLabel = 'Kelas';
    
    protected static ?string $navigationGroup = 'Master Data';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kelas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('level')
                    ->label('Tingkat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('department_id')
                    ->label('Jurusan')
                    ->relationship('department', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('school_year_id')
                    ->label('Tahun Pelajaran')
                    ->relationship('schoolYear', 'tahun')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('homeroom_teacher_id')
                    ->label('Wali Kelas')
                    ->relationship('homeroomTeacher', 'name', fn ($query) => 
                        $query->role('guru')->orderBy('name')
                    )
                    ->searchable()
                    ->preload(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('Tingkat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Jurusan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('schoolYear.tahun')
                    ->label('Tahun Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('homeroomTeacher.name')
                    ->label('Wali Kelas')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('students_count')
                    ->label('Jumlah Siswa')
                    ->counts('students'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->label('Tingkat')
                    ->options([
                        'X' => 'X',
                        'XI' => 'XI',
                        'XII' => 'XII',
                        'XIII' => 'XIII',
                    ]),
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Jurusan')
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('school_year_id')
                    ->label('Tahun Pelajaran')
                    ->relationship('schoolYear', 'tahun'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->before(function (ClassRoom $record) {
                        if ($record->students()->count() > 0) {
                            Notification::make()
                                ->danger()
                                ->title('Kelas tidak dapat dihapus')
                                ->body('Kelas masih memiliki siswa yang terdaftar.')
                                ->send();
                            
                            return false;
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\StudentsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassRooms::route('/'),
            'create' => Pages\CreateClassRoom::route('/create'),
            'view' => Pages\ViewClassRoom::route('/{record}'),
            'edit' => Pages\EditClassRoom::route('/{record}/edit'),
        ];
    }
} 