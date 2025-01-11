<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class RestoreBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function handle()
    {
        try {
            $backupPath = Storage::disk('backup')->path($this->filename);
            
            // Extract backup file
            $zip = new \ZipArchive;
            $zip->open($backupPath);
            $zip->extractTo(storage_path('app/restore-temp'));
            $zip->close();

            // Get SQL file
            $sqlFile = storage_path('app/restore-temp/db-dumps/mysql-attendance.sql');
            
            if (file_exists($sqlFile)) {
                // Execute SQL restore
                DB::unprepared(file_get_contents($sqlFile));
            }

            // Clean up
            \File::deleteDirectory(storage_path('app/restore-temp'));

        } catch (Exception $e) {
            \Log::error('Restore failed: ' . $e->getMessage());
            throw $e;
        }
    }
} 