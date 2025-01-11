<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TeachingActivity;
use App\Models\Assessment;
use App\Models\ExtracurricularActivity;

class DailyActivities extends Component
{
    public $date;
    public $guruId;
    
    public function mount($date = null, $guruId = null)
    {
        $this->date = $date ?? now();
        $this->guruId = $guruId ?? auth()->id();
    }
    
    public function getActivitiesProperty()
    {
        return [
            'teaching_activities' => TeachingActivity::whereDate('tanggal', $this->date)
                ->where('guru_id', $this->guruId)
                ->get(),
            'assessments' => Assessment::whereDate('date', $this->date)
                ->where('teacher_id', $this->guruId)
                ->get(),
            'extracurriculars' => ExtracurricularActivity::whereDate('tanggal', $this->date)
                ->where('guru_id', $this->guruId)
                ->get(),
        ];
    }
    
    public function render()
    {
        return view('livewire.daily-activities', [
            'activities' => $this->activities,
        ]);
    }
} 