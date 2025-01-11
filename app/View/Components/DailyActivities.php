<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\TeachingActivity;
use App\Models\Assessment;
use App\Models\ExtracurricularActivity;

class DailyActivities extends Component
{
    public $teaching_activities;
    public $assessments;
    public $extracurriculars;

    public function __construct($date = null)
    {
        $date = $date ?? now();
        
        $this->teaching_activities = TeachingActivity::whereDate('tanggal', $date)
            ->where('guru_id', auth()->id())
            ->get();
            
        $this->assessments = Assessment::whereDate('date', $date)
            ->where('teacher_id', auth()->id())
            ->get();
            
        $this->extracurriculars = ExtracurricularActivity::whereDate('tanggal', $date)
            ->where('guru_id', auth()->id())
            ->get();
    }

    public function render()
    {
        return view('filament.forms.components.daily-activities');
    }
} 