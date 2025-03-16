<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Buku;
use App\Models\Kunjungan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'nisn' => 1,
            'username' => 'admin',
            'fullname' => 'Admin',
            'password' => bcrypt('123'),
            'role' => 'admin',
            'status' => 'aktif',
            'kelas' => 'admin',
        ]);

        User::factory()->create([
            'nisn' => 2,
            'username' => 'guru',
            'fullname' => 'Guru',
            'password' => bcrypt('123'),
            'role' => 'guru',
            'status' => 'aktif',
            'kelas' => 'guru',
        ]);

        User::factory()->create([
            'nisn' => 3,
            'username' => 'siswa',
            'fullname' => 'Siswa',
            'password' => bcrypt('123'),
            'role' => 'siswa',
            'status' => 'aktif',
        ]);

        User::factory()->create([
            'nisn' => 4,
            'username' => 'block',
            'fullname' => 'Block',
            'password' => bcrypt('123'),
            'role' => 'siswa',
            'status' => 'tidak aktif',
        ]);

        User::factory(10)->create();

        Buku::factory(10)->create();

        Kunjungan::factory(10)->create();
    }
}
