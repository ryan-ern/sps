<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'nisn',
        'no_regis',
        'fullname',
        'judul',
        'tgl_pinjam',
        'tgl_kembali',
        'denda',
    ];

    public function hitungDenda($tgl_pinjam)
    {
        $tglBatas = Carbon::parse($tgl_pinjam)->addDays(3);
        $hariTelat = now()->diffInDays($tglBatas, false);

        if ($hariTelat > 0) {
            $this->denda = $hariTelat * 500;
            $this->save();
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nisn', 'nisn');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'no_regis', 'no_regis');
    }
}
