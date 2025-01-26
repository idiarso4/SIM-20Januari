<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPiket extends Model
{
    use HasFactory;

    protected $table = "jadwal_piket";
    
    protected $fillable = [
        "guru_id",
        "hari",
        "jam_mulai",
        "jam_selesai",
        "is_active",
    ];

    protected $casts = [
        "jam_mulai" => "datetime",
        "jam_selesai" => "datetime",
        "is_active" => "boolean",
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, "guru_id");
    }
}
