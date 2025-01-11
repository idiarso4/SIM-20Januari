<?php

namespace Database\Seeders;

use App\Models\SchoolYear;
use Illuminate\Database\Seeder;

class SchoolYearSeeder extends Seeder
{
    public function run()
    {
        $schoolYears = [
            [
                'tahun' => '2024/2025',
                'semester' => 'ganjil',
                'status' => 'aktif',
            ],
            [
                'tahun' => '2024/2025',
                'semester' => 'genap',
                'status' => 'aktif',
            ],
        ];

        foreach ($schoolYears as $year) {
            SchoolYear::create($year);
        }
    }
}
