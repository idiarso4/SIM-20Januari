<?php

namespace App\Http\Controllers;

use App\Models\TeacherJournal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherJournalController extends Controller
{
    public function download(TeacherJournal $teacherJournal)
    {
        abort_unless(
            auth()->user()->hasRole('admin') || $teacherJournal->guru_id === auth()->id(),
            403
        );

        $pdf = PDF::loadView('pdf.teacher-journal', [
            'journal' => $teacherJournal,
            'teaching_activities' => $teacherJournal->guru->teachingActivities()
                ->whereDate('tanggal', $teacherJournal->tanggal)
                ->get(),
            'assessments' => $teacherJournal->guru->assessments()
                ->whereDate('date', $teacherJournal->tanggal)
                ->get(),
            'extracurriculars' => $teacherJournal->guru->extracurricularActivities()
                ->whereDate('tanggal', $teacherJournal->tanggal)
                ->get(),
        ]);

        return $pdf->download('agenda-harian-' . $teacherJournal->tanggal . '.pdf');
    }
} 