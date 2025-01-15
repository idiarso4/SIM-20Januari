<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExtracurricularActivity extends Model
{
    protected $fillable = [
        'tanggal',
        'extracurricular_id',
        'materi',
        'guru_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function extracurricular(): BelongsTo
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(ExtracurricularActivityAttendance::class);
    }
} 