<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\LeaveController;
use App\Http\Controllers\API\BackupController;

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/get-attendance-today',[AttendanceController::class, 'getAttendanceToday'])->name('get_attendance_today');
    Route::get('/get-schedule', [AttendanceController::class, 'getSchedule'])->name('get_schedule');
    Route::post('/store-attendance', [AttendanceController::class, 'store'])->name('store_attendance');
    Route::get('/get-attendance-by-month-year/{month}/{year}', [AttendanceController::class, 'getAttendanceByMonthAndYear'])->name('get_attendance_by_month_and_year');
    Route::post('/banned', [AttendanceController::class, 'banned'])->name('banned');
    Route::get('/get-photo', [AttendanceController::class, 'getPhoto'])->name('get_photo');

    Route::get('/leaves', [LeaveController::class, 'index'])->name('get_leave');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('create_leave');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['throttle:60,1'])->group(function () {
    // API routes here
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::prefix('backup')->group(function () {
        Route::post('create', [BackupController::class, 'backup']);
        Route::get('files', [BackupController::class, 'getBackupFiles']);
        Route::post('restore', [BackupController::class, 'restore']);
        Route::get('download/{filename}', [BackupController::class, 'download']);
        Route::delete('delete/{filename}', [BackupController::class, 'delete']);
    });
});
