<?php

namespace App\Filament\Resources;

use App\Models\Assessment;
use App\Models\ClassRoom;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AssessmentResource\Pages;
use Illuminate\Support\Collection;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Penilaian';
    protected static ?string $pluralModelLabel = 'Penilaian';
    protected static ?string $navigationLabel = 'Manajemen Penilaian';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('guru')) {
            $query->where('teacher_id', auth()->id());
        }

        return $query;
    }

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
                                        ->orderBy('nama_lengkap')
                                        ->get()
                                        ->map(function ($student) {
                                            return [
                                                'student_id' => $student->id,
                                                'score' => null,
                                                'status' => 'hadir'
                                            ];
                                        })
                                        ->toArray();
                                    $set('student_scores', $students);
                                }
                            }),
                        Forms\Components\Hidden::make('teacher_id')
                            ->default(auth()->id()),
                    ])->columns(2),
                
                Forms\Components\Section::make('Detail Penilaian')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Jenis Penilaian')
                            ->options([
                                'sumatif' => 'Sumatif',
                                'non_sumatif' => 'Non-Sumatif',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('subject')
                            ->label('Mata Pelajaran')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('assessment_name')
                            ->label('Nama Penilaian')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Nilai Siswa')
                    ->schema([
                        Forms\Components\Repeater::make('student_scores')
                            ->label('Daftar Nilai Siswa')
                            ->schema([
                                Forms\Components\Hidden::make('student_id'),
                                Forms\Components\TextInput::make('nama_lengkap')
                                    ->label('Siswa')
                                    ->disabled()
                                    ->dehydrated(false),
                                Forms\Components\Select::make('status')
                                    ->label('Status')
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
                                            $set('score', null);
                                        }
                                    }),
                                Forms\Components\TextInput::make('score')
                                    ->label('Nilai')
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
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->maxLength(65535),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->maxLength(65535),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Guru')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Penilaian')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sumatif' => 'success',
                        'non_sumatif' => 'info',
                    }),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assessment_name')
                    ->label('Nama Penilaian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Penilaian')
                    ->options([
                        'sumatif' => 'Sumatif',
                        'non_sumatif' => 'Non-Sumatif',
                    ]),
                Tables\Filters\SelectFilter::make('class_room_id')
                    ->label('Kelas')
                    ->relationship('classRoom', 'name'),
                Tables\Filters\SelectFilter::make('teacher_id')
                    ->label('Guru')
                    ->relationship('teacher', 'name')
                    ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin'])),
            ])
            ->actions([
                Tables\Actions\Action::make('view_scores')
                    ->label('Lihat Nilai')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Assessment $record): string => route('filament.admin.resources.assessments.view-scores', ['record' => $record->id]))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('export')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn ($records) => redirect()->route('assessment.export', ['ids' => $records->pluck('id')->toArray()]))
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssessments::route('/'),
            'create' => Pages\CreateAssessment::route('/create'),
            'edit' => Pages\EditAssessment::route('/{record}/edit'),
            'view-scores' => Pages\ViewAssessmentScores::route('/{record}/scores'),
        ];
    }
} 