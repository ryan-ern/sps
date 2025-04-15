<?php

namespace App\Providers;

use App\Models\Peminjaman;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.app.navbar', function ($view) {
            $notifikasi = [];
            $today = now(); // Tanggal saat ini
            $yesterday = $today->subDays(1); // Tanggal 1 hari yang lalu

            // Mengambil peminjaman yang harus dikembalikan dalam rentang 3 hari terakhir
            $peminjamanH1 = Peminjaman::where('pinjam', 'terima')
                ->where(function ($query) {
                    $query->where('kembali', '-')
                        ->orWhere('kembali', 'verifikasi');
                })
                ->whereDate('est_kembali', '<=', $today->toDateString()) // Hingga hari ini
                ->get();


            foreach ($peminjamanH1 as $peminjaman) {
                $notifikasi[] = [
                    'pesan' => 'Buku ' . $peminjaman->judul . '<br> yang dipinjam oleh '  . $peminjaman->fullname  . ' - ' . $peminjaman->user->kelas  . '<br> harus dikembalikan pada ' . $peminjaman->est_kembali->format('d M Y') . '!',
                    'waktu' => $today->diffForHumans($peminjaman->est_kembali),
                    'url' => route('pengembalian.read'),
                ];
            }


            // Notifikasi peminjaman terbaru
            $latestPeminjaman = Peminjaman::where('pinjam', 'verifikasi')
                ->orderBy('tgl_pinjam', 'desc') // Urutkan berdasarkan peminjaman terbaru
                ->get(); // Ambil semua peminjaman terbaru

            if ($latestPeminjaman->isNotEmpty()) { // Pastikan koleksi tidak kosong
                foreach ($latestPeminjaman as $peminjaman) {
                    $notifikasi[] = [
                        'pesan' => 'Buku "' . $peminjaman->judul . '" <br> ingin dipinjam oleh ' . $peminjaman->fullname . ' - ' . $peminjaman->user->kelas,
                        'waktu' => $today->diffForHumans($peminjaman->tgl_pinjam),
                        'url' => route('peminjaman.read'),
                    ];
                }
            }


            // Kirim notifikasi ke view
            $view->with('notifikasi', $notifikasi);
        });
    }
}
