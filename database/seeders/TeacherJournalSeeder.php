<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\TeacherJournal;
use Carbon\Carbon;

class TeacherJournalSeeder extends Seeder
{
    public function run(): void
    {
        // Get a teacher
        $teacher = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))->first();
        if (!$teacher) {
            return;
        }

        // Get a classroom
        $classRoom = ClassRoom::first();
        if (!$classRoom) {
            return;
        }

        // Create sample journal entries for the past week
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }

            // Create teaching activity journal
            TeacherJournal::create([
                'guru_id' => $teacher->id,
                'class_room_id' => $classRoom->id,
                'mata_pelajaran' => 'Matematika',
                'materi' => 'Aljabar - Pertemuan ' . ($i + 1),
                'tanggal' => $date,
                'jam_ke_mulai' => 1,
                'jam_ke_selesai' => 2,
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '08:30:00',
                'media_dan_alat' => 'Papan tulis, Proyektor, Laptop',
                'kegiatan' => 'Menjelaskan materi aljabar dan latihan soal',
                'hasil' => 'Siswa memahami konsep dasar aljabar',
                'status' => 'submitted',
            ]);

            // Create assessment activity journal (if it's day 3)
            if ($i === 3) {
                TeacherJournal::create([
                    'guru_id' => $teacher->id,
                    'class_room_id' => $classRoom->id,
                    'mata_pelajaran' => 'Matematika',
                    'materi' => 'Ulangan Harian Aljabar',
                    'tanggal' => $date,
                    'jam_ke_mulai' => 3,
                    'jam_ke_selesai' => 4,
                    'jam_mulai' => '08:30:00',
                    'jam_selesai' => '10:00:00',
                    'media_dan_alat' => 'Lembar soal, lembar jawaban',
                    'kegiatan' => 'Pelaksanaan ulangan harian materi aljabar',
                    'hasil' => 'Ulangan berjalan lancar',
                    'status' => 'approved',
                ]);
            }

            // Create extracurricular activity journal (if it's day 2)
            if ($i === 2) {
                TeacherJournal::create([
                    'guru_id' => $teacher->id,
                    'class_room_id' => $classRoom->id,
                    'mata_pelajaran' => 'Ekstrakurikuler Matematika',
                    'materi' => 'Olimpiade Matematika',
                    'tanggal' => $date,
                    'jam_ke_mulai' => 9,
                    'jam_ke_selesai' => 10,
                    'jam_mulai' => '14:00:00',
                    'jam_selesai' => '15:30:00',
                    'media_dan_alat' => 'Modul olimpiade, soal latihan',
                    'kegiatan' => 'Pembahasan soal olimpiade matematika',
                    'hasil' => 'Siswa berlatih soal-soal olimpiade',
                    'status' => 'submitted',
                ]);
            }
        }
    }
} 