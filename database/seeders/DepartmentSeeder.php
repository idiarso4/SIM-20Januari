<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Akuntansi dan keuangan Lembaga',
                'kode' => 'akl',
                'status' => true,
            ],
            [
                'name' => 'Pengembangan Perangkat Lunak dan Gim',
                'kode' => 'pplg',
                'status' => true,
            ],
            [
                'name' => 'Teknik Otomotif',
                'kode' => 'to',
                'status' => true,
            ],
            [
                'name' => 'Teknik Bodi Kendaraan Ringan',
                'kode' => 'tbkr',
                'status' => true,
            ],
            [
                'name' => 'Teknik Kendaraan Ringan',
                'kode' => 'tkr',
                'status' => true,
            ],
            [
                'name' => 'Sistem Informasi Jaringan dan Aplikasi',
                'kode' => 'sija',
                'status' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['kode' => $department['kode']],
                $department
            );
        }
    }
}
