<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;

class PresensiSeeder extends Seeder
{
    public function run()
    {
        // Ambil user karyawan secara acak
        $karyawan = User::where('role', 'karyawan')->first();

        if (!$karyawan) {
            return;
        }

        $statuses = ['Hadir', 'Sakit', 'MT'];
        
        for ($i = 0; $i < 15; $i++) {
            // Mundur 1 hingga 3 hari, acak jam
            $daysBack = rand(1, 3);
            $hour = rand(7, 10);
            $minute = rand(0, 59);
            $date = Carbon::now()->subDays($daysBack)->setTime($hour, $minute, 0);

            // Random status
            $status = $statuses[array_rand($statuses)];

            Presensi::create([
                'user_id' => $karyawan->id,
                'waktu_absen' => $date,
                'status' => $status,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Dummy Seeder)',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
