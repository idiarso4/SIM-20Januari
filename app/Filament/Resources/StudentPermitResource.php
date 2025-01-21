<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentPermitResource\Pages;
use App\Models\StudentPermit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StudentPermitExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class StudentPermitResource extends Resource
{
    protected static ?string $model = StudentPermit::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    
    protected static ?string $navigationLabel = 'Administrasi Guru Piket';
    
    protected static ?string $modelLabel = 'Perizinan Siswa';
    
    protected static ?string $pluralModelLabel = 'Perizinan Siswa';
    
    protected static ?string $navigationGroup = 'Piket & Perizinan';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'nama_lengkap')
                    ->required()
                    ->label('Nama Siswa')
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\Select::make('approved_by')
                    ->options(function() {
                        return \App\Models\User::query()
                            ->whereHas('roles', fn($q) => $q->where('name', 'guru'))
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->label('Guru yang Menyetujui')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Pilih guru yang menyetujui izin siswa')
                    ->columnSpan('full'),
                    
                Forms\Components\Select::make('piket_guru_id')
                    ->relationship('piketGuru', 'name', function ($query) {
                        $today = Carbon::now();
                        $dayName = str_replace(
                            ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                            ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                            $today->format('l')
                        );
                        
                        return $query->whereHas('teacherDuties', function ($q) use ($dayName, $today) {
                            $q->where('day', $dayName)
                                ->where('is_active', true)
                                ->whereTime('start_time', '<=', $today->format('H:i:s'))
                                ->whereTime('end_time', '>=', $today->format('H:i:s'));
                        })->where('role', 'guru');
                    })
                    ->label('Guru Piket')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText(function() {
                        $today = Carbon::now();
                        return 'Guru piket hari ' . str_replace(
                            ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                            ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                            $today->format('l')
                        );
                    }),
                    
                Forms\Components\DatePicker::make('permit_date')
                    ->required()
                    ->label('Tanggal Izin')
                    ->default(now()),
                    
                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->label('Waktu Mulai'),
                    
                Forms\Components\TimePicker::make('end_time')
                    ->label('Waktu Selesai'),
                    
                Forms\Components\TextInput::make('reason')
                    ->required()
                    ->label('Alasan')
                    ->maxLength(255),
                    
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('pending')
                    ->required()
                    ->label('Status'),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('approver.name')
                    ->label('Disetujui Oleh')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('piketGuru.name')
                    ->label('Guru Piket')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('permit_date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('reason')
                    ->label('Alasan')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('print')
                    ->label('Cetak Surat')
                    ->icon('heroicon-o-printer')
                    ->action(function (StudentPermit $record) {
                        $pdf = Pdf::loadView('pdf.student-permit-report', [
                            'permit' => $record
                        ]);
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'surat-izin-' . $record->id . '.pdf');
                    }),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Download Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        return Excel::download(
                            new StudentPermitExport(StudentPermit::all()),
                            'perizinan-siswa-' . now()->format('Y-m-d') . '.xlsx'
                        );
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('export')
                        ->label('Download Excel Terpilih')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return Excel::download(
                                new StudentPermitExport($records),
                                'perizinan-terpilih-' . now()->format('Y-m-d') . '.xlsx'
                            );
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListStudentPermits::route('/'),
            'create' => Pages\CreateStudentPermit::route('/create'),
            'edit' => Pages\EditStudentPermit::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes();
    }
}
