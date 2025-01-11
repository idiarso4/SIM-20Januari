<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Extracurricular extends Model
{
    protected $fillable = [
        'nama',
        'guru_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'deskripsi',
        'status',
        'tempat',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'status' => 'string',
    ];

    public function guru(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'extracurricular_teacher')
            ->whereHas('roles', function($query) {
                $query->where('name', 'guru');
            })
            ->withTimestamps();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ExtracurricularActivity::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'extracurricular_student')
            ->withTimestamps();
    }
} 