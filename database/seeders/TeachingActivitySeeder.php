<?php

namespace Database\Seeders;

use App\Models\TeachingActivity;
use Illuminate\Database\Seeder;

class TeachingActivitySeeder extends Seeder
{
    public function run()
    {
        TeachingActivity::factory()
            ->count(50)
            ->create();
    }
} 