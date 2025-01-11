<?php

namespace App\Filament\Resources\PklInternshipResource\Pages;

use App\Filament\Resources\PklInternshipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\PklInternshipExport;
use App\Imports\PklInternshipImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions\ExportAction;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\FileUpload;

class ListPklInternships extends ListRecords
{
    protected static string $resource = PklInternshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('download_data')
                ->label('Download data')
                ->color('success')
                ->action(function () {
                    return Excel::download(new PklInternshipExport, 'pkl-internships.xlsx');
                }),
            Actions\Action::make('import_data')
                ->label('Import data')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel')
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    Excel::import(new PklInternshipImport, $data['file']);
                    $this->notify('success', 'Data berhasil diimport');
                }),
        ];
    }
} 