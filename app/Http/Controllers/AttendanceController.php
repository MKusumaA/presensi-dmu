<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\QrToken;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class AttendanceController extends Controller
{
    public function generateToken(): JsonResponse
    {
        $rawToken = Str::random(64);
        
        QrToken::truncate(); 

        QrToken::create([
            'token_hash' => hash('sha256', $rawToken),
            'expires_at' => Carbon::now()->addSeconds(15)
        ]);

        return response()->json(['token' => $rawToken], Response::HTTP_CREATED);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'qr_token' => ['required', 'string', 'size:64']
        ]);

        return DB::transaction(function () use ($validated, $request): RedirectResponse {
            $hashedToken = hash('sha256', $validated['qr_token']);

            $qrToken = QrToken::where('token_hash', $hashedToken)
                ->where('expires_at', '>=', Carbon::now())
                ->lockForUpdate()
                ->first();

            if (!$qrToken) {
                abort(Response::HTTP_FORBIDDEN);
            }

            $qrToken->delete();

            $timeLimit = Carbon::today()->setTime(8, 15, 0);
            $status = Carbon::now()->lessThanOrEqualTo($timeLimit) ? 'Hadir' : 'Menunggu ACC';

            Presensi::create([
                'user_id' => $request->user()->id,
                'waktu_absen' => Carbon::now(),
                'status' => $status,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('dashboard')->with('success', 'Attendance securely verified.');
        });
    }
}