<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Bikin Akun HRD
        User::create([
            'name' => 'Ibu Erlin (HR Manager)',
            'email' => 'erlin@dmu.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Bikin Akun Karyawan
        User::create([
            'name' => 'Budi (Staff IT)',
            'email' => 'budi@dmu.com',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
        ]);
    }
}