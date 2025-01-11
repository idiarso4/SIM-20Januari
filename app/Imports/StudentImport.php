<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class StudentImport extends DefaultValueBinder implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithCustomValueBinder, WithUpserts
{
    use SkipsErrors;

    protected $classRoomId;
    protected $processedEmails = [];

    public function __construct($classRoomId)
    {
        $this->classRoomId = $classRoomId;
    }

    public function uniqueBy()
    {
        return 'nis';
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() === 'A' && $cell->getRow() > 1) {
            $cell->setValueExplicit((string)$value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    protected function getOrCreateUser($data)
    {
        try {
            // Cek apakah email sudah diproses sebelumnya
            if (isset($this->processedEmails[$data['email']])) {
                return User::find($this->processedEmails[$data['email']]);
            }

            // Cek user berdasarkan email
            $existingUser = User::where('email', $data['email'])->first();
            
            if ($existingUser) {
                // Simpan ID user yang sudah ada
                $this->processedEmails[$data['email']] = $existingUser->id;
                
                // Update nama jika berbeda
                if ($existingUser->name !== $data['nama_lengkap']) {
                    $existingUser->name = $data['nama_lengkap'];
                    $existingUser->save();
                }
                
                return $existingUser;
            }

            // Buat user baru jika belum ada
            $newUser = User::create([
                'name' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make('password')
            ]);

            // Assign role siswa
            $newUser->assignRole('siswa');

            // Simpan ID user baru
            $this->processedEmails[$data['email']] = $newUser->id;

            return $newUser;
        } catch (\Exception $e) {
            throw new \Exception('Gagal membuat atau memperbarui user: ' . $e->getMessage());
        }
    }

    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            // Pastikan NIS selalu string
            $nis = (string)$row['nis'];
            
            // Dapatkan atau buat user
            $user = $this->getOrCreateUser($row);

            // Update atau buat student
            $student = Student::updateOrCreate(
                ['nis' => $nis],
                [
                    'nama_lengkap' => $row['nama_lengkap'],
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'agama' => $row['agama'],
                    'email' => $row['email'],
                    'telp' => $row['telp'] ?? null,
                    'class_room_id' => $this->classRoomId,
                    'user_id' => $user->id,
                ]
            );

            DB::commit();
            return $student;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat atau memperbarui student: ' . $e->getMessage());
        }
    }

    public function rules(): array
    {
        return [
            'nis' => ['required', 'string', 'max:255'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'agama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telp' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nis.required' => 'NIS wajib diisi',
            'nis.string' => 'NIS harus berupa teks',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'agama.required' => 'Agama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
        ];
    }
} 