<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([
            [
                '1234567890',
                'Tester Satu',
                'testersatu',
                '12A',
                'password',
                'siswa',
                'aktif',
                'testersatu@example.com'
            ],
            [
                '1234567891',
                'Tester Dua',
                'testerdua',
                '12B',
                'password',
                'admin',
                'tidak aktif',
                'testerdua@example.com'
            ],
            [
                '1234567892',
                'Tester Tiga',
                'testertiga',
                '12A',
                'password',
                'guru',
                'aktif',
                'testertiga@example.com'
            ]
        ]);
    }

    public function headings(): array
    {
        return ['nisn', 'fullname', 'username', 'kelas', 'password', 'role', 'status', 'email'];
    }
}
