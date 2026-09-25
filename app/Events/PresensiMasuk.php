<?php

namespace App\Events;

use App\Models\Presensi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresensiMasuk implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $presensi;
    public $namaKaryawan;
    public $waktuScan;

    public function __construct(Presensi $presensi)
    {
        $this->presensi = $presensi;
        $this->namaKaryawan = $presensi->user->name;
        // Format waktu agar siap cetak
        $this->waktuScan = $presensi->created_at->format('H:i:s') . ' WIB'; 
    }

    public function broadcastOn(): array
    {
        // Nama saluran (channel) radio tempat kita memancarkan sinyal
        return [
            new Channel('hrd-dashboard'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'presensi.baru';
    }
}