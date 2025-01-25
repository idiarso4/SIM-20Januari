<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            'super_admin',
            'admin',
            'guru',
            'guru_piket',
            'wali_kelas',
            'kepala_sekolah',
            'wakil_kepala_sekolah',
            'staff_tu',
            'siswa'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create permissions
        $permissions = [
            // Basic permissions
            'view_dashboard',
            'manage_users',
            'manage_roles',
            
            // Academic permissions
            'manage_classes',
            'view_classes',
            'manage_students',
            'view_students',
            'manage_teachers',
            'view_teachers',
            'manage_subjects',
            'view_subjects',
            
            // Attendance permissions
            'manage_attendance',
            'view_attendance',
            'manage_piket',
            'view_piket',
            
            // Report permissions
            'export_class-room',
            'export_student',
            'generate_reports',
            'view_reports',
            
            // Administrative permissions
            'manage_school_settings',
            'manage_academic_year',
            'manage_departments'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $rolePermissions = [
            'super_admin' => $permissions, // Super admin gets all permissions
            
            'admin' => [
                'view_dashboard',
                'manage_users',
                'manage_classes',
                'view_classes',
                'manage_students',
                'view_students',
                'manage_teachers',
                'view_teachers',
                'manage_subjects',
                'view_subjects',
                'manage_attendance',
                'view_attendance',
                'export_class-room',
                'export_student',
                'generate_reports',
                'view_reports'
            ],
            
            'guru' => [
                'view_dashboard',
                'view_classes',
                'view_students',
                'view_subjects',
                'manage_attendance',
                'view_attendance',
                'view_reports'
            ],
            
            'guru_piket' => [
                'view_dashboard',
                'view_classes',
                'view_students',
                'manage_attendance',
                'view_attendance',
                'manage_piket',
                'view_piket'
            ],
            
            'wali_kelas' => [
                'view_dashboard',
                'view_classes',
                'manage_students',
                'view_students',
                'manage_attendance',
                'view_attendance',
                'generate_reports',
                'view_reports'
            ],
            
            'kepala_sekolah' => [
                'view_dashboard',
                'view_classes',
                'view_students',
                'view_teachers',
                'view_subjects',
                'view_attendance',
                'view_reports',
                'manage_school_settings'
            ],
            
            'wakil_kepala_sekolah' => [
                'view_dashboard',
                'view_classes',
                'view_students',
                'view_teachers',
                'view_subjects',
                'view_attendance',
                'view_reports'
            ],
            
            'staff_tu' => [
                'view_dashboard',
                'manage_students',
                'view_students',
                'export_class-room',
                'export_student',
                'generate_reports',
                'view_reports'
            ],
            
            'siswa' => [
                'view_dashboard',
                'view_attendance'
            ]
        ];

        foreach ($rolePermissions as $role => $permissions) {
            $role = Role::where('name', $role)->first();
            $role->syncPermissions($permissions);
        }
    }
}