<?php

namespace App\Filament\Resources\DutyTeacherPermitResource\Pages;

use App\Filament\Resources\DutyTeacherPermitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon;

class EditDutyTeacherPermit extends EditRecord
{
    protected static string $resource = DutyTeacherPermitResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['piket_guru_id'] = auth()->id();
        
        if ($data['status'] === 'approved') {
            $data['approved_at'] = now();
        }
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
} 