<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function export() 
    {
        Log::info('Export started');
        try {
            $students = \App\Models\Student::all();
            Log::info('Found ' . $students->count() . ' students');
            
            return Excel::download(new StudentsExport, 'daftar-siswa.xlsx');
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
} 