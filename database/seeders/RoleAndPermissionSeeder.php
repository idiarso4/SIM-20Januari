<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $siswa = Role::firstOrCreate(['name' => 'siswa']);

        // Hapus role 'teacher' yang lama jika ada
        Role::where('name', 'teacher')->delete();

        // Generate Shield permissions
        $this->command->info('Creating Shield permissions...');
        
        // Create permissions for each resource
        $resources = [
            'user',
            'role',
            'permission',
            'guru',
            'siswa',
            'kelas',
            'mata-pelajaran',
            'jadwal',
        ];

        foreach ($resources as $resource) {
            Permission::firstOrCreate(['name' => "{$resource}_view_any"]);
            Permission::firstOrCreate(['name' => "{$resource}_view"]);
            Permission::firstOrCreate(['name' => "{$resource}_create"]);
            Permission::firstOrCreate(['name' => "{$resource}_update"]);
            Permission::firstOrCreate(['name' => "{$resource}_delete"]);
            Permission::firstOrCreate(['name' => "{$resource}_restore"]);
            Permission::firstOrCreate(['name' => "{$resource}_force_delete"]);
        }

        // Additional permissions
        Permission::firstOrCreate(['name' => 'view_admin']);
        Permission::firstOrCreate(['name' => 'access_filament']);

        // Assign all permissions to super_admin
        $superAdmin->givePermissionTo(Permission::all());

        // Create super admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        
        $user->assignRole($superAdmin);
    }
}
