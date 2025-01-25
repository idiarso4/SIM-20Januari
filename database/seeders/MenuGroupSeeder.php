<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class MenuGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Master Data',
                'order' => 1,
            ],
            [
                'name' => 'Akademik',
                'order' => 2,
            ],
            [
                'name' => 'Kesiswaan',
                'order' => 3,
            ],
            [
                'name' => 'BK',
                'order' => 4,
            ],
            [
                'name' => 'Settings',
                'order' => 5,
            ],
        ];

        foreach ($groups as $group) {
            MenuGroup::create($group);
        }
    }
} 