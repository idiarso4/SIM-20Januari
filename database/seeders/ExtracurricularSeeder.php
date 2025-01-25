<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Extracurricular;

class ExtracurricularSeeder extends Seeder
{
    public function run(): void
    {
        $extracurriculars = [
            [
                'nama' => 'Pramuka',
                'deskripsi' => 'Kegiatan kepramukaan untuk membentuk karakter dan keterampilan',
                'hari' => 'Jumat',
                'jam_mulai' => '14:00',
                'jam_selesai' => '16:00',
                'tempat' => 'Lapangan Sekolah',
                'status' => true,
            ],
            [
                'nama' => 'Futsal',
                'deskripsi' => 'Olahraga futsal untuk meningkatkan kebugaran dan kerjasama tim',
                'hari' => 'Selasa',
                'jam_mulai' => '15:00',
                'jam_selesai' => '17:00',
                'tempat' => 'Lapangan Futsal',
                'status' => true,
            ],
            [
                'nama' => 'Basket',
                'deskripsi' => 'Olahraga basket untuk meningkatkan kebugaran dan kerjasama tim',
                'hari' => 'Rabu',
                'jam_mulai' => '15:00',
                'jam_selesai' => '17:00',
                'tempat' => 'Lapangan Basket',
                'status' => true,
            ],
            [
                'nama' => 'PMR',
                'deskripsi' => 'Palang Merah Remaja untuk belajar pertolongan pertama dan kesehatan',
                'hari' => 'Kamis',
                'jam_mulai' => '14:00',
                'jam_selesai' => '16:00',
                'tempat' => 'Ruang UKS',
                'status' => true,
            ],
            [
                'nama' => 'English Club',
                'deskripsi' => 'Klub bahasa Inggris untuk meningkatkan kemampuan berbahasa',
                'hari' => 'Senin',
                'jam_mulai' => '14:00',
                'jam_selesai' => '16:00',
                'tempat' => 'Ruang Bahasa',
                'status' => true,
            ],
            [
                'nama' => 'Robotika',
                'deskripsi' => 'Pembelajaran dan praktik robotika dan pemrograman',
                'hari' => 'Rabu',
                'jam_mulai' => '14:00',
                'jam_selesai' => '16:00',
                'tempat' => 'Lab Komputer',
                'status' => true,
            ],
            [
                'nama' => 'Paduan Suara',
                'deskripsi' => 'Kegiatan bernyanyi dan paduan suara',
                'hari' => 'Selasa',
                'jam_mulai' => '14:00',
                'jam_selesai' => '16:00',
                'tempat' => 'Ruang Musik',
                'status' => true,
            ],
            [
                'nama' => 'Seni Tari',
                'deskripsi' => 'Pembelajaran tarian tradisional dan modern',
                'hari' => 'Jumat',
                'jam_mulai' => '15:00',
                'jam_selesai' => '17:00',
                'tempat' => 'Ruang Tari',
                'status' => true,
            ],
            [
                'nama' => 'Pencak Silat',
                'deskripsi' => 'Seni bela diri tradisional Indonesia',
                'hari' => 'Senin',
                'jam_mulai' => '15:00',
                'jam_selesai' => '17:00',
                'tempat' => 'Aula Sekolah',
                'status' => true,
            ],
            [
                'nama' => 'Karya Ilmiah Remaja',
                'deskripsi' => 'Penelitian dan pengembangan karya ilmiah',
                'hari' => 'Kamis',
                'jam_mulai' => '14:00',
                'jam_selesai' => '16:00',
                'tempat' => 'Perpustakaan',
                'status' => true,
            ],
        ];

        foreach ($extracurriculars as $extracurricular) {
            Extracurricular::create($extracurricular);
        }
    }
}
