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
                '9A',
                'siswa',
                'aktif',
                'testersatu@example.com'
            ],
            [
                '1234567891',
                'Tester Dua',
                '9B',
                'admin',
                'tidak aktif',
                'testerdua@example.com'
            ],
            [
                '1234567892',
                'Tester Tiga',
                '9C',
                'guru',
                'aktif',
                'testertiga@example.com'
            ]
        ]);
    }

    public function headings(): array
    {
        return ['nisn', 'fullname', 'kelas', 'role', 'status', 'email'];
    }
}
