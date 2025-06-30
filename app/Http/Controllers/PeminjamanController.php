<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|in:5,10,25,50,100,500,Semua',
            'page' => 'integer|min:1',
            'search' => 'nullable|string|max:255',
            'dates' => 'nullable|string',
        ]);

        // Ambil jumlah data per halaman (default: 10)
        $perPage = $request->input('per_page', 10);

        // Ambil nilai pencarian
        $search = $request->input('search');

        // Ambil tanggal range
        $dateRange = $request->input('dates');

        // Query dasar untuk Buku Referensi
        $referensiQuery = Peminjaman::whereHas('buku', function ($query) {
            $query->where('jenis', 'referensi');
        })->orderByRaw("CASE WHEN pinjam = 'verifikasi' THEN 0 ELSE 1 END")
            ->orderBy('tgl_pinjam', 'desc');

        // Query dasar untuk Buku Paket
        $paketQuery = Peminjaman::whereHas('buku', function ($query) {
            $query->where('jenis', 'paket');
        })->orderByRaw("CASE WHEN pinjam = 'verifikasi' THEN 0 ELSE 1 END")
            ->orderBy('tgl_pinjam', 'desc');

        // Filter berdasarkan pencarian
        if ($search) {
            $referensiQuery
                ->where('nisn', 'like', "%{$search}%")
                ->orWhere('fullname', 'like', "%{$search}%")
                ->orWhere('judul', 'like', "%{$search}%")
                ->orWhere('tgl_pinjam', 'like', "%{$search}%")
                ->orWhere('denda', 'like', "%{$search}%");
            $paketQuery
                ->where('nisn', 'like', "%{$search}%")
                ->orWhere('fullname', 'like', "%{$search}%")
                ->orWhere('judul', 'like', "%{$search}%")
                ->orWhere('tgl_pinjam', 'like', "%{$search}%")
                ->orWhere('denda', 'like', "%{$search}%");
        }

        // Filter berdasarkan rentang tanggal
        if ($dateRange  && $dateRange !== '01/01/0001 - 01/01/0001') {
            $dates = explode(" - ", $dateRange);
            if (count($dates) === 2) {
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();

                $referensiQuery->whereBetween('tgl_pinjam', [$startDate, $endDate]);
                $paketQuery->whereBetween('tgl_pinjam', [$startDate, $endDate]);
            }
        }

        // Jika per_page adalah "Semua", tampilkan semua data
        if ($request->per_page == 'Semua') {
            $referensi = $referensiQuery->paginate(1000000);
            $paket = $paketQuery->paginate(1000000);
        } else {
            $referensi = $referensiQuery->paginate($perPage);
            $paket = $paketQuery->paginate($perPage);
        }

        return view('pages.admin.peminjaman', compact('referensi', 'paket', 'perPage', 'search', 'dateRange'));
    }

    public function userPeminjaman(Request $request) //admin untuk menampilkan riwayat peminjaman buku
    {
        $request->validate([
            'per_page' => 'nullable|in:5,10,25,50,100,500,Semua',
            'page' => 'integer|min:1',
            'search' => 'nullable|string|max:255',
            'dates' => 'nullable|string',
            'nisn' => 'nullable|string',
        ]);

        // Ambil jumlah data per halaman (default: 10)
        $perPage = $request->input('per_page', 10);

        // Ambil nilai pencarian
        $search = $request->input('search');

        // Ambil tanggal range
        $dateRange = $request->input('dates');

        // Query dasar untuk Buku Referensi
        $referensiQuery = Peminjaman::whereHas('buku', function ($query) {
            $query->where('jenis', 'referensi');
        })
            ->orderByRaw("CASE WHEN pinjam = 'verifikasi' THEN 0 ELSE 1 END")
            ->orderBy('tgl_pinjam', 'desc');

        // Query dasar untuk Buku Paket
        $paketQuery = Peminjaman::whereHas('buku', function ($query) {
            $query->where('jenis', 'paket');
        })
            ->orderByRaw("CASE WHEN pinjam = 'verifikasi' THEN 0 ELSE 1 END")
            ->orderBy('tgl_pinjam', 'desc');

        // Filter berdasarkan pencarian
        if ($search) {
            $referensiQuery
                ->where('nisn', 'like', "%{$search}%")
                ->orWhere('fullname', 'like', "%{$search}%")
                ->orWhere('judul', 'like', "%{$search}%")
                ->orWhere('tgl_pinjam', 'like', "%{$search}%")
                ->orWhere('denda', 'like', "%{$search}%");
            $paketQuery
                ->where('nisn', 'like', "%{$search}%")
                ->orWhere('fullname', 'like', "%{$search}%")
                ->orWhere('judul', 'like', "%{$search}%")
                ->orWhere('tgl_pinjam', 'like', "%{$search}%")
                ->orWhere('denda', 'like', "%{$search}%");
        }

        // Filter berdasarkan rentang tanggal
        if ($dateRange  && $dateRange !== '01/01/0001 - 01/01/0001') {
            $dates = explode(" - ", $dateRange);
            if (count($dates) === 2) {
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();

                $referensiQuery->whereBetween('tgl_pinjam', [$startDate, $endDate]);
                $paketQuery->whereBetween('tgl_pinjam', [$startDate, $endDate]);
            }
        }

        // Jika per_page adalah "Semua", tampilkan semua data
        if ($request->per_page == 'Semua') {
            $referensi = $referensiQuery
                ->where('nisn', $request->nisn)
                ->paginate(1000000);
            $paket = $paketQuery
                ->where('nisn', $request->nisn)
                ->paginate(1000000);
        } else {
            $referensi = $referensiQuery
                ->where('nisn', $request->nisn)
                ->paginate($perPage);
            $paket = $paketQuery
                ->where('nisn', $request->nisn)
                ->paginate($perPage);
        }

        $user = User::find($request->nisn);

        return view('pages.admin.peminjaman-detail', compact('referensi', 'paket', 'perPage', 'search', 'dateRange', 'user'));
    }

    public function indexSiswa(Request $request) //menampilkan data peminjaman buku yang dilakukan oleh siswa yang sedang login
    {
        $request->validate([
            'per_page' => 'nullable|in:5,10,25,50,100,500,Semua',
            'page' => 'integer|min:1',
            'search' => 'nullable|string|max:255',
        ]);

        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $data = Peminjaman::with('buku')
            ->whereHas('buku', function ($query) {
                $query->where('nisn', auth()->user()->nisn);
            });

        if ($search) {
            $data->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('no_regis', 'like', "%{$search}%")
                    ->orWhere('tgl_pinjam', 'like', "%{$search}%")
                    ->orWhere('denda', 'like', "%{$search}%");
            });
        }

        $data->orderBy('tgl_pinjam', 'desc');

        if ($perPage == 'Semua') {
            $data = $data->paginate(1000000);
        } else {
            $data = $data->paginate($perPage);
        }

        return view('pages.siswa.peminjaman', compact('data'));
    }

    public function pinjam($id) // siswa yang sedang login untuk mengajukan peminjaman buku
    {
        $buku = Buku::where('no_regis', $id)->where('status', 'tersedia')->first();

        if (!$buku || $buku->stok < 1) {
            flash()->flash('error', 'Buku dengan no_regis ' . $id . ' tidak tersedia.', [], 'Tahap Peminjaman Gagal');
            return redirect()->route('dashboard');
        }

        $user = auth()->user();

        // Validasi untuk buku referensi
        if ($buku->jenis_buku === 'referensi') {
            $peminjamanReferensi = Peminjaman::where('nisn', $user->nisn)
                ->whereHas('buku', fn($q) => $q->where('jenis_buku', 'referensi'))
                ->pluck('judul')
                ->unique();

            if ($peminjamanReferensi->count() >= 2) {
                flash()->flash('error', 'Maksimal 2 buku referensi yang dapat dipinjam.', [], 'Tahap Peminjaman Gagal');
                return redirect()->route('dashboard');
            }

            if ($peminjamanReferensi->contains($buku->judul)) {
                flash()->flash('error', 'Anda sudah meminjam buku referensi dengan judul ' . $buku->judul . '.', [], 'Tahap Peminjaman Gagal');
                return redirect()->route('dashboard');
            }
        }

        // Ubah status buku yang dipinjam menjadi tidak tersedia
        $buku->update([
            'status' => 'tidak tersedia'
        ]);

        // Catat peminjaman
        Peminjaman::create([
            'nisn' => $user->nisn,
            'no_regis' => $buku->no_regis,
            'fullname' => $user->fullname,
            'judul' => $buku->judul,
            'tgl_pinjam' => now(),
            'denda' => 0,
            'pinjam' => 'verifikasi'
        ]);

        flash()->flash('success', 'Buku ' . $buku->judul . ' dengan nomor registrasi ' . $buku->no_regis . ' berhasil diajukan.', [], 'Tahap Peminjaman dilakukan');

        return redirect()->route('peminjaman-siswa.read');
    }




    public function accept(Request $request, $id) //digunakan oleh admin untuk menerima atau menyetujui permintaan peminjaman buku dari siswa
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->first();
        if (!$peminjaman) {
            flash()->flash(
                'danger',
                'Data peminjaman ' . $request->fullname . ' dengan no_regis ' . $request->no_regis . ' tidak ditemukan.',
                [],
                'Terima Peminjaman Gagal'
            );
            return redirect()->route('peminjaman.read');
        }

        // Kurangi stok seluruh buku dengan judul yang sama (jika dikelola sebagai satu grup)
        Buku::where('judul', $peminjaman->buku->judul)->update([
            'stok' => DB::raw('stok - 1')
        ]);

        $peminjaman->pinjam = 'terima';
        $peminjaman->kembali = '-';
        $peminjaman->tgl_pinjam = now();
        if ($peminjaman->buku && strtolower($peminjaman->buku->jenis) === 'paket') {
            $peminjaman->est_kembali = now()->addDays(180); // 1 semester
        } else {
            $peminjaman->est_kembali = now()->addDays(3);
        }

        $peminjaman->save();

        Buku::where('judul', $peminjaman->buku->judul)->update([
            'stok' => DB::raw('stok - 1')
        ]);

        flash()->flash(
            'success',
            'Data peminjaman ' . $request->fullname . ' dengan no_regis ' . $request->no_regis . ' berhasil diterima.',
            [],
            'Terima Peminjaman Sukses'
        );

        return redirect()->route('peminjaman.read');
    }

    public function decline(Request $request, $id) //digunakan oleh admin untuk menolak permintaan peminjaman buku
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->first();

        if (!$peminjaman) {
            flash()->flash(
                'danger',
                'Data peminjaman ' . $request->fullname . ' dengan no_regis ' . $request->no_regis . ' tidak ditemukan.',
                [],
                'Terima Peminjaman Gagal'
            );
            return redirect()->route('peminjaman.read');
        }
        $peminjaman->pinjam = 'tolak';
        $peminjaman->save();

        $buku = Buku::where('no_regis', $peminjaman->no_regis)->first();
        $buku->status = 'tersedia';
        $buku->save();

        flash()->flash(
            'success',
            'Data peminjaman ' . $request->fullname . ' dengan no_regis ' . $request->no_regis . ' berhasil ditolak.',
            [],
            'Tolak Peminjaman Sukses'
        );

        return redirect()->route('peminjaman.read');
    }
}
