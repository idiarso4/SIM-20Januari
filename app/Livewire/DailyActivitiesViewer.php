<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TeachingActivity;
use App\Models\Assessment;
use App\Models\ExtracurricularActivity;

class DailyActivitiesViewer extends Component
{
    public $tanggal;
    public $guruId;
    
    public function mount($tanggal = null, $guruId = null)
    {
        $this->tanggal = $tanggal ?? now();
        $this->guruId = $guruId ?? auth()->id();
    }
    
    public function render()
    {
        $activities = [
            'teaching_activities' => TeachingActivity::whereDate('tanggal', $this->tanggal)
                ->where('guru_id', $this->guruId)
                ->get(),
            'assessments' => Assessment::whereDate('date', $this->tanggal)
                ->where('teacher_id', $this->guruId)
                ->get(),
            'extracurriculars' => ExtracurricularActivity::whereDate('tanggal', $this->tanggal)
                ->where('guru_id', $this->guruId)
                ->get(),
        ];
        
        return view('livewire.daily-activities-viewer', [
            'activities' => $activities,
        ]);
    }
    
    public function updatedTanggal()
    {
        $this->dispatch('activities-updated');
    }
    
    public function updatedGuruId()
    {
        $this->dispatch('activities-updated');
    }
} 