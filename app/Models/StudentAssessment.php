<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAssessment extends Model
{
    protected $fillable = [
        'student_id',
        'guru_id',
        'class_room_id',
        'teacher_journal_id',
        'mata_pelajaran',
        'jenis_penilaian',
        'attempt',
        'kompetensi_dasar',
        'tanggal',
        'nilai',
        'deskripsi',
        'catatan_guru',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nilai' => 'decimal:2',
        'attempt' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function teacherJournal(): BelongsTo
    {
        return $this->belongsTo(TeacherJournal::class);
    }
}
