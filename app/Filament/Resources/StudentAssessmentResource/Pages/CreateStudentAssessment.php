<?php

namespace App\Filament\Resources\StudentAssessmentResource\Pages;

use App\Filament\Resources\StudentAssessmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentAssessment extends CreateRecord
{
    protected static string $resource = StudentAssessmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['guru_id'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
