<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Contoh data dengan beberapa baris
        return [
            [
                '100110011001', // nis
                '200110011001', // nisn
                'John Doe', // nama_lengkap
                'john@siswa.com', // email
                'L', // jenis_kelamin
                'Jakarta', // tempat_lahir
                '2006-08-20', // tanggal_lahir
                'Islam', // agama
                'Jl. Example No. 1', // alamat
                '08123456789', // telp
                'SIJA', // jurusan
                'X SIJA 3', // kelas
                '2024', // tahun_masuk
                'aktif', // status
                '', // profile_link
                'John Sr', // nama_ayah
                'Wiraswasta', // pekerjaan_ayah
                'Jane', // nama_ibu
                'IRT', // pekerjaan_ibu
                'Jl. Example No. 1', // alamat_ortu
                '08123456788', // telp_ortu
            ],
            [
                '100110011002', // nis
                '200110011002', // nisn
                'Jane Smith', // nama_lengkap
                'jane@siswa.com', // email
                'P', // jenis_kelamin
                'Bandung', // tempat_lahir
                '2006-09-15', // tanggal_lahir
                'Islam', // agama
                'Jl. Example No. 2', // alamat
                '08123456790', // telp
                'TKR', // jurusan
                'X TKR 1', // kelas
                '2024', // tahun_masuk
                'aktif', // status
                '', // profile_link
                'Smith Sr', // nama_ayah
                'Karyawan', // pekerjaan_ayah
                'Sarah', // nama_ibu
                'Guru', // pekerjaan_ibu
                'Jl. Example No. 2', // alamat_ortu
                '08123456789', // telp_ortu
            ],
            [
                '100110011003', // nis
                '200110011003', // nisn
                'Bob Wilson', // nama_lengkap
                'bob@siswa.com', // email
                'L', // jenis_kelamin
                'Surabaya', // tempat_lahir
                '2006-10-25', // tanggal_lahir
                'Kristen', // agama
                'Jl. Example No. 3', // alamat
                '08123456791', // telp
                'TKR', // jurusan
                'X TKR 2', // kelas
                '2024', // tahun_masuk
                'aktif', // status
                '', // profile_link
                'Wilson Sr', // nama_ayah
                'Pengusaha', // pekerjaan_ayah
                'Mary', // nama_ibu
                'Dokter', // pekerjaan_ibu
                'Jl. Example No. 3', // alamat_ortu
                '08123456790', // telp_ortu
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'nis',
            'nisn',
            'nama_lengkap',
            'email',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'agama',
            'alamat',
            'telp',
            'jurusan',
            'kelas',
            'tahun_masuk',
            'status',
            'profile_link',
            'nama_ayah',
            'pekerjaan_ayah',
            'nama_ibu',
            'pekerjaan_ibu',
            'alamat_ortu',
            'telp_ortu'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:U1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
        ]);

        // Tambahkan validasi data
        $sheet->getCell('E2')->getDataValidation()
            ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(false)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Input error')
            ->setError('Nilai tidak valid')
            ->setPromptTitle('Pilih')
            ->setPrompt('Pilih L atau P')
            ->setFormula1('"L,P"');

        $sheet->getCell('H2')->getDataValidation()
            ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(false)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Input error')
            ->setError('Nilai tidak valid')
            ->setPromptTitle('Pilih')
            ->setPrompt('Pilih agama')
            ->setFormula1('"Islam,Kristen,Katolik,Hindu,Buddha,Konghucu"');

        $sheet->getCell('K2')->getDataValidation()
            ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(false)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Input error')
            ->setError('Nilai tidak valid')
            ->setPromptTitle('Pilih')
            ->setPrompt('Pilih jurusan')
            ->setFormula1('"TKRO,TBO,AKL,SIJA"');

        $sheet->getCell('N2')->getDataValidation()
            ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(false)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Input error')
            ->setError('Nilai tidak valid')
            ->setPromptTitle('Pilih')
            ->setPrompt('Pilih status')
            ->setFormula1('"aktif,tidak aktif,lulus,pindah"');

        // Tambahkan keterangan untuk profile_link
        $sheet->getComment('O1')->getText()->createTextRun('Opsional. Jika diisi harus berupa URL lengkap diawali dengan https://');

        // Tambahkan komentar yang lebih detail untuk kolom kelas
        $sheet->getComment('L1')->getText()->createTextRun(
            "Format nama kelas harus sesuai dengan yang ada di database.\n" .
            "Format: [TINGKAT] [JURUSAN] [NOMOR]\n" .
            "Contoh yang valid:\n" .
            "- X SIJA 3\n" .
            "- X TKR 1\n" .
            "- XI SIJA 2\n" .
            "PENTING: Perhatikan spasi dan huruf besar kecil"
        );

        // Update komentar untuk kolom jurusan
        $sheet->getComment('K1')->getText()->createTextRun(
            "Gunakan kode jurusan berikut:\n" .
            "- SIJA untuk Sistem Informasi Jaringan dan Aplikasi\n" .
            "- TKR untuk Teknik Kendaraan Ringan\n" .
            "- TBO untuk Teknik Bodi Otomotif\n" .
            "- AKL untuk Akuntansi dan Keuangan Lembaga"
        );

        // Auto size columns
        foreach(range('A','U') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 