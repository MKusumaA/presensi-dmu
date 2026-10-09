<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

final class AdminController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();

        $presensiHariIni = Presensi::with('user')
            ->whereDate('created_at', $hariIni)
            ->orderBy('created_at', 'desc')
            ->get();

        $riwayatPresensi = Presensi::with('user')
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

    public function updateStatus(Request $request, int $id)
    {
        // Validasi input untuk memastikan hanya kode yang diizinkan yang bisa masuk ke database
        $request->validate([
            'status' => 'required|in:Hadir,MT,Sakit,Ijin,TMDL,TMTD,TA,Ditolak (Alpa),Menunggu ACC',
        ]);

        $presensi = Presensi::findOrFail($id);
        $presensi->status = $request->status;
        $presensi->save();

        return redirect()->back()->with('success', 'Status presensi karyawan berhasil dikoreksi.');
    }

    public function exportCsv()
    {
        $presensis = Presensi::with('user')->orderBy('waktu_absen', 'desc')->get();
        $filename = 'Laporan_Absensi_DMU_'.date('Y-m-d').'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Nama Karyawan', 'Waktu Scan', 'Entitas Perusahaan', 'Status'];

        $callback = function () use ($presensis, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($presensis as $presensi) {
                fputcsv($file, [
                    $presensi->user->name,
                    $presensi->waktu_absen,
                    $presensi->user->company_entity ?? '-',
                    $presensi->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
            'company_entity' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'karyawan',
            'company_entity' => $request->company_entity,
        ]);

        return redirect()->back()->with('success', 'Akun karyawan baru berhasil ditambahkan.');
    }

    public function indexKaryawan()
    {
        $karyawans = User::where('role', 'karyawan')->get();

        return view('admin.karyawan-index', compact('karyawans'));
    }

    public function updateKaryawan(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'company_entity' => 'required|string|max:255',
        ]);

        $karyawan = User::findOrFail($id);
        $karyawan->update([
            'name' => $request->name,
            'email' => $request->email,
            'company_entity' => $request->company_entity,
        ]);

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroyKaryawan(int $id)
    {
        $karyawan = User::findOrFail($id);
        $karyawan->delete();

        return redirect()->back()->with('success', 'Akun karyawan berhasil dihapus.');
    }
}
