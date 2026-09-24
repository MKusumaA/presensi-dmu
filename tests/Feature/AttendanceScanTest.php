<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\QrToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class AttendanceScanTest extends TestCase
{
    use RefreshDatabase;

    private User $karyawan;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup isolasi data pengguna untuk pengujian
        $this->karyawan = User::factory()->create([
            'role' => 'karyawan',
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_karyawan_dapat_melakukan_scan_dengan_token_valid(): void
    {
        $rawToken = Str::random(64);
        
        QrToken::create([
            'token_hash' => hash('sha256', $rawToken),
            'expires_at' => Carbon::now()->addSeconds(60),
        ]);

        $response = $this->actingAs($this->karyawan)
            ->post(route('attendance.scan'), [
                'qr_token' => $rawToken,
            ]);

        $response->assertRedirect(route('attendance.scan.view'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('presensis', [
            'user_id' => $this->karyawan->id,
        ]);
    }

    public function test_menolak_scan_dengan_token_kedaluwarsa(): void
    {
        $rawToken = Str::random(64);
        
        QrToken::create([
            'token_hash' => hash('sha256', $rawToken),
            'expires_at' => Carbon::now()->subSeconds(10), // Simulasi kadaluwarsa
        ]);

        $response = $this->actingAs($this->karyawan)
            ->post(route('attendance.scan'), [
                'qr_token' => $rawToken,
            ]);

        // Ekspektasi sistem menolak dan mengembalikan sesi error
        $response->assertSessionHas('error'); 
    }

    public function test_mencegah_admin_melakukan_scan_sebagai_karyawan(): void
    {
        $rawToken = Str::random(64);
        
        QrToken::create([
            'token_hash' => hash('sha256', $rawToken),
            'expires_at' => Carbon::now()->addSeconds(60),
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('attendance.scan'), [
                'qr_token' => $rawToken,
            ]);

        // Memastikan mekanisme FormRequest authorize() memblokir akses
        $response->assertForbidden();
    }
}