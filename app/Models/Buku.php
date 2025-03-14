<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';
    protected $primaryKey = 'no_regis';
    public $incrementing = false;
    protected $keyType = 'string';
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
