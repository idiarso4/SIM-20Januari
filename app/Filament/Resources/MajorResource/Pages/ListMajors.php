<?php

namespace App\Filament\Resources\MajorResource\Pages;

use App\Filament\Resources\MajorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\MajorExport;
use App\Exports\MajorTemplateExport;
use App\Imports\MajorImport;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;

class ListMajors extends ListRecords
{
    protected static string $resource = MajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()
                ->label('Download Data')
                ->exporter(MajorExport::class)
                ->fileName('data-kelas-' . date('Y-m-d')),
            ExportAction::make()
                ->label('Download Template')
                ->exporter(MajorTemplateExport::class)
                ->fileName('template-kelas'),
            ImportAction::make()
                ->label('Import Data')
                ->importer(MajorImport::class)
        ];
    }
} 