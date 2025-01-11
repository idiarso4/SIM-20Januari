<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JurnalPkl extends Model
{
    protected $fillable = [
        'student_id',
        'tanggal',
        'kegiatan',
        'dokumentasi',
        'status',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
} 