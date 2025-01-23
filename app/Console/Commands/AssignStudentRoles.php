<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignStudentRoles extends Command
{
    protected $signature = 'roles:assign-student';
    protected $description = 'Assign Siswa role to all students';

    public function handle()
    {
        // Create 'Siswa' role if it doesn't exist
        $role = Role::firstOrCreate([
            'name' => 'Siswa',
            'guard_name' => 'web'
        ]);

        $this->info("Role 'Siswa' created or already exists.");

        // Get all users who are marked as students (either by user_type or role column)
        $users = User::where('user_type', 'siswa')
            ->orWhere('role', 'siswa')
            ->get();

        $count = 0;
        foreach ($users as $user) {
            if (!$user->hasRole('Siswa')) {
                $user->assignRole('Siswa');
                $count++;
            }
        }

        $this->info("Assigned 'Siswa' role to {$count} users.");
    }
}
