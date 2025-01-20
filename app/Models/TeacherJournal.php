<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherJournal extends Model
{
    protected $fillable = [
        'tanggal',
        'guru_id',
        'kegiatan',
        'hasil',
        'hambatan',
        'pemecahan_masalah',
        'mata_pelajaran',
        'materi',
        'jam_mulai',
        'jam_selesai'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
