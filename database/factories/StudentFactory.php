<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'nis' => $this->faker->unique()->numerify('########'),
            'nama_lengkap' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telp' => $this->faker->phoneNumber(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'class_room_id' => ClassRoom::factory(),
            'user_id' => User::factory()
        ];
    }
} 