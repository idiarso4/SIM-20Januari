<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Imports\ClassroomImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Exports\StudentTemplateExport;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

class StudentsRelationManager extends RelationManager
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $relationship = 'students';

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    protected static ?string $title = 'Siswa';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nis')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_lengkap')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telp')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('agama')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_lengkap')
            ->columns([
                Tables\Columns\TextColumn::make('nis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agama')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        return Excel::download(new StudentTemplateExport, 'template_siswa.xlsx');
                    }),
                Action::make('import')
                    ->label('Import Siswa')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('File Excel')
                            ->helperText('Format file: XLSX. Ukuran maksimal: 5MB')
                            ->disk('local')
                            ->directory('temp')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            ])
                            ->maxSize(5120)
                            ->required(),
                    ])
                    ->action(function (array $data, RelationManager $livewire): void {
                        $classRoomId = $livewire->ownerRecord->id;
                        
                        try {
                            if (!isset($data['file'])) {
                                throw new \Exception('File tidak ditemukan.');
                            }

                            $filePath = storage_path('app/' . $data['file']);
                            
                            if (!file_exists($filePath)) {
                                throw new \Exception('File tidak ditemukan di server.');
                            }

                            $rows = SimpleExcelReader::create($filePath)
                                ->fromSheet(1)
                                ->getRows()
                                ->each(function(array $row) use ($classRoomId) {
                                    if (empty($row['nis']) || empty($row['nama_lengkap']) || empty($row['email'])) {
                                        throw new \Exception('Data siswa tidak lengkap. Pastikan NIS, nama lengkap, dan email terisi.');
                                    }

                                    DB::transaction(function() use ($row, $classRoomId) {
                                        // Create user account
                                        $user = User::create([
                                            'name' => $row['nama_lengkap'],
                                            'email' => $row['email'],
                                            'password' => Hash::make('password'),
                                        ]);

                                        // Assign role siswa to user
                                        $user->assignRole('siswa');

                                        // Create student record
                                        Student::create([
                                            'nis' => $row['nis'],
                                            'nama_lengkap' => $row['nama_lengkap'],
                                            'jenis_kelamin' => $row['jenis_kelamin'] ?? 'L',
                                            'agama' => $row['agama'] ?? 'Islam',
                                            'email' => $row['email'],
                                            'telp' => $row['telp'] ?? null,
                                            'class_room_id' => $classRoomId,
                                            'user_id' => $user->id,
                                        ]);
                                    });
                                });

                            Notification::make()
                                ->success()
                                ->title('Import Berhasil')
                                ->body('Data siswa berhasil diimport.')
                                ->send();

                            $livewire->refreshTable();
                        } catch (\Exception $e) {
                            \Log::error('Import error: ' . $e->getMessage());
                            \Log::error($e->getTraceAsString());
                            
                            Notification::make()
                                ->danger()
                                ->title('Import Gagal')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function refresh(): void
    {
        $this->emit('refreshComponent');
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }
} 