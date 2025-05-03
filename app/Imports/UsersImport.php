<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, ShouldQueue
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        // Pisahkan fullname menjadi array kata
        $namaParts = explode(' ', trim($row['fullname']));
        $namaDepan = $namaParts[0];
        $namaBelakang = end($namaParts); // Nama terakhir (jika hanya 1 kata, jadi nama depan)

        // Ambil 4 digit terakhir dari NISN
        $nisnAkhir = substr($row['nisn'], -4);

        return new User([
            'nisn' => $row['nisn'],
            'fullname' => $row['fullname'],
            'username' => strtolower($namaDepan), // lowercase
            'kelas' => $row['kelas'],
            'password' => bcrypt(strtolower($namaBelakang) . $nisnAkhir),
            'role' => $row['role'],
            'status' => $row['status'],
            'email' => $row['email'],
        ]);
    }


    public function uniqueBy()
    {
        return ['nisn', 'username', 'email'];
    }


    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
