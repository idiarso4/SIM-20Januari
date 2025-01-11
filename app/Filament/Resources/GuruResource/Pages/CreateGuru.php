<?php

namespace App\Filament\Resources\GuruResource\Pages;

use App\Filament\Resources\GuruResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateGuru extends CreateRecord
{
    protected static string $resource = GuruResource::class;

    protected function afterCreate(): void
    {
        // Assign the guru role to the newly created user
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $this->record->assignRole($guruRole);
    }
} 