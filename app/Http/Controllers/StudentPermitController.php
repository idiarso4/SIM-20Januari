<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class StudentPermitController extends Controller
{
    public function create()
    {
        try {
            $students = Student::with('class')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
            
            $teachers = Teacher::where('is_active', true)
                ->orderBy('name')
                ->get();
            
            return view('admin.student-permits.create', compact('students', 'teachers'));
        } catch (\Exception $e) {
            \Log::error('Error in StudentPermitController@create: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }
} 