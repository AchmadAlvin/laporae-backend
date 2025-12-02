<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Admin Aplikasi',
            'email' => 'admin@aksesmadiun.com',
            'password' => Hash::make('password123'),
        ]);
        User::create([
            'nama_lengkap' => 'Budi Setiawan',
            'email' => 'budi@email.com',
            'password' => Hash::make('Budi123'),
        ]);
        User::create([
            'nama_lengkap' => 'Ahmad Drian',
            'email' => 'ian@email.com',
            'password' => Hash::make('ian123'),
        ]);
    }
}
