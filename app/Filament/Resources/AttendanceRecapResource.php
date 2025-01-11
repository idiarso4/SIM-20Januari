<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceRecapResource\Pages;
use App\Models\TeachingActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\DB;

class AttendanceRecapResource extends Resource
{
    protected static ?string $model = TeachingActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Rekap Kehadiran';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $modelLabel = 'Rekap Kehadiran';
    protected static ?string $pluralModelLabel = 'Rekap Kehadiran';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }

    public static function getSlug(): string
    {
        return 'teaching-activities';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendanceRecaps::route('/'),
            'create' => Pages\CreateAttendanceRecap::route('/create'),
            'edit' => Pages\EditAttendanceRecap::route('/{record}/edit'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Guru')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mata_pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jam_ke_mulai')
                    ->label('Jam Ke')
                    ->formatStateUsing(fn ($state) => "Jam ke-{$state}"),
                Tables\Columns\TextColumn::make('jam_ke_selesai')
                    ->label('Sampai Jam')
                    ->formatStateUsing(fn ($state) => "s/d jam ke-{$state}"),
                Tables\Columns\ViewColumn::make('attendance_summary')
                    ->label('Rekap Kehadiran')
                    ->view('filament.tables.columns.attendance-summary'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guru_id')
                    ->label('Guru')
                    ->relationship('guru', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('class_room_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama')
                    ->searchable()
                    ->preload(),
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
                    })
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Download Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\AttendanceRecapsExport(),
                            'rekap-kehadiran.xlsx'
                        );
                    })
            ])
            ->defaultSort('tanggal', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form fields here if needed
            ]);
    }
}
