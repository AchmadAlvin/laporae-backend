<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'nama' => 'Admin Utama',
                'email' => 'admin@laporae.local',
                'password' => Hash::make('admin12345'),
            ],
            [
                'nama' => 'Admin Operasional',
                'email' => 'operasional@laporae.local',
                'password' => Hash::make('operasional123'),
            ],
        ];

        foreach ($admins as $admin) {
            Admin::updateOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }
    }
}
