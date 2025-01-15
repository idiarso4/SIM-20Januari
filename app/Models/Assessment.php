<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessment extends Model
{
    protected $fillable = [
        'class_room_id',
        'guru_id',
        'mata_pelajaran',
        'kompetensi_dasar',
        'jenis_penilaian',
        'bobot',
        'tanggal',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'bobot' => 'integer'
    ];

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
} 