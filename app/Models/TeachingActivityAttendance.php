<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeachingActivityAttendance extends Model
{
    protected $fillable = [
        'teaching_activity_id',
        'student_id',
        'status',
        'keterangan'
    ];

    public function teachingActivity(): BelongsTo
    {
        return $this->belongsTo(TeachingActivity::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
} 