<?php

namespace App\Jobs;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
            ->get()->each(function ($peminjaman) {
                $peminjaman->hitungDenda();
            });
    }
}
