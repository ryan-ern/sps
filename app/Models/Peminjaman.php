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

        $estKembali = Carbon::parse($this->tgl_pinjam)->addDays(3);
        $tglKembali = $this->tgl_kembali != null ? Carbon::parse($this->tgl_kembali) : now();

        // Cek apakah telat
        if ($tglKembali->greaterThan($estKembali)) {
            $hariTelat = abs($tglKembali->diffInDays($estKembali, false));
            $this->denda = $hariTelat * 500;
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
