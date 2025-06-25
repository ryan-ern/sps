<?php

namespace App\Jobs;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifMail;

class HitungDendaPeminjaman implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Menjalankan schedule:run pada " . now());   //* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1 (GUNAKAN INI SAAT HOSTING)

        Peminjaman::where('kembali', '!=', 'selesai')
            ->orWhereNull('kembali')
            ->whereNull('tgl_kembali')
            ->get()
            ->each(function ($peminjaman) {
                $peminjaman->hitungDenda();

                $estKembali = \Carbon\Carbon::parse($peminjaman->est_kembali);
                $now = now();

                // Kirim pengingat 1 hari sebelum jatuh tempo
                if ($now->diffInDays($estKembali, false) === 1) {
                    Mail::to($peminjaman->user->email)
                        ->send(new NotifMail($peminjaman, 'sebelum'));
                }

                // Kirim pengingat jika sudah lewat dari est_kembali dan belum dikembalikan
                if ($now->greaterThan($estKembali)) {
                    Mail::to($peminjaman->user->email)
                        ->send(new NotifMail($peminjaman, 'setelah'));
                }
            });
    }
}
