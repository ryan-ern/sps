<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use Illuminate\Console\Command;

class HitungSemuaDenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitung:denda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Peminjaman::where('kembali', '!=', 'selesai')
            ->orWhereNull('kembali')
            ->whereNull('tgl_kembali')
            ->get()->each(function ($peminjaman) {
                $peminjaman->hitungDenda();
                $this->info('Denda peminjaman ID : ' . $peminjaman->id . ' berhasil dihitung ulang.');
            });
    }
}
