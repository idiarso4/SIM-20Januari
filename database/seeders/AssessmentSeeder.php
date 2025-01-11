<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assessment;
use App\Models\StudentScore;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        // Create a teacher if none exists
        if (!User::whereHas('roles', fn($q) => $q->where('name', 'guru'))->exists()) {
            $teacher = User::create([
                'name' => 'Guru Matematika',
                'email' => 'guru.matematika@example.com',
                'password' => bcrypt('password'),
            ]);
            $teacher->assignRole('guru');
        } else {
            $teacher = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))->first();
        }

        // Get or create a class room
        $classRoom = ClassRoom::first();
        if (!$classRoom) {
            // Get or create a department
            $department = \App\Models\Department::first();
            if (!$department) {
                $department = \App\Models\Department::create([
                    'name' => 'IPA',
                    'kode' => 'IPA',
                    'status' => true,
                ]);
            }

            // Get or create a school year
            $schoolYear = \App\Models\SchoolYear::first();
            if (!$schoolYear) {
                $schoolYear = \App\Models\SchoolYear::create([
                    'tahun' => '2024/2025',
                    'semester' => 'ganjil',
                    'status' => 'aktif',
                ]);
            }

            $classRoom = ClassRoom::create([
                'name' => 'X IPA 1',
                'level' => 'X',
                'department_id' => $department->id,
                'school_year_id' => $schoolYear->id,
                'homeroom_teacher_id' => $teacher->id,
                'is_active' => true,
            ]);
        }

        // Create some students if none exist
        if (Student::where('class_room_id', $classRoom->id)->count() === 0) {
            for ($i = 1; $i <= 10; $i++) {
                Student::create([
                    'nis' => '2024' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'nama_lengkap' => 'Siswa ' . $i,
                    'class_room_id' => $classRoom->id,
                    'email' => 'siswa' . $i . '@example.com',
                    'telp' => '08' . str_pad($i, 10, '0', STR_PAD_LEFT),
                    'jenis_kelamin' => $i % 2 === 0 ? 'L' : 'P',
                    'agama' => 'Islam',
                ]);
            }
        }

        $students = Student::where('class_room_id', $classRoom->id)->get();

        // Create sample assessments
        $assessments = [
            [
                'class_room_id' => $classRoom->id,
                'teacher_id' => $teacher->id,
                'type' => 'sumatif',
                'subject' => 'Matematika',
                'assessment_name' => 'Ulangan Harian 1',
                'date' => now(),
                'description' => 'Materi Aljabar',
                'notes' => 'Ulangan pertama semester ini',
            ],
            [
                'class_room_id' => $classRoom->id,
                'teacher_id' => $teacher->id,
                'type' => 'non_sumatif',
                'subject' => 'Matematika',
                'assessment_name' => 'Tugas 1',
                'date' => now()->subDays(2),
                'description' => 'Tugas Aljabar',
                'notes' => 'Tugas kelompok',
            ],
            [
                'class_room_id' => $classRoom->id,
                'teacher_id' => $teacher->id,
                'type' => 'sumatif',
                'subject' => 'Matematika',
                'assessment_name' => 'Ulangan Tengah Semester',
                'date' => now()->subDays(7),
                'description' => 'UTS Matematika',
                'notes' => 'Semua materi yang sudah dipelajari',
            ],
        ];

        DB::transaction(function () use ($assessments, $students) {
            foreach ($assessments as $assessmentData) {
                $assessment = Assessment::create($assessmentData);

                // Create scores for each student
                foreach ($students as $student) {
                    // Randomly assign status with weighted probability
                    $status = collect([
                        'hadir' => 85,  // 85% chance
                        'sakit' => 5,   // 5% chance
                        'izin' => 5,    // 5% chance
                        'alpha' => 5,   // 5% chance
                    ])->map(function ($weight) {
                        return $weight / 100;
                    })->pipe(function ($weights) {
                        $random = mt_rand(1, 100) / 100;
                        $sum = 0;
                        foreach ($weights as $status => $weight) {
                            $sum += $weight;
                            if ($random <= $sum) {
                                return $status;
                            }
                        }
                        return 'hadir';
                    });

                    StudentScore::create([
                        'assessment_id' => $assessment->id,
                        'student_id' => $student->id,
                        'status' => $status,
                        'score' => $status === 'hadir' ? mt_rand(60, 100) : null,
                    ]);
                }
            }
        });
    }
} 