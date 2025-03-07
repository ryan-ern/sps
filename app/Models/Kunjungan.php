<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungans';

    protected $fillable = [
        'nisn',
        'fullname',
        'kelas',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'nisn', 'nisn');
    }
}
