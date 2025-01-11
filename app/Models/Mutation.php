<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tanggal',
        'jenis', // masuk/keluar
        'alasan',
        'sekolah_asal',
        'sekolah_tujuan',
        'status'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
} 