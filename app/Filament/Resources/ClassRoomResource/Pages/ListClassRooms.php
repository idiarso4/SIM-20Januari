<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use App\Filament\Resources\ClassRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClassRoomsImport;
use App\Exports\ClassRoomsExport;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ListClassRooms extends ListRecords
{
    protected static string $resource = ClassRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kelas'),
            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('File Excel')
                        ->required()
                        ->directory('temp')
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->helperText('Format: Nama Kelas, Tingkat, Jurusan, Tahun Pelajaran, Wali Kelas, Status'),
                ])
                ->action(function (array $data) {
                    $file = Storage::disk('public')->path($data['file']);
                    Excel::import(new ClassRoomsImport, $file);
                    
                    // Clean up
                    Storage::disk('public')->delete($data['file']);
                    
                    Notification::make()
                        ->success()
                        ->title('Import berhasil')
                        ->send();
                }),
            Action::make('export')
                ->label('Unduh Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return Excel::download(new ClassRoomsExport, 'daftar-kelas.xlsx');
                }),
        ];
    }
} 