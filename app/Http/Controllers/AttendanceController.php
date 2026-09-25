<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\PresensiMasuk;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\Presensi;
use App\Models\QrToken;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

final class AttendanceController extends Controller
{
    public function generateToken(): JsonResponse
    {
        $rawToken = Str::random(64);
        
        QrToken::where('expires_at', '<', Carbon::now())->delete();

        QrToken::create([
            'token_hash' => hash('sha256', $rawToken),
            'expires_at' => Carbon::now()->addSeconds(60)
        ]);

        return response()->json(['token' => $rawToken], Response::HTTP_CREATED);
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            return DB::transaction(function () use ($validated, $request): RedirectResponse {
                $rawToken = trim($validated['qr_token']);
                $hashedToken = hash('sha256', $rawToken);

                $qrToken = QrToken::where('token_hash', $hashedToken)->first();

                if (!$qrToken) {
                    return redirect()->route('attendance.scan.view')->with('error', 'Token kadaluarsa. Layar mungkin sudah berganti QR.');
                }

                if ($qrToken->expires_at->isPast()) {
                    return redirect()->route('attendance.scan.view')->with('error', 'Waktu scan habis. Silakan scan QR yang baru.');
                }

                $qrToken->delete();

                $timeLimit = Carbon::today()->setTime(8, 15, 0);
                $status = Carbon::now()->lessThanOrEqualTo($timeLimit) ? 'Hadir' : 'Menunggu ACC';

                $presensi = Presensi::create([
                    'user_id' => $request->user()->id,
                    'waktu_absen' => Carbon::now(),
                    'status' => $status,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                event(new PresensiMasuk($presensi));
                return redirect()->route('attendance.scan.view')->with('success', 'Berhasil melakukan presensi!');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Attendance scan failed: ' . $e->getMessage());
            return redirect()->route('attendance.scan.view')->with('error', 'Terjadi kesalahan sistem saat memproses presensi.');
        }

    }
    
    public function scanView()
    {
        // Membuka halaman kamera untuk scan QR
        return view('presensi.ScanQr');
    }

    public function dashboardKaryawan()
    {
        $riwayat_absen = Presensi::where('user_id', Auth::id())
                                ->currentMonth()
                                ->orderBy('waktu_absen', 'desc')
                                ->get();

        return view('presensi.karyawan_dashboard', compact('riwayat_absen'));
    }
}