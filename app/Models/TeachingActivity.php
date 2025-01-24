<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeachingActivity extends Model
{
    protected $fillable = [
        'tanggal',
        'guru_id',
        'kelas_id',
        'mata_pelajaran',
        'materi',
        'jam_ke_mulai',
        'jam_ke_selesai',
        'media_dan_alat',
        'attendances',
        'important_notes',
        'jam_ke',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'attendances' => 'array'
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'kelas_id');
    }

    public static function toCsv($activities)
    {
        $headers = [
            'Tanggal',
            'Kelas',
            'Mata Pelajaran',
            'Materi',
            'Jam Ke',
            'Sampai Jam Ke',
            'Jumlah Hadir',
            'Jumlah Sakit',
            'Jumlah Izin', 
            'Jumlah Alpha',
            'Jumlah Dispensasi',
            'Media dan Alat',
            'Catatan Kejadian Penting'
        ];

        $handle = fopen('php://temp', 'r+');
        
        // Add BOM for Excel UTF-8 compatibility
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Write headers
        fputcsv($handle, $headers);
        
        // Write data rows
        foreach ($activities as $activity) {
            $row = [
                $activity->tanggal->format('d/m/Y'),
                $activity->kelas->name,
                $activity->mata_pelajaran,
                $activity->materi,
                $activity->jam_ke_mulai,
                $activity->jam_ke_selesai,
                collect($activity->attendances)->where('status', 'Hadir')->count(),
                collect($activity->attendances)->where('status', 'Sakit')->count(),
                collect($activity->attendances)->where('status', 'Izin')->count(),
                collect($activity->attendances)->where('status', 'Alpha')->count(),
                collect($activity->attendances)->where('status', 'Dispensasi')->count(),
                $activity->media_dan_alat,
                $activity->important_notes
            ];
            
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return $csv;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Cek duplikasi hanya untuk record lain
            $exists = static::where('guru_id', $model->guru_id)
                ->where('tanggal', $model->tanggal)
                ->where('jam_ke_mulai', $model->jam_ke_mulai)
                ->where('id', '!=', $model->id)
                ->exists();

            if ($exists) {
                throw new \Exception('Jadwal mengajar untuk guru, tanggal dan jam ini sudah ada.');
            }
        });
    }
} 