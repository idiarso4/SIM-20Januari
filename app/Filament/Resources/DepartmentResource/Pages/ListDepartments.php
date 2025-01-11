<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Imports\DepartmentImporter;
use App\Filament\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(DepartmentImporter::class)
                ->label('Import'),
        ];
    }
} 