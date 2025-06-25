<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifMail;

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