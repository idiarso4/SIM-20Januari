<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAttendance extends Model
{
    const STATUS_HADIR = 'hadir';
    const STATUS_IZIN = 'izin';
    const STATUS_SAKIT = 'sakit';
    const STATUS_ALPHA = 'alpha';
    const STATUS_DISPENSASI = 'dispensasi';

    protected $fillable = [
        'teaching_activity_id',
        'extracurricular_activity_id',
        'student_id',
        'class_room_id',
        'tanggal',
        'status',
        'keterangan',
        'class_room_id',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'string',
        'tanggal' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->status = static::normalizeStatus($model->status);
        });
    }

    protected static function normalizeStatus($status)
    {
        return match(strtolower($status)) {
            'present', 'hadir' => self::STATUS_HADIR,
            'permit', 'izin' => self::STATUS_IZIN,
            'sick', 'sakit' => self::STATUS_SAKIT,
            'absent', 'alpha' => self::STATUS_ALPHA,
            'dispensation', 'dispensasi' => self::STATUS_DISPENSASI,
            default => self::STATUS_HADIR,
        };
    }

    public function teachingActivity(): BelongsTo
    {
        return $this->belongsTo(TeachingActivity::class);
    }

    public function extracurricularActivity(): BelongsTo
    {
        return $this->belongsTo(ExtracurricularActivity::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
