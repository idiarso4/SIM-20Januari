<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherScheduleResource\Pages;
use App\Models\TeacherSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\Action;
use App\Imports\TeacherScheduleImport;
use App\Exports\TeacherScheduleExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class TeacherScheduleResource extends Resource
{
    protected static ?string $model = TeacherSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Jadwal Guru';
    protected static ?string $pluralModelLabel = 'Jadwal Guru';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('guru_id')
                    ->relationship('guru', 'name')
                    ->label('Guru')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('class_room_id')
                    ->relationship('classRoom', 'name')
                    ->label('Kelas')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('subject')
                    ->label('Mata Pelajaran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('day')
                    ->label('Hari')
                    ->options([
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                    ])
                    ->required(),
                Forms\Components\TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->required(),
                Forms\Components\TextInput::make('room')
                    ->label('Ruangan')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Guru')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('day')
                    ->label('Hari')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Jam Mulai')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Jam Selesai')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room')
                    ->label('Ruangan')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guru_id')
                    ->relationship('guru', 'name')
                    ->label('Guru')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('class_room_id')
                    ->relationship('classRoom', 'name')
                    ->label('Kelas')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('day')
                    ->label('Hari')
                    ->options([
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                Action::make('import')
                    ->label('Import Data')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->action(function (array $data): void {
                        try {
                            Excel::import(new TeacherScheduleImport, $data['file']);
                            Notification::make()
                                ->title('Berhasil import data')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal import data')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('File Excel')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->required()
                    ]),
                Action::make('export')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        return Excel::download(new TeacherScheduleExport, 'template-jadwal-guru.xlsx');
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeacherSchedules::route('/'),
            'create' => Pages\CreateTeacherSchedule::route('/create'),
            'edit' => Pages\EditTeacherSchedule::route('/{record}/edit'),
        ];
    }
} 