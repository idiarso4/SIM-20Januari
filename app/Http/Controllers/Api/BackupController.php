<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Exception;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin']);  // Pastikan hanya admin yang bisa akses
    }

    public function backup()
    {
        try {
            // Nama file backup dengan timestamp
            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s');

            // Jalankan command backup database
            Artisan::call('backup:run', [
                '--only-db' => true,
                '--filename' => $filename
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully',
                'data' => [
                    'filename' => $filename,
                    'created_at' => Carbon::now()->toDateTimeString()
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backup failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getBackupFiles()
    {
        try {
            $files = Storage::disk('backup')->files();
            $backups = [];

            foreach ($files as $file) {
                if (substr($file, -4) === '.zip') {
                    $backups[] = [
                        'filename' => $file,
                        'size' => Storage::disk('backup')->size($file),
                        'last_modified' => Carbon::createFromTimestamp(
                            Storage::disk('backup')->lastModified($file)
                        )->toDateTimeString()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Backup files retrieved successfully',
                'data' => $backups
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve backup files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function restore(Request $request)
    {
        try {
            $request->validate([
                'filename' => 'required|string'
            ]);

            $filename = $request->filename;

            if (!Storage::disk('backup')->exists($filename)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found'
                ], 404);
            }

            // Jalankan proses restore dalam queue
            dispatch(new \App\Jobs\RestoreBackup($filename));

            return response()->json([
                'success' => true,
                'message' => 'Restore process has been queued',
                'data' => [
                    'filename' => $filename
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Restore failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function download($filename)
    {
        try {
            if (!Storage::disk('backup')->exists($filename)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found'
                ], 404);
            }

            return Storage::disk('backup')->download($filename);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Download failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($filename)
    {
        try {
            if (!Storage::disk('backup')->exists($filename)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found'
                ], 404);
            }

            Storage::disk('backup')->delete($filename);

            return response()->json([
                'success' => true,
                'message' => 'Backup file deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 