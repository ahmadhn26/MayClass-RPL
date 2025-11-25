<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Utama MayClass',
                'username' => 'admin_utama',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('gatau123'),
                'role' => 'admin',
                'phone' => '0812-7777-1234',
                'gender' => 'other',
                'address' => 'Jl. Pengelola Pendidikan No. 1, Bandung',
            ]
        );
    }
}
