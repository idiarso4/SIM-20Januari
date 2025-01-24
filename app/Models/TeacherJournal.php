<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherJournal extends Model
{
    protected $fillable = [
        'tanggal',
        'guru_id',
        'day',
        'mata_pelajaran',
        'kegiatan',
        'hasil',
        'hambatan',
        'pemecahan_masalah',
        'notes'
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'kelas_id');
    }
}
