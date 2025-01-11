<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PklInternship extends Model
{
    protected $fillable = [
        'student_id',
        'user_id',
        'guru_pembimbing_id',
        'office_id',
        'company_leader',
        'company_type',
        'company_phone',
        'company_description',
        'start_date',
        'end_date',
        'position',
        'phone',
        'description',
        'status'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guruPembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_pembimbing_id');
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
} 