<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        Permission::create(['name' => 'export_class-room']);
        Permission::create(['name' => 'export_student']);

        // Assign to super admin
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $superAdminRole->givePermissionTo([
            'export_class-room',
            'export_student'
        ]);

        // Don't assign to guru
        $guruRole = Role::where('name', 'guru')->first();
        // guru tidak diberi permission export
    }
} 