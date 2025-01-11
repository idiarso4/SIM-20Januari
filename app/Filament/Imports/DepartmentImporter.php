<?php

namespace App\Filament\Imports;

use App\Models\Department;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Illuminate\Support\Str;

class DepartmentImporter extends Importer
{
    protected static ?string $model = Department::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nama Jurusan')
                ->required(),
                
            ImportColumn::make('code')
                ->label('Kode')
                ->required()
                ->rules(['string']),
                
            ImportColumn::make('is_active')
                ->label('Status')
                ->required()
                ->rules(['boolean']),
        ];
    }

    public function resolveRecord(): ?Department
    {
        return new Department();
    }

    public static function beforeImport(): void
    {
        // Optional: Add any setup logic here
    }

    public function afterImport(): void
    {
        // Optional: Add any cleanup logic here
    }

    protected function mutateBeforeSave(array $data): array 
    {
        // Don't include code and is_active in the data array
        return [
            'name' => $data['name'],
        ];
    }

    protected function afterFill(Department $record, array $data): void
    {
        // Set the mapped fields directly on the model
        $record->kode = Str::slug($data['code']);
        $record->status = $data['is_active'];
    }
} 