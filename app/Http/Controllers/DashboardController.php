<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kunjungan;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $roles = Auth::user()->role;
        if ($roles == 'siswa') {
            $today = Carbon::today();

            // Statistik harian
            $dataPengunjung  = Kunjungan::whereDate('created_at', $today)->count();
            $dataPeminjam    = Peminjaman::whereDate('tgl_pinjam', $today)->count();
            $dataKembali     = Peminjaman::whereDate('tgl_kembali', $today)->count();

            // Buku Terfavorit: berdasarkan jumlah peminjaman per judul
            $bukuFavorit = Buku::select('judul', DB::raw('MIN(no_regis) as regis_terendah'), DB::raw('CAST(SUM(stok) AS UNSIGNED) as total_stok'))
                ->groupBy('judul')
                ->get()
                ->map(function ($buku) {
                    // Hitung total peminjaman berdasarkan judul
                    $totalPinjam = Peminjaman::where('judul', $buku->judul)
                        ->where('pinjam', 'terima')
                        ->count();

                    // Ambil cover dari no_regis terendah (karena biasanya data awal)
                    $data = Buku::where('no_regis', $buku->regis_terendah)->first();

                    return (object) [
                        'no_regis' => $buku->regis_terendah ?? '-',
                        'judul' => $buku->judul ?? '-',
                        'total_pinjam' => $totalPinjam ?? 0,
                        'file_cover' => $data?->file_cover ?? 'default/default-book.png',
                        'file_buku' => $data?->file_buku ?? 'default/default-book.png',
                        'stok' => $data->stok ?? 0,
                        'pengarang' => $data->pengarang ?? '-',
                        'penerbit' => $data->penerbit ?? '-',
                        'keterangan' => $data->keterangan ?? '-',
                        'tahun' => $data->tahun ?? '-',
                    ];
                })
                ->sortByDesc('total_pinjam')
                ->take(5)
                ->values();

            // Wajib Dilihat (semua buku urut berdasarkan total peminjaman)
            $wajibDilihat = Buku::select('judul', DB::raw('MIN(no_regis) as regis_terendah'))
                ->groupBy('judul')
                ->get()
                ->map(function ($buku) {
                    $totalPinjam = Peminjaman::where('judul', $buku->judul)
                        ->where('pinjam', 'terima')
                        ->count();

                    $data = Buku::where('no_regis', $buku->regis_terendah)->first();

                    return (object) [
                        'no_regis' => $data->no_regis ?? '-',
                        'judul' => $data->judul ?? '-',
                        'total_pinjam' => $totalPinjam ?? 0,
                        'file_cover' => $data?->file_cover ?? 'default/default-book.png',
                        'file_buku' => $data?->file_buku ?? 'default/default-book.png',
                        'stok' => $data->stok ?? 0,
                        'pengarang' => $data->pengarang ?? '-',
                        'penerbit' => $data->penerbit ?? '-',
                        'keterangan' => $data->keterangan ?? '-',
                        'tahun' => $data->tahun ?? '-',
                    ];
                })
                ->sortByDesc('total_pinjam')
                ->values();

            // Konten sering dilihat: bisa pakai updated_at sebagai simulasi
            $kontenSeringDilihat = Buku::orderByDesc('updated_at')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'no_regis' => $item->regis_terendah ?? '-',
                        'judul' => $item->judul ?? '-',
                        'total_pinjam' => $totalPinjam ?? 0,
                        'file_cover' => $item?->file_cover ?? 'default/default-book.png',
                        'file_buku' => $item?->file_buku ?? 'default/default-book.png',
                        'stok' => $item->stok ?? 0,
                        'pengarang' => $item->pengarang ?? '-',
                        'penerbit' => $item->penerbit ?? '-',
                        'keterangan' => $item->keterangan ?? '-',
                        'tahun' => $item->tahun ?? '-',
                        'view_count' => 1 // Ganti dengan field aslinya jika ada
                    ];
                });

            return view('pages.siswa.dashboard', compact(
                'dataPengunjung',
                'dataPeminjam',
                'dataKembali',
                'bukuFavorit',
                'wajibDilihat',
                'kontenSeringDilihat'
            ));
        } elseif ($roles == 'guru') {
            $today = Carbon::today();

            // Statistik harian
            $dataPengunjung  = Kunjungan::whereDate('created_at', $today)->count();
            $dataPeminjam    = Peminjaman::whereDate('tgl_pinjam', $today)->count();
            $dataKembali     = Peminjaman::whereDate('tgl_kembali', $today)->count();

            // Buku Terfavorit: berdasarkan jumlah peminjaman per judul
            $bukuFavorit = Buku::select('judul', DB::raw('MIN(no_regis) as regis_terendah'), DB::raw('CAST(SUM(stok) AS UNSIGNED) as total_stok'))
                ->groupBy('judul')
                ->get()
                ->map(function ($buku) {
                    // Hitung total peminjaman berdasarkan judul
                    $totalPinjam = Peminjaman::where('judul', $buku->judul)
                        ->where('pinjam', 'terima')
                        ->count();

                    // Ambil cover dari no_regis terendah (karena biasanya data awal)
                    $data = Buku::where('no_regis', $buku->regis_terendah)->first();

                    return (object) [
                        'no_regis' => $buku->regis_terendah ?? '-',
                        'judul' => $buku->judul ?? '-',
                        'total_pinjam' => $totalPinjam ?? 0,
                        'file_cover' => $data?->file_cover ?? 'default/default-book.png',
                        'file_buku' => $data?->file_buku ?? 'default/default-book.png',
                        'stok' => $data->stok ?? 0,
                        'pengarang' => $data->pengarang ?? '-',
                        'penerbit' => $data->penerbit ?? '-',
                        'keterangan' => $data->keterangan ?? '-',
                        'tahun' => $data->tahun ?? '-',
                    ];
                })
                ->sortByDesc('total_pinjam')
                ->take(5)
                ->values();

            // Wajib Dilihat (semua buku urut berdasarkan total peminjaman)
            $wajibDilihat = Buku::select('judul', DB::raw('MIN(no_regis) as regis_terendah'))
                ->groupBy('judul')
                ->get()
                ->map(function ($buku) {
                    $totalPinjam = Peminjaman::where('judul', $buku->judul)
                        ->where('pinjam', 'terima')
                        ->count();

                    $data = Buku::where('no_regis', $buku->regis_terendah)->first();

                    return (object) [
                        'no_regis' => $data->no_regis ?? '-',
                        'judul' => $data->judul ?? '-',
                        'total_pinjam' => $totalPinjam ?? 0,
                        'file_cover' => $data?->file_cover ?? 'default/default-book.png',
                        'file_buku' => $data?->file_buku ?? 'default/default-book.png',
                        'stok' => $data->stok ?? 0,
                        'pengarang' => $data->pengarang ?? '-',
                        'penerbit' => $data->penerbit ?? '-',
                        'keterangan' => $data->keterangan ?? '-',
                        'tahun' => $data->tahun ?? '-',
                    ];
                })
                ->sortByDesc('total_pinjam')
                ->values();

            // Konten sering dilihat: bisa pakai updated_at sebagai simulasi
            $kontenSeringDilihat = Buku::orderByDesc('updated_at')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'no_regis' => $item->regis_terendah ?? '-',
                        'judul' => $item->judul ?? '-',
                        'total_pinjam' => $totalPinjam ?? 0,
                        'file_cover' => $item?->file_cover ?? 'default/default-book.png',
                        'file_buku' => $item?->file_buku ?? 'default/default-book.png',
                        'stok' => $item->stok ?? 0,
                        'pengarang' => $item->pengarang ?? '-',
                        'penerbit' => $item->penerbit ?? '-',
                        'keterangan' => $item->keterangan ?? '-',
                        'tahun' => $item->tahun ?? '-',
                        'view_count' => 1 // Ganti dengan field aslinya jika ada
                    ];
                });

            return view('pages.guru.dashboard', compact(
                'dataPengunjung',
                'dataPeminjam',
                'dataKembali',
                'bukuFavorit',
                'wajibDilihat',
                'kontenSeringDilihat'
            ));
        } elseif ($roles == 'admin') {
            $today = Carbon::today();

            // Statistik harian
            $dataPengunjung  = Kunjungan::whereDate('created_at', $today)->count();
            $dataPeminjam    = Peminjaman::whereDate('tgl_pinjam', $today)->count();
            $dataKembali     = Peminjaman::whereDate('tgl_kembali', $today)->count();

            // Buku Terfavorit: berdasarkan jumlah peminjaman per judul
            $bukuFavorit = Buku::select('judul', DB::raw('MIN(no_regis) as regis_terendah'), DB::raw('CAST(SUM(stok) AS UNSIGNED) as total_stok'))
                ->groupBy('judul')
                ->get()
                ->map(function ($buku) {
                    // Hitung total peminjaman berdasarkan judul
                    $totalPinjam = Peminjaman::where('judul', $buku->judul)
                        ->where('pinjam', 'terima')
                        ->count();

                    // Ambil cover dari no_regis terendah (karena biasanya data awal)
                    $data = Buku::where('no_regis', $buku->regis_terendah)->first();

                    return (object) [
                        'no_regis' => $buku->regis_terendah ?? '-',
                        'judul' => $buku->judul ?? '-',
                        'total_pinjam' => $totalPinjam ?? 0,
                        'file_cover' => $data?->file_cover ?? 'default/default-book.png',
                        'file_buku' => $data?->file_buku ?? 'default/default-book.png',
                        'stok' => $data->stok ?? 0,
                        'pengarang' => $data->pengarang ?? '-',
                        'penerbit' => $data->penerbit ?? '-',
                        'keterangan' => $data->keterangan ?? '-',
                        'tahun' => $data->tahun ?? '-',
                    ];
                })
                ->sortByDesc('total_pinjam')
                ->take(5)
                ->values();

            // Wajib Dilihat (semua buku urut berdasarkan total peminjaman)
            $wajibDilihat = Buku::select('judul', DB::raw('MIN(no_regis) as regis_terendah'))
                ->groupBy('judul')
                ->get()
                ->map(function ($buku) {
                    $totalPinjam = Peminjaman::where('judul', $buku->judul)
                        ->where('pinjam', 'terima')
                        ->count();

                    $data = Buku::where('no_regis', $buku->regis_terendah)->first();

                    return (object) [
                        'no_regis' => $data->no_regis ?? '-',
                        'judul' => $data->judul ?? '-',
                        'total_pinjam' => $totalPinjam ?? 0,
                        'file_cover' => $data?->file_cover ?? 'default/default-book.png',
                        'file_buku' => $data?->file_buku ?? 'default/default-book.png',
                        'stok' => $data->stok ?? 0,
                        'pengarang' => $data->pengarang ?? '-',
                        'penerbit' => $data->penerbit ?? '-',
                        'keterangan' => $data->keterangan ?? '-',
                        'tahun' => $data->tahun ?? '-',
                    ];
                })
                ->sortByDesc('total_pinjam')
                ->values();

            // Konten sering dilihat: bisa pakai updated_at sebagai simulasi
            $kontenSeringDilihat = Buku::orderByDesc('updated_at')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'no_regis' => $item->regis_terendah ?? '-',
                        'judul' => $item->judul ?? '-',
                        'total_pinjam' => $totalPinjam ?? 0,
                        'file_cover' => $item?->file_cover ?? 'default/default-book.png',
                        'file_buku' => $item?->file_buku ?? 'default/default-book.png',
                        'stok' => $item->stok ?? 0,
                        'pengarang' => $item->pengarang ?? '-',
                        'penerbit' => $item->penerbit ?? '-',
                        'keterangan' => $item->keterangan ?? '-',
                        'tahun' => $item->tahun ?? '-',
                        'view_count' => 1 // Ganti dengan field aslinya jika ada
                    ];
                });

            return view('pages.admin.dashboard', compact(
                'dataPengunjung',
                'dataPeminjam',
                'dataKembali',
                'bukuFavorit',
                'wajibDilihat',
                'kontenSeringDilihat'
            ));
        } else {
            return view('auth.signin');
        }
    }
}
