<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentExportController extends Controller
{
    public function __invoke()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }
}
