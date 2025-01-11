<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'guru', 'guard_name' => 'web']);
        Role::create(['name' => 'siswa', 'guard_name' => 'web']);
        Role::create(['name' => 'waka', 'guard_name' => 'web']);
        Role::create(['name' => 'guru_pembimbing_pkl', 'guard_name' => 'web']);
        Role::create(['name' => 'guru_piket', 'guard_name' => 'web']);
        Role::create(['name' => 'guru_bk', 'guard_name' => 'web']);

        // Create permissions
        $permissions = [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_any_student',
            'view_student',
            'create_student',
            'update_student',
            'delete_student',
            'import_student',
            'export_student',
            'view_any_guru',
            'view_guru',
            'create_guru',
            'update_guru',
            'delete_guru',
            'import_guru',
            'export_guru',
            // Extracurricular Activity Permissions
            'view_any_extracurricular_activity',
            'view_extracurricular_activity',
            'create_extracurricular_activity',
            'update_extracurricular_activity',
            'delete_extracurricular_activity',
            'export_extracurricular_activity',
            // Teacher Journal Permissions
            'view_any_teacher_journal',
            'view_teacher_journal',
            'create_teacher_journal',
            'update_teacher_journal',
            'delete_teacher_journal',
            'approve_teacher_journal',
            // Student Assessment Permissions
            'view_any_student_assessment',
            'view_student_assessment',
            'create_student_assessment',
            'update_student_assessment',
            'delete_student_assessment',
            // Add permission for the complete profile page
            'page_CompleteProfile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roles = [
            'super_admin' => [
                'view_users',
                'create_users',
                'edit_users',
                'delete_users',
                'view_any_student',
                'view_student',
                'create_student',
                'update_student',
                'delete_student',
                'import_student',
                'export_student',
                'view_any_guru',
                'view_guru',
                'create_guru',
                'update_guru',
                'delete_guru',
                'import_guru',
                'export_guru',
                'view_any_extracurricular_activity',
                'view_extracurricular_activity',
                'create_extracurricular_activity',
                'update_extracurricular_activity',
                'delete_extracurricular_activity',
                'export_extracurricular_activity',
                'view_any_teacher_journal',
                'view_teacher_journal',
                'create_teacher_journal',
                'update_teacher_journal',
                'delete_teacher_journal',
                'approve_teacher_journal',
                'view_any_student_assessment',
                'view_student_assessment',
                'create_student_assessment',
                'update_student_assessment',
                'delete_student_assessment',
            ],
            'admin' => [
                'view_users',
                'create_users',
                'edit_users',
                'delete_users',
                'view_any_student',
                'view_student',
                'create_student',
                'update_student',
                'delete_student',
                'import_student',
                'export_student',
                'view_any_guru',
                'view_guru',
                'create_guru',
                'update_guru',
                'delete_guru',
                'import_guru',
                'export_guru',
                'view_any_extracurricular_activity',
                'view_extracurricular_activity',
                'create_extracurricular_activity',
                'update_extracurricular_activity',
                'delete_extracurricular_activity',
                'export_extracurricular_activity',
                'view_any_teacher_journal',
                'view_teacher_journal',
                'approve_teacher_journal',
                'view_any_student_assessment',
                'view_student_assessment',
            ],
            'guru' => [
                'view_users',
                'view_any_student',
                'view_student',
                'view_any_extracurricular_activity',
                'view_extracurricular_activity',
                'create_extracurricular_activity',
                'update_extracurricular_activity',
                'export_extracurricular_activity',
                'view_any_teacher_journal',
                'view_teacher_journal',
                'create_teacher_journal',
                'update_teacher_journal',
                'view_any_student_assessment',
                'view_student_assessment',
                'create_student_assessment',
                'update_student_assessment',
            ],
            'siswa' => [
                'view_any_student',
                'view_student',
                'view_any_extracurricular_activity',
                'view_extracurricular_activity',
                'view_student_assessment',
            ],
            'waka' => [
                'view_any_teacher_journal',
                'view_teacher_journal',
                'approve_teacher_journal',
                'view_any_student_assessment',
                'view_student_assessment',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
