<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function download($filename): StreamedResponse
    {
        $path = 'backups/' . $filename;
        
        if (!Storage::exists($path)) {
            abort(404, 'File backup tidak ditemukan');
        }

        return Storage::download($path, $filename, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
} 