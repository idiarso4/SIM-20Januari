<?php

namespace Database\Factories;

use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolYearFactory extends Factory
{
    protected $model = SchoolYear::class;

    public function definition()
    {
        $year = $this->faker->numberBetween(2020, 2024);
        return [
            'tahun' => $year . '/' . ($year + 1),
            'semester' => $this->faker->randomElement(['ganjil', 'genap']),
            'status' => 'aktif',
            'is_active' => true
        ];
    }
} 