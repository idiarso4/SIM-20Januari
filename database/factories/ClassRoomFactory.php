<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassRoomFactory extends Factory
{
    protected $model = ClassRoom::class;

    public function definition()
    {
        $level = $this->faker->randomElement(['X', 'XI', 'XII']);
        return [
            'name' => $level . ' ' . $this->faker->randomLetter(),
            'level' => $level,
            'department_id' => Department::factory(),
            'school_year_id' => SchoolYear::factory()
        ];
    }
} 