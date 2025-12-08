<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'adminae@gmail.com'], 
            [
                'nama_lengkap' => 'Super Admin',
                'password'     => Hash::make('admin123'), 
                'is_admin'     => 1,
            ]
        );
    }
}
