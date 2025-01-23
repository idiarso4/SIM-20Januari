<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuruPiketSeeder extends Seeder
{
    public function run()
    {
        $guruPiket = [
            // Daftar guru KBM (diurutkan sesuai abjad)
            'Alfi Nur Hazizah, S.Pd.',
            'Catur Budi Satrio, S.Pd.',
            'Destriana Irmayasari, S.Pd.',
            'Dian Fidya Hastuti, S.Pd.',
            'Eko Budi Santoso, S.Pd, Gr.',
            'Endra Arif Apriyanto, S.Pd.',
            'Endah Yuliani, S.Pd.',
            'Faera Astuti, S.Pd.',
            'Fifin Oktarini, S.Pd.',
            'Idiarso, S.Kom.',
            'Jarwo, S.Pd.',
            'Kuntoro Triatmoko, S.Pd.',
            'Kuswanti, S.Pd.I',
            'M. Lutfi Hani Syukri, S.T.',
            'Ngaliman, S.Pd., M.M.',
            'Nurul Huda, S.T.',
            'Sri Herawan Kusuma, S.Kom',
            'Supriyana, S.Pd.',
            'Supriyani, S.Pd.',
            'Tutik Nurhayati, S.E.',
            'Wahyono, S.Pd.',
            'Wijayadi Muliawan, S.E.',

            // Daftar guru STP2K (diurutkan sesuai abjad)
            'Anggun Budiyawan, S.Pd.',
            'Aprilian Epti Wahyuni, S.Pd.',
            'Aris Rusman, S.Pd.',
            'Atun Istianah, SE.Akt.',
            'Bayu Indar Yunianto, S.Pd.',
            'Eko Santoso, S.Pd.',
            'Farah Kun Arifah, S.Pd.',
            'Gunawan Wibisono, S.Pd.',
            'Harni Nuril Fitri, S.Pd.',
            'Hermanto, S.Pd.',
            'Kiat Uji Purwani, S.Pd.',
            'Lia Dwi Arumsari, S.Pd.',
            'M. Iman Satria Gotama, S.Pd.',
            'Mohamad Yogi Prasetyo, S.Pd.',
            'Nining Yuni Prabawanti, S.E.',
            'Nursetyapamujiasih, S.Pd.',
            'Purwono, S.Pd.',
            'Rokhman, S.Pd.',
            'Sigit Prihantono, S.Pd.',
            'Syaifudin Aji Negara, S.Pd.',
            'Titis Sulistarini, S.Pd.',
            'Tri Haryadi, S.Pd.',
            'Umi Rofingah, S.Pd.',
            'Yan Indra Pratama, S.Pd.',
            'Yusuf Abdillah, S.Pd.',
        ];

        // Dapatkan role Guru Piket dan Guru
        $guruPiketRole = Role::where('name', 'Guru Piket')->first();
        $guruRole = Role::where('name', 'guru')->first();

        $newUsers = [];
        $existingUsers = [];

        foreach ($guruPiket as $nama) {
            $user = User::where('name', $nama)->first();
            
            if (!$user) {
                // Buat email dari nama (hapus gelar dan karakter khusus)
                $emailPrefix = Str::slug(
                    preg_replace('/,\s*S\..*$/', '', $nama), // Hapus gelar
                    '.' // Gunakan titik sebagai separator
                );
                
                // Buat user baru
                $user = User::create([
                    'name' => $nama,
                    'email' => $emailPrefix . '@smkn1punggelan.sch.id',
                    'password' => Hash::make('password123'), // Password default
                    'email_verified_at' => now(),
                ]);
                
                // Assign role guru
                $user->assignRole($guruRole);
                
                $newUsers[] = [
                    'nama' => $nama,
                    'email' => $emailPrefix . '@smkn1punggelan.sch.id'
                ];
            } else {
                $existingUsers[] = $nama;
            }

            // Assign role guru piket
            if (!$user->hasRole('Guru Piket')) {
                $user->assignRole($guruPiketRole);
            }
        }

        // Tampilkan ringkasan
        if (count($newUsers) > 0) {
            $this->command->info('User baru yang dibuat:');
            foreach ($newUsers as $user) {
                $this->command->line("- {$user['nama']}");
                $this->command->line("  Email: {$user['email']}");
            }
            $this->command->info('Password default untuk user baru: password123');
        }

        $this->command->info('Total: ' . count($existingUsers) . ' user existing, ' . count($newUsers) . ' user baru');
    }
} 