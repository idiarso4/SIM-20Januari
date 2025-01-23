<?php

namespace App\Filament\Resources\StudentPermitResource\Pages;

use App\Filament\Resources\StudentPermitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentPermit extends CreateRecord
{
    protected static string $resource = StudentPermitResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'pending';
        $data['piket_guru_id'] = null; // Will be set by duty teacher
        $data['approved_by'] = auth()->id();

        return $data;
    }
}
