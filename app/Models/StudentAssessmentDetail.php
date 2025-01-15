<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAssessmentDetail extends Model
{
    protected $fillable = [
        'student_assessment_id',
        'student_id',
        'nilai',
        'keterangan'
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(StudentAssessment::class, 'student_assessment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
} 