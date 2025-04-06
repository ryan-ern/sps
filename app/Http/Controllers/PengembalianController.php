<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PengembalianController extends Controller
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
            $query->where('jenis', 'referensi')
                ->where('pinjam', 'terima');
        })->orderByRaw("CASE WHEN kembali = 'verifikasi' THEN 0 ELSE 1 END")
            ->orderBy('tgl_kembali', 'desc');

        // Query dasar untuk Buku Paket
        $paketQuery = Peminjaman::whereHas('buku', function ($query) {
            $query->where('jenis', 'paket')
                ->where('pinjam', 'terima');
        })->orderByRaw("CASE WHEN kembali = 'verifikasi' THEN 0 ELSE 1 END")
            ->orderBy('tgl_kembali', 'desc');


        // Filter berdasarkan pencarian
        if ($search) {
            $referensiQuery->where('pinjam', 'terima')
                ->where(function ($query) use ($search) {
                    $query->where('nisn', 'like', "%{$search}%")
                        ->orWhere('fullname', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('tgl_pinjam', 'like', "%{$search}%")
                        ->orWhere('tgl_kembali', 'like', "%{$search}%")
                        ->orWhere('denda', 'like', "%{$search}%");
                });
            $paketQuery->where('pinjam', 'terima')
                ->where(function ($query) use ($search) {
                    $query->where('nisn', 'like', "%{$search}%")
                        ->orWhere('fullname', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('tgl_pinjam', 'like', "%{$search}%")
                        ->orWhere('tgl_kembali', 'like', "%{$search}%")
                        ->orWhere('denda', 'like', "%{$search}%");
                });
        }

        // Filter berdasarkan rentang tanggal
        if ($dateRange) {
            $dates = explode(" - ", $dateRange);
            if (count($dates) === 2) {
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();

                $referensiQuery->whereBetween('created_at', [$startDate, $endDate]);
                $paketQuery->whereBetween('created_at', [$startDate, $endDate]);
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

        return view('pages.admin.pengembalian', compact('referensi', 'paket', 'perPage', 'search', 'dateRange'));
    }

    public function accept(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->first();
        if (!$peminjaman) {
            flash()->flash(
                'danger',
                'Data pengembalian ' . $request->fullname . ' dengan no_regis ' . $request->no_regis . ' tidak ditemukan.',
                [],
                'Terima pengembalian Gagal'
            );
            return redirect()->route('pengembalian.read');
        }

        $peminjaman->kembali = 'selesai';
        $peminjaman->tgl_kembali = now();
        $peminjaman->save();

        flash()->flash(
            'success',
            'Data pengembalian ' . $request->fullname . ' dengan no_regis ' . $request->no_regis . ' berhasil diverifikasi.',
            [],
            'Terima pengembalian Sukses'
        );

        return redirect()->route('pengembalian.read');
    }
}
