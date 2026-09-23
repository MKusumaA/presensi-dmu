<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class AdminController extends Controller
{
    public function index()
    {
        $hariIni = \Carbon\Carbon::today();

        $presensiHariIni = \App\Models\Presensi::with('user')
            ->whereDate('created_at', $hariIni)
            ->orderBy('created_at', 'desc')
            ->get();

        $riwayatPresensi = \App\Models\Presensi::with('user')
            ->whereDate('created_at', '<', $hariIni)
            ->orderBy('created_at', 'desc')
            ->paginate(15); // Pakai halaman (pagination) agar tidak berat jika data ribuan

        return view('admin.dashboard', compact('presensiHariIni', 'riwayatPresensi'));
    }

    public function acc(int $id): RedirectResponse
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->update(['status' => 'Hadir']); // Ubah status jadi Hadir
        
        return back()->with('success', 'Berhasil ACC! Karyawan ditandai Hadir.');
    }

    public function tolak(int $id): RedirectResponse
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->update(['status' => 'Ditolak (Alpa)']); // Ubah status jadi Ditolak
        
        return back()->with('error', 'Presensi Ditolak! Karyawan ditandai Alpa.');
    }
    
    public function updateStatus(\Illuminate\Http\Request $request, int $id)
    {
        // Validasi input untuk memastikan hanya kode yang diizinkan yang bisa masuk ke database
        $request->validate([
            'status' => 'required|in:Hadir,MT,Sakit,Ijin,TMDL,TMTD,TA,Ditolak (Alpa),Menunggu ACC'
        ]);

        $presensi = \App\Models\Presensi::findOrFail($id);
        $presensi->status = $request->status;
        $presensi->save();

        return redirect()->back()->with('success', 'Status presensi karyawan berhasil dikoreksi.');
    }

    public function exportCsv()
    {
        $presensis = Presensi::with('user')->orderBy('waktu_absen', 'desc')->get();
        $filename = "Laporan_Absensi_DMU_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Nama Karyawan', 'Waktu Scan', 'IP Address', 'Status'];

        $callback = function() use($presensis, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($presensis as $presensi) {
                fputcsv($file, [
                    $presensi->user->name,
                    $presensi->waktu_absen,
                    $presensi->ip_address,
                    $presensi->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}