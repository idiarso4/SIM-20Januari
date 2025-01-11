<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class GrantSuperAdmin extends Command
{
    protected $signature = 'auth:grant-super-admin {email}';
    protected $description = 'Grant super admin role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        // Create super_admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

        // Grant all permissions to super_admin
        $user->assignRole($superAdminRole);

        $this->info("Successfully granted super admin role to {$user->name}");
        return 0;
    }
} 