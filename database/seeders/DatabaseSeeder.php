<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bikin Akun HRD
        User::firstOrCreate(
            ['email' => 'erlin@dmu.com'],
            [
                'name' => 'Ibu Erlin (HR Manager)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Bikin Akun Karyawan
        User::firstOrCreate(
            ['email' => 'budi@dmu.com'],
            [
                'name' => 'Budi (Staff IT)',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
            ]
        );

        $this->call([
            PresensiSeeder::class,
        ]);
    }
}