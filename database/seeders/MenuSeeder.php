<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Dashboard
            [
                'name' => 'Dashboard',
                'icon' => 'heroicon-o-home',
                'route' => 'filament.admin.pages.dashboard',
                'order' => 1,
            ],

            // Master Data (parent_id: null, group_id: 2)
            [
                'name' => 'Master Data',
                'icon' => 'heroicon-o-circle-stack',
                'order' => 2,
            ],
            // Sub-items Master Data
            [
                'name' => 'Jurusan',
                'icon' => 'heroicon-o-academic-cap',
                'route' => 'filament.admin.resources.departments.index',
                'order' => 1,
                'group_id' => 2,
            ],
            [
                'name' => 'Kelas',
                'icon' => 'heroicon-o-building-office-2',
                'route' => 'filament.admin.resources.class-rooms.index',
                'order' => 2,
                'group_id' => 2,
            ],
            [
                'name' => 'Mata Pelajaran',
                'icon' => 'heroicon-o-book-open',
                'route' => 'filament.admin.resources.subjects.index',
                'order' => 3,
                'group_id' => 2,
            ],

            // Akademik (parent_id: null, group_id: 3)
            [
                'name' => 'Akademik',
                'icon' => 'heroicon-o-academic-cap',
                'order' => 3,
            ],
            // Sub-items Akademik
            [
                'name' => 'Kegiatan Mengajar',
                'icon' => 'heroicon-o-presentation-chart-line',
                'route' => 'filament.admin.resources.teaching-activities.index',
                'order' => 1,
                'group_id' => 3,
            ],
            [
                'name' => 'Ekstrakurikuler',
                'icon' => 'heroicon-o-star',
                'route' => 'filament.admin.resources.extracurriculars.index',
                'order' => 2,
                'group_id' => 3,
            ],
            [
                'name' => 'Kegiatan Ekstrakurikuler',
                'icon' => 'heroicon-o-clipboard-document-list',
                'route' => 'filament.admin.resources.extracurricular-activities.index',
                'order' => 3,
                'group_id' => 3,
            ],
            [
                'name' => 'Jurnal Guru',
                'icon' => 'heroicon-o-document-text',
                'route' => 'filament.admin.resources.teacher-journals.index',
                'order' => 4,
                'group_id' => 3,
            ],
            [
                'name' => 'Penilaian',
                'icon' => 'heroicon-o-clipboard-document-check',
                'route' => 'filament.admin.resources.student-assessments.index',
                'order' => 5,
                'group_id' => 3,
            ],

            // Kesiswaan (parent_id: null, group_id: 4)
            [
                'name' => 'Kesiswaan',
                'icon' => 'heroicon-o-users',
                'order' => 4,
            ],
            // Sub-items Kesiswaan
            [
                'name' => 'Data Siswa',
                'icon' => 'heroicon-o-user-group',
                'route' => 'filament.admin.resources.students.index',
                'order' => 1,
                'group_id' => 4,
            ],
            [
                'name' => 'Mutasi',
                'icon' => 'heroicon-o-arrows-right-left',
                'route' => 'filament.admin.resources.mutations.index',
                'order' => 2,
                'group_id' => 4,
            ],
            [
                'name' => 'Perizinan',
                'icon' => 'heroicon-o-document-check',
                'route' => 'filament.admin.resources.student-permits.index',
                'order' => 3,
                'group_id' => 4,
            ],

            // BK (parent_id: null, group_id: 5)
            [
                'name' => 'BK',
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'order' => 5,
            ],
            // Sub-items BK
            [
                'name' => 'Konseling',
                'icon' => 'heroicon-o-chat-bubble-oval-left-ellipsis',
                'route' => 'filament.admin.resources.counselings.index',
                'order' => 1,
                'group_id' => 5,
            ],
            [
                'name' => 'Home Visit',
                'icon' => 'heroicon-o-home',
                'route' => 'filament.admin.resources.home-visits.index',
                'order' => 2,
                'group_id' => 5,
            ],
            [
                'name' => 'Bimbingan Karir',
                'icon' => 'heroicon-o-briefcase',
                'route' => 'filament.admin.resources.career-guidances.index',
                'order' => 3,
                'group_id' => 5,
            ],

            // Pengaturan (parent_id: null, group_id: 6)
            [
                'name' => 'Pengaturan',
                'icon' => 'heroicon-o-cog-6-tooth',
                'order' => 6,
            ],
            // Sub-items Pengaturan
            [
                'name' => 'Manajemen Pengguna',
                'icon' => 'heroicon-o-users',
                'route' => 'filament.admin.resources.users.index',
                'order' => 1,
                'group_id' => 6,
            ],
            [
                'name' => 'Hak Akses',
                'icon' => 'heroicon-o-key',
                'route' => 'filament.admin.resources.roles.index',
                'order' => 2,
                'group_id' => 6,
            ],
            [
                'name' => 'Backup & Restore',
                'icon' => 'heroicon-o-arrow-down-tray',
                'route' => 'filament.admin.resources.backups.index',
                'order' => 3,
                'group_id' => 6,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
} 