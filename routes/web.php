<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Presensi;
use App\Exports\AttendanceExport;
use App\Http\Controllers\ClassVerificationController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Livewire\Livewire;
use App\Http\Controllers\ImportTemplateController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\BackupController;
use App\Filament\Pages\CompleteProfile;
use App\Http\Controllers\Admin\StudentAssessmentController;

Route::group(['middleware' => 'auth'], function() {
    Route::get('presensi', Presensi::class)->name('presensi');
    Route::get('attendance/export', function () {
        return Excel::download(new AttendanceExport, 'attendances.xlsx');
    })->name('attendance-export');
    
    
});

Route::get('/login', function() {
    return redirect('admin/login');
})->name('user.login');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/verify-class/{id}', [ClassVerificationController::class, 'verify'])->name('verify.class');

Route::get('/api/generate-qr/{classId}', function($classId) {
    $timestamp = now();
    $token = hash('sha256', $classId . $timestamp . config('app.key'));
    
    return QrCode::size(150)->generate(route('verify.class', [
        'id' => $classId,
        'timestamp' => $timestamp,
        'token' => $token
    ]));
});

Route::get('/verify-permit/{id}', function($id) {
    $permit = \App\Models\StudentPermit::findOrFail($id);
    return view('verify-permit', compact('permit'));
})->name('verify-permit');

// QR Code routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Prayer QR Code
    Route::get('/prayer/qr/generate', [App\Http\Controllers\PrayerQrController::class, 'generate'])->name('prayer.qr.generate');
    Route::match(['get', 'post'], '/prayer/qr/validate/{token}', [App\Http\Controllers\PrayerQrController::class, 'validateToken'])->name('prayer.qr.validate');
    
    // Class QR Code
    Route::get('/class/qr/generate/{classId}/{subjectId}', [App\Http\Controllers\AttendanceQrController::class, 'generateClassQr'])->name('class.qr.generate');
    Route::match(['get', 'post'], '/class/qr/validate/{token}', [App\Http\Controllers\AttendanceQrController::class, 'validateClassToken'])->name('class.qr.validate');
    
    // Extracurricular QR Code
    Route::get('/extra/qr/generate/{extraId}', [App\Http\Controllers\AttendanceQrController::class, 'generateExtraQr'])->name('extra.qr.generate');
    Route::match(['get', 'post'], '/extra/qr/validate/{token}', [App\Http\Controllers\AttendanceQrController::class, 'validateExtraToken'])->name('extra.qr.validate');
    
    // Export Routes
    Route::get('/export/prayer-attendance', function () {
        return Excel::download(new App\Exports\PrayerAttendanceExport(
            request('start_date'),
            request('end_date')
        ), 'prayer-attendance.xlsx');
    })->name('export.prayer-attendance');
    
    Route::get('/export/class-attendance', function () {
        return Excel::download(new App\Exports\ClassAttendanceExport(
            request('class_id'),
            request('subject_id'),
            request('start_date'),
            request('end_date')
        ), 'class-attendance.xlsx');
    })->name('export.class-attendance');
    
    Route::get('/export/extra-attendance', function () {
        return Excel::download(new App\Exports\ExtracurricularAttendanceExport(
            request('extra_id'),
            request('start_date'),
            request('end_date')
        ), 'extra-attendance.xlsx');
    })->name('export.extra-attendance');
});

Route::get('/download-template-siswa', [ImportTemplateController::class, 'downloadStudentTemplate'])
    ->name('download.template.siswa');

Route::get('/download-template-guru', [ImportTemplateController::class, 'downloadGuruTemplate'])
    ->middleware(['auth'])
    ->name('download.template.guru');

Route::middleware(['auth'])->group(function () {
    Route::get('admin/students/export', [StudentController::class, 'export'])
        ->name('admin.students.export');
    Route::get('admin/class-rooms/export', [App\Http\Controllers\Admin\ClassRoomController::class, 'export'])
        ->name('admin.class-rooms.export');
});

Route::get('backups/{filename}/download', [BackupController::class, 'download'])
    ->name('filament.admin.resources.backups.download')
    ->middleware(['auth', 'verified']);

Route::name('filament.admin.pages.')
    ->prefix('admin')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('complete-profile', CompleteProfile::class)->name('complete-profile');
    });

Route::name('filament.admin.resources.student-assessments.')
    ->prefix('admin/student-assessments')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/create', [StudentAssessmentController::class, 'create'])->name('create');
        // ... route lainnya
    });