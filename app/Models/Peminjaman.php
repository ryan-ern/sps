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
        'est_kembali',
        'tgl_kembali',
        'denda',
        'pinjam',
        'kembali'
    ];

    protected $casts = [
        'tgl_pinjam' => 'datetime',
        'est_kembali' => 'datetime',
        'tgl_kembali' => 'datetime',
    ];

    public function hitungDenda()
    {
        if (!$this->tgl_pinjam) {
            return;
        }

        $estKembali = Carbon::parse($this->est_kembali);
        $tglKembali = $this->tgl_kembali != null ? Carbon::parse($this->tgl_kembali) : now();

        $jamTelat = $tglKembali->greaterThan($estKembali) ? $estKembali->diffInHours($tglKembali) : 0;

        if ($jamTelat >= 24) {
            $jumlahHariTelat = floor($jamTelat / 24);
            $this->denda = $jumlahHariTelat * 500;
        } else {
            $this->denda = 0;
        }

        $this->save();
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
