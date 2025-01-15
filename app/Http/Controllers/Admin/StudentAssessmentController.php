<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentAssessment;
use App\Models\StudentAssessmentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAssessmentController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validasi input
            $request->validate([
                'tanggal' => 'required|date',
                'class_room_id' => 'required|exists:class_rooms,id',
                'mata_pelajaran' => 'required|string',
                'jenis' => 'required|in:Sumatif,Non-Sumatif',
                'kategori' => 'required|in:Teori,Praktik',
                'details' => 'required|array',
                'details.*.student_id' => 'required|exists:students,id',
                'details.*.nilai' => 'nullable|numeric|min:0|max:100',
            ]);

            // Simpan header penilaian
            $assessment = StudentAssessment::create([
                'tanggal' => $request->tanggal,
                'guru_id' => auth()->id(),
                'class_room_id' => $request->class_room_id,
                'mata_pelajaran' => $request->mata_pelajaran,
                'jenis' => $request->jenis,
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan,
            ]);

            // Debug info
            \Log::info('Assessment created:', [
                'assessment_id' => $assessment->id,
                'guru_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            // Simpan detail nilai siswa
            foreach ($request->details as $detail) {
                StudentAssessmentDetail::create([
                    'student_assessment_id' => $assessment->id,
                    'student_id' => $detail['student_id'],
                    'nilai' => $detail['nilai'] ?? 0,
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('filament.admin.resources.student-assessments.index')
                ->with('success', 'Nilai berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error saving assessment:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Gagal menyimpan nilai: ' . $e->getMessage()]);
        }
    }
} 