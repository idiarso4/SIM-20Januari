<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GuruImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function model(array $row)
    {
        // Skip if required fields are empty
        if (empty($row['nama_lengkap']) || empty($row['email'])) {
            return null;
        }

        // Log the incoming data for debugging
        Log::info('Importing row data:', $row);

        DB::beginTransaction();
        try {
            // Create the user
            $user = User::create([
                'name' => $row['nama_lengkap'],
                'email' => $row['email'],
                'nip' => $row['nip'] ?? null,
                'phone' => $row['no_telepon'] ?? null,
                'password' => Hash::make($row['password'] ?? 'password123'),
            ]);

            // Assign the guru role
            $guruRole = Role::firstOrCreate(['name' => 'guru']);
            $user->assignRole($guruRole);

            DB::commit();
            Log::info('Successfully imported user:', ['id' => $user->id, 'name' => $user->name]);
            
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing guru:', [
                'error' => $e->getMessage(),
                'row' => $row
            ]);
            throw $e;
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
