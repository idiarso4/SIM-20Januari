<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtracurricularAttendance extends Model
{
    protected $fillable = [
        'extracurricular_activity_id',
        'student_id',
        'status',
        'keterangan',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(ExtracurricularActivity::class, 'extracurricular_activity_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
} 