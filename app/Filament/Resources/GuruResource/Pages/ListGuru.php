<?php

namespace App\Filament\Resources\GuruResource\Pages;

use App\Filament\Resources\GuruResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Imports\GuruImport;
use App\Exports\GuruExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Validators\ValidationException;

class ListGuru extends ListRecords
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return Excel::download(new GuruExport, 'template-import-guru.xlsx');
                }),
            Actions\Action::make('importGuru')
                ->label('Import Data Guru')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel')
                        ->disk('local')
                        ->directory('temp')
                        ->visibility('private')
                        ->acceptedFileTypes([
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->maxSize(5120)
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $filePath = Storage::disk('local')->path($data['file']);
                        
                        Excel::import(new GuruImport(), $filePath);
                        
                        // Delete the file after import
                        Storage::disk('local')->delete($data['file']);

                        Notification::make()
                            ->title('Data guru berhasil diimport')
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
                            ->title('Gagal import data guru')
                            ->danger()
                            ->body(implode("\n", $messages))
                            ->persistent()
                            ->send();
                            
                        Storage::disk('local')->delete($data['file']);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal import data guru')
                            ->danger()
                            ->body($e->getMessage())
                            ->persistent()
                            ->send();
                            
                        Storage::disk('local')->delete($data['file']);
                    }
                })
                ->slideOver(),
            Actions\CreateAction::make(),
        ];
    }
} 