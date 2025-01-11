<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class MajorTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return new Collection([
            [
                'nama_kelas' => 'X TKRO 1',
                'tingkat' => 'X',
                'jurusan' => 'TEKNIK KENDARAAN RINGAN OTOMOTIF',
                'tahun_pelajaran' => '2023/2024',
                'semester' => 'Ganjil',
                'status' => 'aktif'
            ],
            [
                'nama_kelas' => 'X TBO 1',
                'tingkat' => 'X',
                'jurusan' => 'TEKNIK BODI OTOMOTIF',
                'tahun_pelajaran' => '2023/2024',
                'semester' => 'Ganjil',
                'status' => 'aktif'
            ],
            [
                'nama_kelas' => 'X AKL 1',
                'tingkat' => 'X',
                'jurusan' => 'AKUNTANSI KEUANGAN DAN LEMBAGA',
                'tahun_pelajaran' => '2023/2024',
                'semester' => 'Ganjil',
                'status' => 'aktif'
            ],
            [
                'nama_kelas' => 'X SIJA 1',
                'tingkat' => 'X',
                'jurusan' => 'SISTEM INFORMATIKA JARINGAN DAN APLIKASI',
                'tahun_pelajaran' => '2023/2024',
                'semester' => 'Ganjil',
                'status' => 'aktif'
            ],
            [
                'nama_kelas' => 'XI TKRO 1',
                'tingkat' => 'XI',
                'jurusan' => 'TEKNIK KENDARAAN RINGAN OTOMOTIF',
                'tahun_pelajaran' => '2023/2024',
                'semester' => 'Ganjil',
                'status' => 'aktif'
            ],
            [
                'nama_kelas' => 'XII SIJA 1',
                'tingkat' => 'XII',
                'jurusan' => 'SISTEM INFORMATIKA JARINGAN DAN APLIKASI',
                'tahun_pelajaran' => '2023/2024',
                'semester' => 'Ganjil',
                'status' => 'aktif'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'nama_kelas',
            'tingkat',
            'jurusan',
            'tahun_pelajaran',
            'semester',
            'status'
        ];
    }
} 