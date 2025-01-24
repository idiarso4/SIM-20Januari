<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Actions\ResetStudentData;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Data Siswa';
    
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nis')
                    ->label('NIS')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('agama')
                    ->label('Agama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telp')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Select::make('class_room_id')
                    ->label('Kelas')
                    ->relationship('classRoom', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('agama')
                    ->label('Agama'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('telp')
                    ->label('No. Telepon'),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->separator(','),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New student'),
                Action::make('import')
                    ->label('Import Data Siswa')
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('File Excel')
                            ->disk('public')
                            ->acceptedFileTypes([
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        try {
                            Excel::import(new StudentsImport, storage_path('app/public/' . $data['file']));
                            
                            FilamentNotification::make()
                                ->title('Import berhasil')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            FilamentNotification::make()
                                ->title('Import gagal')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                                
                            Log::error('Import failed:', [
                                'error' => $e->getMessage(),
                                'file' => $data['file']
                            ]);
                        }
                    }),
                Action::make('export')
                    ->label('Download Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(route('admin.students.export'))
                    ->openUrlInNewTab(),
                Action::make('reset_data')
                    ->label('Reset Data Siswa')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Data Siswa')
                    ->modalDescription('Apakah Anda yakin ingin mereset data siswa? Semua data siswa akan dihapus.')
                    ->modalSubmitActionLabel('Ya, Reset Data')
                    ->modalCancelActionLabel('Batal')
                    ->action(function () {
                        // Hapus data terkait terlebih dahulu
                        DB::table('student_permits')->truncate();
                        DB::table('extracurricular_student')->truncate();
                        DB::table('students')->truncate();

                        Notification::make()
                            ->title('Data siswa berhasil direset')
                            ->success()
                            ->send();
                    })
            ])
            ->defaultSort('nama_lengkap', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('class_room_id')
                    ->label('Kelas')
                    ->relationship('classRoom', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('assignSiswaRole')
                        ->label('Set sebagai Siswa')
                        ->icon('heroicon-o-academic-cap')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = 0;
                            foreach ($records as $student) {
                                if (!$student->user->hasRole('siswa')) {
                                    $student->user->assignRole('siswa');
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->success()
                                ->title("Berhasil menambahkan role Siswa ke {$count} siswa")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
} 