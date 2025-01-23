<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< Updated upstream
        // Create basic roles
        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'guru', 'guard_name' => 'web']);
        Role::create(['name' => 'siswa', 'guard_name' => 'web']);
=======
        $roles = [
            'super_admin',
            'admin',
            'guru',
            'siswa',
            'Guru Piket'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role],
                ['guard_name' => 'web']
            );
        }
>>>>>>> Stashed changes
    }
}
