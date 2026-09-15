<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// PINTU RAHASIA UNTUK TESTING (Bypass Login)
Route::get('/login', function () {
    $user = User::firstOrCreate(
        ['email' => 'karyawan@dmu.com'],
        ['name' => 'Karyawan Test DMU', 'password' => bcrypt('123456')]
    );
    Auth::login($user);
    return redirect()->route('attendance.scan.view');
})->name('login');

// Rute Utama (Hanya bisa diakses kalau sudah lewat pintu rahasia / login)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/qr', function () {
        return view('presensi.AdminQr');
    })->name('admin.qr');

    Route::get('/attendance/scan', function () {
        return view('presensi.ScanQr');
    })->name('attendance.scan.view');

    Route::post('/api/qr/generate', [AttendanceController::class, 'generateToken'])
        ->name('qr.generate');
        
    Route::post('/attendance/scan', [AttendanceController::class, 'store'])
        ->name('attendance.scan');
});