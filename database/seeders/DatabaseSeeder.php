<?php

namespace Database\Seeders;

use App\Models\CustomerAccount;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Users (3 roles) ──

        User::create([
            'username' => 'admin',
            'name'     => 'Administrator',
            'password' => 'admin123',
            'role'     => 'Administrator',
            'status'   => 'Aktif',
        ]);

        User::create([
            'username' => 'supervisor',
            'name'     => 'Supervisor',
            'password' => 'supervisor123',
            'role'     => 'Supervisor',
            'status'   => 'Aktif',
        ]);

        User::create([
            'username' => 'teller01',
            'name'     => 'Teller Satu',
            'password' => 'teller123',
            'role'     => 'Teller',
            'status'   => 'Aktif',
        ]);

        // ── Nasabah (1) + Customer Account ──

        $nasabah = Nasabah::create([
            'account_number' => 'RPL-2026-0001',
            'student_number' => '17782',
            'student_name'   => 'Reno Cahya Hutama',
            'class'          => '12 RPL 1',
            'jurusan'        => 'RPL',
            'gender'         => 'L',
            'phone_number'   => '08231',
            'security_pin'   => bcrypt('123456'),
            'balance'        => 0,
            'status'         => 'Aktif',
        ]);

        CustomerAccount::create([
            'nasabah_id' => $nasabah->id,
            'username'   => 'renocahya',
            'password'   => 'nasabah123',
        ]);
    }
}
