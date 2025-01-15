<?php

namespace App\Filament\Resources\TeachingActivityResource\Pages;

use App\Filament\Resources\TeachingActivityResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\TeachingActivity;

class EditTeachingActivity extends EditRecord
{
    protected static string $resource = TeachingActivityResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Cek duplikasi saat edit, kecuali untuk record yang sedang diedit
        $query = TeachingActivity::where('guru_id', auth()->id())
            ->where('tanggal', $data['tanggal'])
            ->where('jam_ke_mulai', $data['jam_ke_mulai'])
            ->where('id', '!=', $this->record->id);

        if ($query->exists()) {
            throw new \Exception('Jadwal mengajar untuk tanggal dan jam ini sudah ada.');
        }

        return $data;
    }
} 