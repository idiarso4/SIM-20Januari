<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\GuruResource;
use App\Http\Controllers\Admin\StudentExportController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', function() {
    return redirect('/admin/login');
})->name('login');

Route::get('/download/template/guru', [GuruResource::class, 'generateTemplate'])
    ->name('download.template.guru');

Route::get('/admin/students/export', StudentExportController::class)
    ->middleware(['auth'])
    ->name('admin.students.export');

Route::post('/admin/users/bulk-update', [App\Http\Controllers\Admin\UserController::class, 'bulkUpdate'])
    ->name('admin.users.bulk-update')
    ->middleware(['auth', 'admin']);