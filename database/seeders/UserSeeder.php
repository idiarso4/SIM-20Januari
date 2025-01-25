<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('super_admin');

        // Create admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create guru
        $guru = User::create([
            'name' => 'Guru',
            'email' => 'guru@guru.com',
            'password' => Hash::make('password'),
        ]);
        $guru->assignRole('guru');

        // Create guru piket
        $guruPiket = User::create([
            'name' => 'Guru Piket',
            'email' => 'gurupiket@guru.com',
            'password' => Hash::make('password'),
        ]);
        $guruPiket->assignRole('Guru Piket');

        // Create siswa
        $siswa = User::create([
            'name' => 'Siswa',
            'email' => 'siswa@siswa.com',
            'password' => Hash::make('password'),
        ]);
        $siswa->assignRole('siswa');

        // You can add more users or use factories
        // User::factory(10)->create();
    }
} 