<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Matematika',
                'code' => 'MTK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bahasa Indonesia',
                'code' => 'BIN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bahasa Inggris',
                'code' => 'BIG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ilmu Pengetahuan Alam',
                'code' => 'IPA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ilmu Pengetahuan Sosial',
                'code' => 'IPS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['code' => $subject['code']],
                $subject
            );
        }
    }
} 