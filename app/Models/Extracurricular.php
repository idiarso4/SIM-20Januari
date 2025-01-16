<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Extracurricular extends Model
{
    protected $fillable = [
        'nama',
        'description',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tempat',
        'deskripsi',
        'status'
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ExtracurricularActivity::class);
    }

    public function guru(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'extracurricular_guru', 'extracurricular_id', 'user_id');
    }
} 