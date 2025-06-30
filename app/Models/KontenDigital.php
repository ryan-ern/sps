<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenDigital extends Model
{
    use HasFactory;

    protected $table = 'konten_digitals';

    protected $fillable = [
        'id',
        'nuptk',
        'jenis',
        'judul',
        'url',
        'cover',
        'file_path',
        'pengarang',
        'penerbit',
        'dilihat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'nuptk', 'nisn');
    }
}
