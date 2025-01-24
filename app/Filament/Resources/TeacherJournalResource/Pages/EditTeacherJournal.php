<?php

namespace App\Filament\Resources\TeacherJournalResource\Pages;

use App\Filament\Resources\TeacherJournalResource;
use App\Models\TeachingActivity;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeacherJournal extends EditRecord
{
    protected static string $resource = TeacherJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

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
} 