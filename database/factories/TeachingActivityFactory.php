<?php

namespace Database\Factories;

use App\Models\TeachingActivity;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeachingActivityFactory extends Factory
{
    protected $model = TeachingActivity::class;

    public function definition()
    {
        $jam_ke_mulai = $this->faker->numberBetween(1, 8);
        $jam_mulai = sprintf('%02d:00:00', 7 + $jam_ke_mulai);
        $jam_selesai = sprintf('%02d:45:00', 7 + $jam_ke_mulai);
        
        return [
            'guru_id' => User::factory()->guru(),
            'class_room_id' => ClassRoom::factory(),
            'mata_pelajaran' => $this->faker->randomElement([
                'Matematika',
                'Bahasa Indonesia',
                'Bahasa Inggris',
                'Fisika',
                'Kimia',
                'Biologi',
                'Sejarah',
                'Geografi',
                'Ekonomi',
                'Sosiologi'
            ]),
            'tanggal' => $this->faker->date(),
            'jam_ke_mulai' => $jam_ke_mulai,
            'jam_ke_selesai' => $jam_ke_mulai + $this->faker->numberBetween(1, 2),
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'materi' => $this->faker->paragraph(),
            'media_dan_alat' => $this->faker->optional()->sentence(),
            'important_notes' => $this->faker->optional()->paragraph()
        ];
    }
} 