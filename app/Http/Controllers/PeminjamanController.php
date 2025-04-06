<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

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

    public function accept(Request $request, $id)
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

        $peminjaman->pinjam = 'terima';
        $peminjaman->kembali = '-';
        $peminjaman->save();

        flash()->flash(
            'success',
            'Data peminjaman ' . $request->fullname . ' dengan no_regis ' . $request->no_regis . ' berhasil diterima.',
            [],
            'Terima Peminjaman Sukses'
        );

        return redirect()->route('peminjaman.read');
    }

    public function decline(Request $request, $id)
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

        flash()->flash(
            'success',
            'Data peminjaman ' . $request->fullname . ' dengan no_regis ' . $request->no_regis . ' berhasil ditolak.',
            [],
            'Tolak Peminjaman Sukses'
        );

        return redirect()->route('peminjaman.read');
    }
}
