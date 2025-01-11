<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherJournal extends Model
{
    protected $fillable = [
        'guru_id',
        'class_room_id',
        'mata_pelajaran',
        'materi',
        'tanggal',
        'jam_ke_mulai',
        'jam_ke_selesai',
        'jam_mulai',
        'jam_selesai',
        'media_dan_alat',
        'important_notes',
        'kegiatan',
        'hasil',
        'hambatan',
        'pemecahan_masalah',
        'status',
        'catatan_waka',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
