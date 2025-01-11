<?php

namespace App\Filament\Imports;

use App\Models\Student;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImporter extends Importer implements ToModel, WithHeadingRow
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nis')
                ->label('NIS')
                ->required()
                ->example('2024001'),
            ImportColumn::make('nama_lengkap')
                ->label('Nama Lengkap')
                ->required()
                ->example('Budi Santoso'),
            ImportColumn::make('jenis_kelamin')
                ->label('Jenis Kelamin (L/P)')
                ->required()
                ->rules(['in:L,P'])
                ->example('L'),
            ImportColumn::make('agama')
                ->label('Agama')
                ->required()
                ->example('Islam'),
            ImportColumn::make('email')
                ->label('Email')
                ->required()
                ->rules(['email'])
                ->example('budi.santoso@gmail.com'),
            ImportColumn::make('telp')
                ->label('No. Telepon')
                ->example('081234567890'),
            ImportColumn::make('class_room_id')
                ->label('ID Kelas')
                ->required()
                ->example('1'),
        ];
    }

    public function model(array $row)
    {
        return new Student([
            'nis' => $row['nis'],
            'nama_lengkap' => $row['nama_lengkap'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'agama' => $row['agama'],
            'email' => $row['email'],
            'telp' => $row['telp'] ?? null,
            'class_room_id' => $row['class_room_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import data siswa selesai dan ' . number_format($import->successful_rows) . ' ' . str('data')->plural($import->successful_rows) . ' berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('data')->plural($failedRowsCount) . ' gagal diimport.';
        }

        return $body;
    }
}
