<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DutyTeacherPermitResource\Pages;
use App\Models\StudentPermit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class DutyTeacherPermitResource extends Resource
{
    protected static ?string $model = StudentPermit::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'Persetujuan Guru Piket';
    
    protected static ?string $modelLabel = 'Persetujuan Izin';
    
    protected static ?string $pluralModelLabel = 'Persetujuan Izin';
    
    protected static ?string $navigationGroup = 'Piket & Perizinan';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'nama_lengkap')
                    ->required()
                    ->label('Nama Siswa')
                    ->disabled(),
                    
                Forms\Components\Select::make('approved_by')
                    ->relationship('approver', 'name')
                    ->required()
                    ->label('Guru yang Mengajukan')
                    ->disabled(),
                    
                Forms\Components\Hidden::make('piket_guru_id')
                    ->default(auth()->id()),
                    
                Forms\Components\DatePicker::make('permit_date')
                    ->required()
                    ->label('Tanggal Izin')
                    ->disabled(),
                    
                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->label('Waktu Mulai')
                    ->disabled(),
                    
                Forms\Components\TimePicker::make('end_time')
                    ->label('Waktu Selesai')
                    ->disabled(),
                    
                Forms\Components\TextInput::make('reason')
                    ->required()
                    ->label('Alasan')
                    ->disabled(),
                    
                Forms\Components\Select::make('status')
                    ->options([
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->required()
                    ->label('Status Persetujuan'),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan'),
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
                    ->label('Guru yang Mengajukan')
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
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
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
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    public static function getRelations(): array
    {
        return [];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDutyTeacherPermits::route('/'),
            'edit' => Pages\EditDutyTeacherPermit::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $today = Carbon::now();
        $dayName = str_replace(
            ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            $today->format('l')
        );

        // Get IDs of teachers who are on duty today
        $dutyTeacherIds = \App\Models\TeacherDuty::where('day', $dayName)
            ->where('is_active', true)
            ->whereTime('start_time', '<=', now())
            ->whereTime('end_time', '>=', now())
            ->pluck('teacher_id');

        return parent::getEloquentQuery()
            ->whereDate('permit_date', $today->format('Y-m-d'))
            ->where('status', 'pending')
            ->where(function ($query) use ($dutyTeacherIds) {
                $query->whereNull('piket_guru_id')
                    ->orWhere('piket_guru_id', auth()->id());
            })
            ->when(
                auth()->user()->hasRole('guru'),
                fn ($query) => $query->whereIn('piket_guru_id', $dutyTeacherIds)
            );
    }
} 