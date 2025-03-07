<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'no_regis',
        'judul',
        'pengarang',
        'penerbit',
        'tahun',
        'stok',
        'keterangan',
        'file_buku',
        'file_cover',
        'jenis',
        'status',
    ];
}
