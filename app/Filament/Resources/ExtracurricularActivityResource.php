<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtracurricularActivityResource\Pages;
use App\Models\ExtracurricularActivity;
use App\Models\Student;
use App\Models\ExtracurricularActivityAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExtracurricularActivityExport;

class ExtracurricularActivityResource extends Resource
{
    protected static ?string $model = ExtracurricularActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Kegiatan Ekstrakurikuler';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('guru')) {
            $query->where('guru_id', auth()->id());
        }

        return $query->with('attendances');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal'),
                Forms\Components\Select::make('extracurricular_id')
                    ->relationship('extracurricular', 'nama')
                    ->label('Ekstrakurikuler')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $students = Student::whereHas('extracurriculars', function ($query) use ($state) {
                                $query->where('extracurricular_id', $state);
                            })->get();

                            $attendances = [];
                            foreach ($students as $student) {
                                $attendances[] = [
                                    'student_id' => $student->id,
                                    'nama_siswa' => $student->nama_lengkap,
                                    'nis' => $student->nis,
                                    'status' => ExtracurricularActivityAttendance::STATUS_HADIR,
                                    'keterangan' => null,
                                ];
                            }
                            $set('attendances', $attendances);
                        }
                    }),
                Forms\Components\Textarea::make('materi')
                    ->required()
                    ->label('Materi')
                    ->placeholder('Materi kegiatan ekstrakurikuler'),
                Forms\Components\Section::make('Daftar Hadir Anggota')
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
                                    ->options(ExtracurricularActivityAttendance::STATUS_OPTIONS)
                                    ->required(),
                                Forms\Components\TextInput::make('keterangan')
                                    ->label('Keterangan')
                                    ->placeholder('Opsional'),
                            ])
                            ->columns(5)
                            ->defaultItems(0)
                            ->disableItemCreation()
                            ->disableItemDeletion()
                            ->columnSpanFull(),
                    ]),
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
                Tables\Columns\TextColumn::make('extracurricular.nama')
                    ->label('Ekstrakurikuler')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('materi')
                    ->label('Materi')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Pembina')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('attendance_summary')
                    ->label('Kehadiran')
                    ->getStateUsing(function ($record) {
                        $summary = [
                            ExtracurricularActivityAttendance::STATUS_HADIR => 0,
                            ExtracurricularActivityAttendance::STATUS_SAKIT => 0,
                            ExtracurricularActivityAttendance::STATUS_IZIN => 0,
                            ExtracurricularActivityAttendance::STATUS_ALPHA => 0
                        ];
                        
                        foreach ($record->attendances as $attendance) {
                            $summary[$attendance->status]++;
                        }
                        
                        return "H:{$summary[ExtracurricularActivityAttendance::STATUS_HADIR]} " .
                               "S:{$summary[ExtracurricularActivityAttendance::STATUS_SAKIT]} " .
                               "I:{$summary[ExtracurricularActivityAttendance::STATUS_IZIN]} " .
                               "A:{$summary[ExtracurricularActivityAttendance::STATUS_ALPHA]}";
                    }),
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
                Tables\Filters\SelectFilter::make('extracurricular')
                    ->relationship('extracurricular', 'nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->preload()
                    ->visible(!auth()->user()->hasRole('guru')),
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
                        return Excel::download(
                            new ExtracurricularActivityExport(
                                static::getEloquentQuery()
                            ),
                            'kegiatan-ekstrakurikuler.xlsx'
                        );
                    }),
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
            'index' => Pages\ListExtracurricularActivities::route('/'),
            'create' => Pages\CreateExtracurricularActivity::route('/create'),
            'view' => Pages\ViewExtracurricularActivity::route('/{record}'),
            'edit' => Pages\EditExtracurricularActivity::route('/{record}/edit'),
        ];
    }    
} 