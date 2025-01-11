<?php

namespace Database\Factories;

use App\Models\ClassAttendance;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassAttendanceFactory extends Factory
{
    protected $model = ClassAttendance::class;

    public function definition()
    {
        return [
            'student_id' => Student::factory(),
            'class_room_id' => ClassRoom::factory(),
            'subject_id' => Subject::factory(),
            'teacher_id' => User::factory(),
            'tanggal' => $this->faker->date(),
            'jam_ke' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['hadir', 'sakit', 'izin', 'alpha', 'dispensasi']),
            'keterangan' => $this->faker->sentence(),
        ];
    }
} 