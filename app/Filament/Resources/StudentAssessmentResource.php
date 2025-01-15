<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentAssessmentResource\Pages;
use App\Models\StudentAssessment;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentAssessmentResource extends Resource
{
    protected static ?string $model = StudentAssessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Penilaian Siswa';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'student-assessments';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('guru_id', auth()->id())->count();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('guru')) {
            $query->where('guru_id', auth()->id());
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('guru_id')
                    ->default(auth()->id())
                    ->dehydrated(true)
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal'),
                Forms\Components\Select::make('class_room_id')
                    ->relationship('classRoom', 'name')
                    ->label('Kelas')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, $context, $record = null) {
                        if ($state) {
                            $students = Student::where('class_room_id', $state)
                                ->orderBy('nama_lengkap')
                                ->get();

                            $details = [];
                            
                            // Jika dalam mode edit dan ada record
                            if ($context === 'edit' && $record) {
                                $existingDetails = $record->details()
                                    ->get()
                                    ->keyBy('student_id');

                                foreach ($students as $student) {
                                    $detail = $existingDetails->get($student->id);
                                    $details[] = [
                                        'student_id' => $student->id,
                                        'nama_siswa' => $student->nama_lengkap,
                                        'nis' => $student->nis,
                                        'nilai' => $detail ? $detail->nilai : 0,
                                        'keterangan' => $detail ? $detail->keterangan : null,
                                    ];
                                }
                            } else {
                                // Mode create atau kelas baru dipilih
                                foreach ($students as $student) {
                                    $details[] = [
                                        'student_id' => $student->id,
                                        'nama_siswa' => $student->nama_lengkap,
                                        'nis' => $student->nis,
                                        'nilai' => 0,
                                        'keterangan' => null,
                                    ];
                                }
                            }
                            
                            $set('details', $details);
                        }
                    }),
                Forms\Components\TextInput::make('mata_pelajaran')
                    ->required()
                    ->label('Mata Pelajaran'),
                Forms\Components\Select::make('jenis')
                    ->options(StudentAssessment::JENIS_OPTIONS)
                    ->required()
                    ->label('Jenis Penilaian'),
                Forms\Components\Select::make('kategori')
                    ->options(StudentAssessment::KATEGORI_OPTIONS)
                    ->required()
                    ->label('Kategori'),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->nullable(),
                Forms\Components\Repeater::make('details')
                    ->schema([
                        Forms\Components\Hidden::make('student_id'),
                        Forms\Components\TextInput::make('nama_siswa')
                            ->label('Nama Siswa')
                            ->disabled(),
                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')
                            ->disabled(),
                        Forms\Components\TextInput::make('nilai')
                            ->label('Nilai')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0),
                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->nullable(),
                    ])
                    ->columns(5)
                    ->label('Daftar Siswa')
                    ->defaultItems(0)
                    ->disableItemCreation()
                    ->disableItemDeletion(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Guru')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListStudentAssessments::route('/'),
            'create' => Pages\CreateStudentAssessment::route('/create'),
            'edit' => Pages\EditStudentAssessment::route('/{record}/edit'),
        ];
    }
}
