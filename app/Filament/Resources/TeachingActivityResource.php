<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachingActivityResource\Pages;
use App\Models\TeachingActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TeachingActivityResource extends Resource
{
    protected static ?string $model = TeachingActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $modelLabel = 'Kegiatan Pembelajaran';
    
    protected static ?string $pluralModelLabel = 'Kegiatan Pembelajaran';
    
    protected static ?string $navigationLabel = 'Kegiatan Pembelajaran';
    
    protected static ?string $navigationGroup = 'Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal'),
                Forms\Components\Select::make('kelas_id')
                    ->relationship('kelas', 'name')
                    ->label('Kelas')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, $record) {
                        if ($state) {
                            static::loadStudentAttendances($state, $set, $record);
                        }
                    })
                    ->afterStateHydrated(function ($state, Forms\Set $set, $record) {
                        if ($state) {
                            static::loadStudentAttendances($state, $set, $record);
                        }
                    }),
                Forms\Components\TextInput::make('mata_pelajaran')
                    ->required()
                    ->label('Mata Pelajaran'),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('jam_ke_mulai')
                            ->required()
                            ->label('Jam Ke')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(12)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $set('jam_ke', $state);
                            }),
                        Forms\Components\TextInput::make('jam_ke_selesai')
                            ->required()
                            ->label('Sampai Jam Ke')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(12),
                    ]),
                Forms\Components\Select::make('guru_id')
                    ->relationship('guru', 'name')
                    ->label('Guru')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->id())
                    ->disabled(fn () => !auth()->user()->hasRole('admin')),
                Forms\Components\Textarea::make('materi')
                    ->required()
                    ->label('Materi')
                    ->maxLength(65535),
                Forms\Components\Textarea::make('media_dan_alat')
                    ->required()
                    ->label('Media dan Alat yang Digunakan')
                    ->maxLength(65535),
                Forms\Components\Section::make('Kehadiran Siswa')
                    ->schema([
                        Forms\Components\Repeater::make('attendances')
                            ->schema([
                                Forms\Components\Hidden::make('student_id'),
                                Forms\Components\TextInput::make('nis')
                                    ->label('NIS')
                                    ->disabled(),
                                Forms\Components\TextInput::make('nama_siswa')
                                    ->label('Nama Siswa')
                                    ->disabled(),
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'Hadir' => 'Hadir',
                                        'Sakit' => 'Sakit',
                                        'Izin' => 'Izin',
                                        'Alpha' => 'Alpha',
                                        'Dispensasi' => 'Dispensasi',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('keterangan')
                                    ->label('Keterangan')
                                    ->placeholder('Keterangan (opsional)'),
                            ])
                            ->columns(4)
                            ->defaultItems(0)
                            ->disableItemCreation()
                            ->disableItemDeletion()
                            ->disableItemMovement(),
                    ]),
                Forms\Components\Textarea::make('important_notes')
                    ->label('Catatan Kejadian Penting')
                    ->placeholder('Masukkan catatan kejadian penting selama pembelajaran (opsional)')
                    ->maxLength(65535),
            ]);
    }

    private static function loadStudentAttendances($classId, Forms\Set $set, $record = null)
    {
        // Debug: Cek nilai classId
        \Log::info('ClassId: ' . $classId);
        
        $students = \App\Models\Student::where('class_room_id', $classId)
            ->orderBy('nama_lengkap')
            ->get();
        
        // Debug: Cek jumlah siswa yang ditemukan
        \Log::info('Jumlah siswa: ' . $students->count());
        \Log::info('Data siswa: ' . $students->toJson());
        
        // Jika ini mode edit, ambil data kehadiran yang ada
        $existingAttendances = $record 
            ? collect($record->attendances)->keyBy('student_id') 
            : collect();
        
        $attendances = [];
        foreach ($students as $student) {
            // Debug: Cek data setiap siswa
            \Log::info('Processing student: ' . $student->toJson());
            
            if ($existingAttendances->has($student->id)) {
                $attendance = $existingAttendances[$student->id];
                // Pastikan nama siswa dan NIS selalu diisi
                $attendance['nama_siswa'] = $student->nama_lengkap;
                $attendance['nis'] = $student->nis;
                $attendances[] = $attendance;
            } else {
                $attendances[] = [
                    'student_id' => $student->id,
                    'nama_siswa' => $student->nama_lengkap,
                    'nis' => $student->nis,
                    'status' => 'Hadir',
                    'keterangan' => null,
                ];
            }
        }
        
        // Debug: Cek data final
        \Log::info('Final attendances: ' . json_encode($attendances));
        
        $set('attendances', $attendances);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('materi')
                    ->label('Materi')
                    ->limit(50),
                Tables\Columns\TextColumn::make('jam_ke_mulai')
                    ->label('Jam Ke')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('jam_ke_selesai')
                    ->label('Sampai Jam Ke')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('hadir_count')
                    ->label('Hadir')
                    ->state(function ($record) {
                        return collect($record->attendances)->where('status', 'Hadir')->count();
                    })
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('sakit_count')
                    ->label('Sakit')
                    ->state(function ($record) {
                        return collect($record->attendances)->where('status', 'Sakit')->count();
                    })
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('izin_count')
                    ->label('Izin')
                    ->state(function ($record) {
                        return collect($record->attendances)->where('status', 'Izin')->count();
                    })
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('alpha_count')
                    ->label('Alpha')
                    ->state(function ($record) {
                        return collect($record->attendances)->where('status', 'Alpha')->count();
                    })
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('dispensasi_count')
                    ->label('Dispensasi')
                    ->state(function ($record) {
                        return collect($record->attendances)->where('status', 'Dispensasi')->count();
                    })
                    ->alignCenter(),
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
                Tables\Filters\SelectFilter::make('kelas')
                    ->relationship('kelas', 'name')
                    ->label('Kelas')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('mata_pelajaran')
                    ->options(fn () => TeachingActivity::distinct()->pluck('mata_pelajaran', 'mata_pelajaran')->toArray())
                    ->label('Mata Pelajaran')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($livewire) {
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\TeachingActivitiesExport(
                                $livewire->getFilteredTableQuery()->get()
                            ),
                            'kegiatan-pembelajaran.xlsx'
                        );
                    })
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
            'index' => Pages\ListTeachingActivities::route('/'),
            'create' => Pages\CreateTeachingActivity::route('/create'),
            'view' => Pages\ViewTeachingActivity::route('/{record}'),
            'edit' => Pages\EditTeachingActivity::route('/{record}/edit'),
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