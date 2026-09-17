<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;


Route::get('/', function () {
    if (Auth::check()) {
    if (Auth::user()->role === 'admin') { 
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('attendance.scan.view');
    }
    return redirect()->route('login');
});

// 1. RUTE GUEST (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// 2. RUTE GLOBAL (Sudah Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==========================================
    // KARYAWAN
    // ==========================================
    Route::middleware('role:karyawan')->group(function () {
        Route::get('/attendance/scan', function () {
            return view('presensi.ScanQr');
        })->name('attendance.scan.view');
        
        Route::post('/attendance/scan', [AttendanceController::class, 'store'])
            ->name('attendance.scan');
    });

    // ==========================================
    // ADMIN / HRD
    // ==========================================
    Route::middleware('role:admin')->group(function () {

        Route::get('/admin/qr', function () {
            return view('presensi.AdminQr');
        })->name('admin.qr');
        
        Route::get('/api/qr/generate', [AttendanceController::class, 'generateToken'])
            ->name('qr.generate');

        Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])
            ->name('admin.dashboard');

        Route::patch('/admin/presensi/{id}/acc', [\App\Http\Controllers\AdminController::class, 'acc'])->name('admin.presensi.acc');
        Route::patch('/admin/presensi/{id}/tolak', [\App\Http\Controllers\AdminController::class, 'tolak'])->name('admin.presensi.tolak');
        Route::get('/admin/presensi/export/csv', [\App\Http\Controllers\AdminController::class, 'exportCsv'])->name('admin.presensi.export.csv');
    });
});