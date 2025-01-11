<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PklInternship extends Model
{
    use HasFactory;

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

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function guruPembimbing()
    {
        return $this->belongsTo(User::class, 'guru_pembimbing_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
} 