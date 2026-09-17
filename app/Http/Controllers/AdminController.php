<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class AdminController extends Controller
{
    public function index(): View
    {
        $presensis = Presensi::with('user')->orderBy('waktu_absen', 'desc')->get();
        return view('admin.dashboard', compact('presensis'));
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