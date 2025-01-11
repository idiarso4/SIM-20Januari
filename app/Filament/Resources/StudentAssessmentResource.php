<?php

namespace App\Filament\Resources;

use App\Models\StudentAssessment;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentAssessmentResource\Pages;

class StudentAssessmentResource extends Resource
{
    protected static ?string $model = StudentAssessment::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Penilaian';
    protected static ?string $pluralModelLabel = 'Penilaian';
    protected static ?string $navigationLabel = 'Manajemen Penilaian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kelas')
                    ->schema([
                        Forms\Components\Select::make('class_room_id')
                            ->label('Kelas')
                            ->relationship('classRoom', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $students = Student::where('class_room_id', $state)
                                        ->orderBy('name')
                                        ->get()
                                        ->map(function ($student) {
                                            return [
                                                'student_id' => $student->id,
                                                'nilai' => null,
                                                'status' => 'hadir'
                                            ];
                                        })
                                        ->toArray();
                                    $set('student_scores', $students);
                                }
                            }),
                        Forms\Components\Hidden::make('guru_id')
                            ->default(auth()->id()),
                    ])->columns(2),
                
                Forms\Components\Section::make('Detail Penilaian')
                    ->schema([
                        Forms\Components\Select::make('jenis_penilaian')
                            ->options([
                                'teori' => 'Teori',
                                'praktik' => 'Praktik',
                                'tugas' => 'Tugas',
                                'uh' => 'Ulangan Harian',
                                'uts' => 'UTS',
                                'uas' => 'UAS',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('mata_pelajaran')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kompetensi_dasar')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tanggal')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Nilai Siswa')
                    ->schema([
                        Forms\Components\Repeater::make('student_scores')
                            ->label('Daftar Nilai Siswa')
                            ->schema([
                                Forms\Components\Hidden::make('student_id'),
                                Forms\Components\TextInput::make('name')
                                    ->label('Siswa')
                                    ->disabled()
                                    ->dehydrated(false),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'hadir' => 'Hadir',
                                        'sakit' => 'Sakit',
                                        'izin' => 'Izin',
                                        'alpha' => 'Alpha',
                                    ])
                                    ->default('hadir')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state !== 'hadir') {
                                            $set('nilai', null);
                                        }
                                    }),
                                Forms\Components\TextInput::make('nilai')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->hidden(fn (Forms\Get $get): bool => $get('status') !== 'hadir'),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->addable(false)
                            ->deletable(false),
                    ]),

                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('deskripsi')
                            ->maxLength(65535),
                        Forms\Components\Textarea::make('catatan_guru')
                            ->maxLength(65535),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('guru.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mata_pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_penilaian')
                    ->badge()
                    ->colors([
                        'primary' => 'teori',
                        'success' => 'praktik',
                        'info' => 'tugas',
                        'warning' => 'uh',
                        'danger' => 'uts',
                        'gray' => 'uas',
                    ]),
                Tables\Columns\TextColumn::make('attempt')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nilai')
                    ->numeric(2)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (StudentAssessment $record): bool => 
                        auth()->user()->hasRole('super_admin') || 
                        auth()->id() === $record->guru_id
                    ),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (StudentAssessment $record): bool => 
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
            'index' => Pages\ListStudentAssessments::route('/'),
            'create' => Pages\CreateStudentAssessment::route('/create'),
            'edit' => Pages\EditStudentAssessment::route('/{record}/edit'),
            'view-scores' => Pages\ViewStudentAssessmentScores::route('/{record}/scores'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('guru')) {
            $query->where('guru_id', auth()->id());
        } elseif (auth()->user()->hasRole('siswa')) {
            $query->where('student_id', auth()->id());
        }

        return $query;
    }
} 