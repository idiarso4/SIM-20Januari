<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use BezhanSalleh\FilamentShield\Support\Utils;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'idiarsosimbang@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123YRK'),
                'role' => 'super_admin',
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole(Utils::getSuperAdminName());
    }
} 