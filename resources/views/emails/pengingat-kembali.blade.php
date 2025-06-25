<p>Halo {{ $peminjaman->user->name }},</p>

@if ($status == 'sebelum')
    <p>Ini adalah pengingat bahwa Anda harus mengembalikan buku <strong>{{ $peminjaman->buku->judul }}</strong> paling
        lambat besok ({{ $peminjaman->est_kembali->format('d M Y') }}).</p>
@else
    <p>Anda telah melewati batas pengembalian untuk buku <strong>{{ $peminjaman->buku->judul }}</strong> sejak tanggal
        {{ $peminjaman->est_kembali->format('d M Y') }}. Harap segera dikembalikan untuk menghindari denda lebih besar.
    </p>
@endif

<p>Terima kasih.</p>
