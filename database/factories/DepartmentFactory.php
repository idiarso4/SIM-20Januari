<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'kode' => $this->faker->unique()->bothify('???###'),
            'status' => 'aktif'
        ];
    }
} 