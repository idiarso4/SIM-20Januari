<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, Notifiable, HasRoles, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // You can add specific conditions here later
    }

    public function extracurriculars()
    {
        return $this->hasMany(Extracurricular::class, 'guru_id');
    }

    public function teacherDuties()
    {
        return $this->hasMany(TeacherDuty::class, 'teacher_id');
    }
}