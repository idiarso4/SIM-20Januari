<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Truncate tables first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $guru = Role::firstOrCreate(['name' => 'guru']);
        $wali = Role::firstOrCreate(['name' => 'wali']);

        // Create permissions for each resource
        $resources = [
            'user',
            'guru',
            'siswa',
            'kelas',
            'attendance',
            'mata-pelajaran',
            'jadwal',
            'shield',
        ];

        $actions = [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'delete_any',
            'import',
            'export',
        ];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $resource.'_'.$action]);
            }
        }

        // Additional permissions
        Permission::firstOrCreate(['name' => 'view_admin_panel']);
        Permission::firstOrCreate(['name' => 'access_filament']);
        Permission::firstOrCreate(['name' => 'shield']);

        // Assign permissions to roles
        $superAdmin->givePermissionTo(Permission::all());
        
        $admin->givePermissionTo([
            'view_admin_panel',
            'access_filament',
            'shield',
            'guru_view_any',
            'guru_view',
            'guru_create',
            'guru_update',
            'guru_delete',
            'guru_import',
            'guru_export',
            'siswa_view_any',
            'siswa_view',
            'siswa_create',
            'siswa_update',
            'siswa_delete',
            'siswa_import',
            'siswa_export',
            'kelas_view_any',
            'kelas_view',
            'kelas_create',
            'kelas_update',
            'kelas_delete',
            'attendance_view_any',
            'attendance_view',
        ]);

        $guru->givePermissionTo([
            'view_admin_panel',
            'access_filament',
            'attendance_view_any',
            'attendance_view',
            'attendance_create',
            'attendance_update',
            'jadwal_view_any',
            'jadwal_view',
        ]);

        // Create super admin user if it doesn't exist
        $user = User::firstOrCreate(
            ['email' => 'idiarsosimbang@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123YRK'),
                'email_verified_at' => now(),
            ]
        );
        
        $user->assignRole($superAdmin);
    }
}
