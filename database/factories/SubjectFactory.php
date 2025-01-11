<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'code' => $this->faker->unique()->bothify('???###'),
            'description' => $this->faker->sentence(),
            'is_active' => true
        ];
    }
} 