<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtracurricularActivityAttendance extends Model
{
    // 1. Konstanta untuk status/opsi
    public const STATUS_HADIR = 'Hadir';
    public const STATUS_SAKIT = 'Sakit';
    public const STATUS_IZIN = 'Izin';
    public const STATUS_ALPHA = 'Alpha';

    public const STATUS_OPTIONS = [
        self::STATUS_HADIR => 'Hadir',
        self::STATUS_SAKIT => 'Sakit',
        self::STATUS_IZIN => 'Izin',
        self::STATUS_ALPHA => 'Alpha',
    ];

    public const STATUS_COLORS = [
        self::STATUS_HADIR => 'success',
        self::STATUS_SAKIT => 'warning',
        self::STATUS_IZIN => 'info',
        self::STATUS_ALPHA => 'danger',
    ];

    // 2. Fillable & Casts
    protected $fillable = [
        'extracurricular_activity_id',
        'student_id',
        'status',
        'keterangan'
    ];

    // 3. Relationships
    public function activity(): BelongsTo
    {
        return $this->belongsTo(ExtracurricularActivity::class, 'extracurricular_activity_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
} 