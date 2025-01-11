<?php

namespace App\Filament\Resources\TeachingActivityResource\Pages;

use App\Filament\Resources\TeachingActivityResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTeachingActivity extends CreateRecord
{
    protected static string $resource = TeachingActivityResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set guru_id jika user adalah guru
        if (auth()->user()->hasRole('guru')) {
            $data['guru_id'] = auth()->id();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Redirect ke form absensi setelah create
        $activity = $this->record;
        
        $this->redirect(route('filament.admin.resources.teaching-activities.edit', [
            'record' => $activity,
            'activeTab' => 'absensi'
        ]));
    }
} 