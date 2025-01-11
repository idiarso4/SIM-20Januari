<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'teaching_activity_id',
        'student_id',
        'status', // present, late, absent, sick, permit
        'keterangan'
    ];

    public function teachingActivity(): BelongsTo
    {
        return $this->belongsTo(TeachingActivity::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
