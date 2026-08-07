<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'role_name' => 'admin', 'deskripsi' => 'Administrator/Petugas Sapras', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'role_name' => 'mahasiswa', 'deskripsi' => 'Mahasiswa', 'created_at' => now(), 'updated_at' => now()],
        ]);

        User::create([
            'id_role' => 1,
            'username' => 'admin01',
            'nama_lengkap' => 'Admin Sapras',
            'email' => 'admin@sipras.test',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'id_role' => 2,
            'username' => '2021010234',
            'nama_lengkap' => 'Ahmad Fauzi',
            'email' => 'mahasiswa@sipras.test',
            'password' => Hash::make('password'),
        ]);
    }
}