<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use BezhanSalleh\FilamentShield\Support\Utils;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Create or update super admin user
            $user = User::firstOrCreate(
                ['email' => 'idiarsosimbang@gmail.com'],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make('admin123YRK'),
                    'role' => 'super_admin',
                    'status' => 'aktif',
                ]
            );

            // Assign super admin role
            if (!$user->hasRole(Utils::getSuperAdminName())) {
                $user->assignRole(Utils::getSuperAdminName());
            }

            $this->info('Super admin user created/updated successfully!');
            
        } catch (\Exception $e) {
            $this->error('Failed to create super admin: ' . $e->getMessage());
        }
    }
}
