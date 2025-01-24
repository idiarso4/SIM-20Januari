<?php

namespace App\Filament\Resources\TeacherJournalResource\Pages;

use App\Filament\Resources\TeacherJournalResource;
use App\Models\TeachingActivity;
use Filament\Resources\Pages\ViewRecord;

class ViewTeacherJournal extends ViewRecord
{
    protected static string $resource = TeacherJournalResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);
        
        $record = $this->getRecord();
        
        if ($record && $record->tanggal) {
            $this->data['teaching_activities'] = TeachingActivity::where('guru_id', auth()->id())
                ->where('tanggal', $record->tanggal)
                ->get();
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ambil data record saat ini
        $record = $this->getRecord();
        
        // Jika ada tanggal, ambil teaching activities
        if ($record && $record->tanggal) {
            $data['teaching_activities'] = TeachingActivity::where('guru_id', auth()->id())
                ->where('tanggal', $record->tanggal)
                ->get();
        } else {
            $data['teaching_activities'] = collect([]);
        }

        return $data;
    }
} 