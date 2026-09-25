<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;

// ==========================================
// RUTE HALAMAN DEPAN (ROOT)
// ==========================================
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') { 
            return redirect()->route('admin.dashboard');
        }
        // Jika karyawan login, arahkan ke dashboard karyawan
        return redirect()->route('karyawan.dashboard'); 
    }
    return redirect()->route('login');
});


// ==========================================
// RUTE PUBLIK (TIDAK PERLU LOGIN)
// ==========================================
// Layar yang dibuka oleh karyawan piket setiap pagi
Route::get('/layar-qr', function () {
    return view('presensi.AdminQr');
})->name('kiosk.qr');

// Mesin pembuat token QR agar layarnya bisa ganti-ganti barcode
Route::get('/api/qr/generate', [AttendanceController::class, 'generateToken'])
    ->name('qr.generate');


// ==========================================
// 1. RUTE GUEST (BELUM LOGIN)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    
    // Proses login dengan pelindung Anti-Spam (Maks 5x percobaan per menit)
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.post');
});


// ==========================================
// 2. RUTE GLOBAL (WAJIB LOGIN)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ------------------------------------------
    // KARYAWAN
    // ------------------------------------------
    Route::middleware('role:karyawan')->group(function () {
        
        // Membuka halaman kamera HP
        Route::get('/scan', [AttendanceController::class, 'scanView'])
            ->name('attendance.scan.view');
            
        // Menerima hasil QR Anti-Spam (Maks 10x scan per menit)
        Route::post('/scan', [AttendanceController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('attendance.scan');

        // Melihat riwayat absen pribadi
        Route::get('/dashboard', [AttendanceController::class, 'dashboardKaryawan'])
            ->name('karyawan.dashboard');
    });

    // ------------------------------------------
    // ADMIN / HRD
    // ------------------------------------------
    Route::middleware('role:admin')->group(function () {

        // Melihat seluruh daftar karyawan yang absen
        Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])
            ->name('admin.dashboard');

        // Tombol ACC / Tolak
        Route::patch('/admin/presensi/{id}/acc', [\App\Http\Controllers\AdminController::class, 'acc'])
            ->name('admin.presensi.acc');
            
        Route::patch('/admin/presensi/{id}/tolak', [\App\Http\Controllers\AdminController::class, 'tolak'])
            ->name('admin.presensi.tolak');
            
        // Export laporan bulanan
        Route::get('/admin/presensi/export/csv', [\App\Http\Controllers\AdminController::class, 'exportCsv'])
            ->name('admin.presensi.export.csv');

        Route::patch('/admin/presensi/{id}/update-status', [\App\Http\Controllers\AdminController::class, 'updateStatus'])
            ->name('admin.presensi.update-status');

        Route::post('/admin/karyawan', [\App\Http\Controllers\AdminController::class, 'storeKaryawan'])
            ->name('admin.karyawan.store');
    });


});