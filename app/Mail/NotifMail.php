<?php

namespace App\Mail;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;
    public $status; // "sebelum" atau "setelah"

    public function __construct(Peminjaman $peminjaman, $status)
    {
        $this->peminjaman = $peminjaman;
        $this->status = $status;
    }

    public function build()
    {
        return $this->subject('Pengingat Pengembalian Buku')
            ->view('emails.pengingat-kembali');
    }
}
