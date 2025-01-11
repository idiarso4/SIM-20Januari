<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\ClassRoomsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ClassRoomController extends Controller
{
    public function export() 
    {
        try {
            return Excel::download(new ClassRoomsExport, 'daftar-kelas.xlsx');
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengexport data');
        }
    }
} 