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

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    
    protected static ?string $navigationLabel = 'Persetujuan Izin Siswa';
    
    protected static ?string $modelLabel = 'Persetujuan Izin';
    
    protected static ?string $pluralModelLabel = 'Persetujuan Izin';
    
    protected static ?string $navigationGroup = 'Piket & Perizinan';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->options([
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ])
                ->required()
                ->label('Status'),
                
            Forms\Components\Hidden::make('piket_guru_id')
                ->default(fn () => auth()->id()),
                
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
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'primary' => 'completed',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                    })
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
                Tables\Actions\EditAction::make()
                    ->visible(fn (StudentPermit $record) => $record->status === 'pending'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $today = Carbon::now();
                $dayName = str_replace(
                    ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                    $today->format('l')
                );
                
                return $query->whereDate('permit_date', $today)
                    ->whereHas('teacher', function ($q) use ($dayName, $today) {
                        $q->whereHas('teacherDuties', function ($q) use ($dayName, $today) {
                            $q->where('day', $dayName)
                                ->where('is_active', true)
                                ->whereTime('start_time', '<=', $today->format('H:i:s'))
                                ->whereTime('end_time', '>=', $today->format('H:i:s'));
                        });
                    });
            })
            ->defaultSort('created_at', 'desc');
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDutyTeacherPermits::route('/'),
            'edit' => Pages\EditDutyTeacherPermit::route('/{record}/edit'),
        ];
    }
} 