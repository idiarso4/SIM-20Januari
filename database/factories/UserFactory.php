<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $this->faker->locale('id_ID');
        
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'user_type' => 'siswa',
            'status' => 'aktif',
        ];
    }

    public function guru()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'guru',
            ];
        });
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'admin',
            ];
        });
    }

    public function siswa()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'siswa',
            ];
        });
    }
}
