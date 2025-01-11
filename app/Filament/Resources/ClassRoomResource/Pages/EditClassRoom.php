<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use App\Filament\Resources\ClassRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use App\Models\User;
use Filament\Forms\Components\Section;
use App\Imports\StudentImport;
use App\Exports\StudentTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Models\Student;

class EditClassRoom extends EditRecord
{
    protected static string $resource = ClassRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return Excel::download(new StudentTemplateExport, 'template-import-siswa.xlsx');
                }),
            Actions\Action::make('importSiswa')
                ->label('Import Data Siswa')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel')
                        ->disk('public')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->maxSize(5120)
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        Excel::import(new StudentImport($this->record->id), Storage::disk('public')->path($data['file']));
                        
                        // Hapus file setelah diimport
                        Storage::disk('public')->delete($data['file']);

                        Notification::make()
                            ->title('Data siswa berhasil diimport')
                            ->success()
                            ->send();
                    } catch (ValidationException $e) {
                        $failures = $e->failures();
                        $messages = [];
                        
                        foreach ($failures as $failure) {
                            $row = $failure->row();
                            $messages[] = "Baris {$row}: " . implode(', ', $failure->errors());
                        }
                        
                        Notification::make()
                            ->title('Gagal import data siswa')
                            ->danger()
                            ->body(implode("\n", $messages))
                            ->persistent()
                            ->send();
                            
                        Storage::disk('public')->delete($data['file']);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal import data siswa')
                            ->danger()
                            ->body($e->getMessage())
                            ->persistent()
                            ->send();
                            
                        Storage::disk('public')->delete($data['file']);
                    }
                })
                ->slideOver(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormSchema(): array 
    {
        return [
            Section::make('Data Kelas')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('level')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('major')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('year')
                        ->required()
                        ->maxLength(255),
                    Select::make('homeroom_teacher_id')
                        ->label('Wali Kelas')
                        ->options(User::where('role', 'guru')->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Toggle::make('is_active')
                        ->required()
                        ->default(true),
                ]),
        ];
    }
} 