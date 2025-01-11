<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtracurricularSupervisor extends Model
{
    protected $fillable = [
        'extracurricular_id',
        'guru_id',
        'is_main_supervisor',
        'notes'
    ];

    protected $casts = [
        'is_main_supervisor' => 'boolean'
    ];

    public function extracurricular(): BelongsTo
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
} 