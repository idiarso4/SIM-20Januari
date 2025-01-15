<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAssessment extends Model
{
    public const TYPE_SUMATIF = 'Sumatif';
    public const TYPE_NON_SUMATIF = 'Non-Sumatif';

    public const TYPE_OPTIONS = [
        self::TYPE_SUMATIF => 'Sumatif',
        self::TYPE_NON_SUMATIF => 'Non-Sumatif',
    ];

    public const CATEGORY_TEORI = 'Teori';
    public const CATEGORY_PRAKTIK = 'Praktik';

    public const CATEGORY_OPTIONS = [
        self::CATEGORY_TEORI => 'Teori',
        self::CATEGORY_PRAKTIK => 'Praktik',
    ];

    public const JENIS_OPTIONS = [
        'Sumatif' => 'Sumatif',
        'Non-Sumatif' => 'Non-Sumatif'
    ];

    public const KATEGORI_OPTIONS = [
        'Teori' => 'Teori',
        'Praktik' => 'Praktik'
    ];

    protected $fillable = [
        'tanggal',
        'guru_id',
        'class_room_id',
        'mata_pelajaran',
        'jenis',
        'kategori',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function details()
    {
        return $this->hasMany(StudentAssessmentDetail::class);
    }
}
