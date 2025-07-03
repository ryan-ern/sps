<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KontenDigital;
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
        function hitungProgres($hariIni, $targetKemarin)
        {
            if ($targetKemarin > 0) {
                $persen = round(($hariIni / $targetKemarin) * 100);
                return min($persen, 100); // Maksimal 100%
            }
            return ($hariIni > 0) ? 100 : 0; // Jika target 0, tapi hari ini ada data, maka 100%
        }

        $roles = Auth::user()->role; //DASHBOARD SISWA
        if ($roles == 'siswa') {
            $today = Carbon::today();

            $filterJenis = request()->get('jenis'); // 'referensi', 'paket', 'digital'
            $search = request()->get('search');

            // ---------- Kunjungan ----------
            $kunjunganHariIni = Kunjungan::where('nisn', Auth::user()->nisn)
                ->whereDate('created_at', $today)
                ->exists();

            // ---------- BUKU ----------
            $bukuQuery = Buku::query();

            if (in_array($filterJenis, ['referensi', 'paket'])) {
                $bukuQuery->where('jenis', $filterJenis);
            }

            if ($search) {
                $bukuQuery->where('judul', 'like', '%' . $search . '%')->orWhere('no_regis', 'like', '%' . $search . '%')->orWhere('pengarang', 'like', '%' . $search . '%')->orWhere('penerbit', 'like', '%' . $search . '%');
            }

            $bukuFavorit = $bukuQuery->select('judul', DB::raw('MIN(no_regis) as regis_terendah'), DB::raw('CAST(SUM(stok) AS UNSIGNED) as total_stok'))
                ->groupBy('judul')
                ->get()
                ->map(function ($buku) {
                    $totalPinjam = Peminjaman::where('judul', $buku->judul)
                        ->where('pinjam', 'terima')
                        ->count();

                    $data = Buku::where('no_regis', $buku->regis_terendah)->first();

                    return (object)[
                        'no_regis' => $data->no_regis ?? '-',
                        'judul' => $data->judul ?? '-',
                        'total_pinjam' => $totalPinjam ?? 0,
                        'file_cover' => $data?->file_cover ?? 'default/default-book.png',
                        'file_buku' => $data?->file_buku ?? 'default/default-book.png',
                        'stok' => $data->stok ?? 0,
                        'jenis' => $data->jenis ?? '-',
                        'pengarang' => $data->pengarang ?? '-',
                        'penerbit' => $data->penerbit ?? '-',
                        'keterangan' => $data->keterangan ?? '-',
                        'tahun' => $data->tahun ?? '-',
                        'created_at' => $data->created_at ?? now()->subYears(10)
                    ];
                })->filter(function ($item) {
                    return $item->total_pinjam > 0;
                })->sortByDesc(function ($item) {
                    return [$item->total_pinjam, $item->created_at]; // Urutkan berdasarkan total_pinjam dan created_at
                })
                ->take(5)
                ->values();

            $bukuTerbaru = Buku::latest() //untuk menampilkan daftar buku terbaru dan menggabungkannya dengan daftar buku favorit
                ->when(in_array($filterJenis, ['referensi', 'paket']), function ($query) use ($filterJenis) {
                    $query->where('jenis', $filterJenis);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where('judul', 'like', '%' . $search . '%');
                })
                ->get()
                ->map(function ($data) {
                    return (object)[
                        'no_regis' => $data->no_regis ?? '-',
                        'judul' => $data->judul ?? '-',
                        'total_pinjam' => Peminjaman::where('judul', $data->judul)->count(),
                        'file_cover' => $data->file_cover ?? 'default/default-book.png',
                        'file_buku' => $data->file_buku ?? 'default/default-book.png',
                        'stok' => $data->stok ?? 0,
                        'jenis' => $data->jenis ?? '-',
                        'pengarang' => $data->pengarang ?? '-',
                        'penerbit' => $data->penerbit ?? '-',
                        'keterangan' => $data->keterangan ?? '-',
                        'tahun' => $data->tahun ?? '-',
                    ];
                });

            $allBuku = collect($bukuFavorit)
                ->merge($bukuTerbaru)
                ->unique('judul')
                ->values();

            // ---------- KONTEN DIGITAL ----------
            $kontenQuery = KontenDigital::query();

            if ($search) {
                $kontenQuery->where('judul', 'like', '%' . $search . '%');
            }

            $kontenSeringDilihat = (clone $kontenQuery)->orderByDesc('dilihat') //untuk mengambil dan menampilkan konten digital yang paling sering dilihat oleh pengguna
                ->orderByDesc('created_at')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'id' => $item->id ?? '-',
                        'judul' => $item->judul ?? '-',
                        'jenis' => $item->jenis ?? '-',
                        'file_path' => $item->file_path ? asset('storage/' . $item->file_path) : null,
                        'url' => $item->url ?? null,
                        'pengarang' => $item->pengarang ?? '-',
                        'penerbit' => $item->penerbit ?? '-',
                        'cover' => $item->cover ?? 'default/default-book.png',
                        'dilihat' => $item->dilihat ?? 0,
                    ];
                });

            $kontenTerbaru = KontenDigital::orderByDesc('created_at')
                ->when($search, function ($query) use ($search) {
                    $query->where('judul', 'like', '%' . $search . '%');
                })
                ->take(10)
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'id' => $item->id ?? '-',
                        'judul' => $item->judul ?? '-',
                        'jenis' => $item->jenis ?? '-',
                        'file_path' => $item->file_path ? asset('storage/' . $item->file_path) : null,
                        'url' => $item->url ?? null,
                        'pengarang' => $item->pengarang ?? '-',
                        'penerbit' => $item->penerbit ?? '-',
                        'cover' => $item->cover ?? 'default/default-book.png',
                        'dilihat' => $item->dilihat ?? 0,
                    ];
                });

            $allKonten = collect($kontenSeringDilihat)
                ->merge($kontenTerbaru)
                ->unique('judul')
                ->values();

            // ---------- Wajib Dilihat ----------
            $wajibDilihat = collect();

            if ($filterJenis === 'referensi' || $filterJenis === 'paket') {
                $wajibDilihat = $allBuku;
            } elseif ($filterJenis === 'digital') {
                $wajibDilihat = $allKonten;
            } else {
                // Selang-seling buku dan konten
                $maxLength = max($allBuku->count(), $allKonten->count());
                for ($i = 0; $i < $maxLength; $i++) {
                    if (isset($allBuku[$i])) {
                        $wajibDilihat->push($allBuku[$i]);
                    }
                    if (isset($allKonten[$i])) {
                        $wajibDilihat->push($allKonten[$i]);
                    }
                }
            }

            return view('pages.siswa.dashboard', compact(
                'bukuFavorit',
                'wajibDilihat',
                'kontenSeringDilihat',
                'kunjunganHariIni'
            ));
        } elseif ($roles == 'guru') {   // DASHBOARD GURU
            $search = request()->get('search');
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            // Statistik harian
            // Pengunjung
            $dataPengunjung = Kunjungan::whereDate('created_at', $today)->count();
            $dataPengunjungKemarin = Kunjungan::whereDate('created_at', $yesterday)->count();

            // Peminjam
            $dataPeminjam = Peminjaman::whereDate('tgl_pinjam', $today)->count();
            $dataPeminjamKemarin = Peminjaman::whereDate('tgl_pinjam', $yesterday)->count();

            // Kembali
            $dataKembali = Peminjaman::whereDate('tgl_kembali', $today)->count();
            $dataKembaliKemarin = Peminjaman::whereDate('tgl_kembali', $yesterday)->count();


            $persenPengunjung = hitungProgres($dataPengunjung, $dataPengunjungKemarin);
            $persenPeminjam   = hitungProgres($dataPeminjam, $dataPeminjamKemarin);
            $persenKembali    = hitungProgres($dataKembali, $dataKembaliKemarin);

            // ---------- BUKU ----------
            $bukuQuery = Buku::query();
            $bukuFavorit = $bukuQuery->select('judul', DB::raw('MIN(no_regis) as regis_terendah'), DB::raw('CAST(SUM(stok) AS UNSIGNED) as total_stok'))
                ->groupBy('judul')
                ->get()
                ->map(function ($buku) {
                    $totalPinjam = Peminjaman::where('judul', $buku->judul)
                        ->where('pinjam', 'terima')
                        ->count();

                    $data = Buku::where('no_regis', $buku->regis_terendah)->first();

                    return (object)[
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
                        'created_at' => $data->created_at ?? now()->subYears(10)
                    ];
                })->filter(function ($item) {
                    return $item->total_pinjam > 0;
                })
                ->sortByDesc(function ($item) {
                    return [$item->total_pinjam, $item->created_at];
                })
                ->take(5)
                ->values();

            $bukuTerbaru = Buku::latest()
                ->get()
                ->map(function ($data) {
                    return (object)[
                        'no_regis' => $data->no_regis ?? '-',
                        'judul' => $data->judul ?? '-',
                        'total_pinjam' => Peminjaman::where('judul', $data->judul)->count(),
                        'file_cover' => $data->file_cover ?? 'default/default-book.png',
                        'file_buku' => $data->file_buku ?? 'default/default-book.png',
                        'stok' => $data->stok ?? 0,
                        'pengarang' => $data->pengarang ?? '-',
                        'penerbit' => $data->penerbit ?? '-',
                        'keterangan' => $data->keterangan ?? '-',
                        'tahun' => $data->tahun ?? '-',
                    ];
                });

            $allBuku = collect($bukuFavorit)
                ->merge($bukuTerbaru)
                ->unique('judul')
                ->values();

            // ---------- KONTEN DIGITAL ----------
            $kontenQuery = KontenDigital::where('judul', 'like', '%' . $search . '%')->orWhere('jenis', 'like', '%' . $search . '%')->orWhere('url', 'like', '%' . $search . '%')->orWhere('pengarang', 'like', '%' . $search . '%');

            $kontenSeringDilihat = (clone $kontenQuery)->orderByDesc('dilihat')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'id' => $item->id ?? '-',
                        'nuptk' => $item->nuptk ?? '-',
                        'judul' => $item->judul ?? '-',
                        'jenis' => $item->jenis ?? '-',
                        'file_path' => $item->file_path ? asset('storage/' . $item->file_path) : null,
                        'url' => $item->url ?? null,
                        'pengarang' => $item->pengarang ?? '-',
                        'penerbit' => $item->penerbit ?? '-',
                        'cover' => $item->cover ?? 'default/default-book.png',
                        'dilihat' => $item->dilihat ?? 0,
                    ];
                });

            $kontenTerbaru = KontenDigital::latest()
                ->take(10)
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'id' => $item->id ?? '-',
                        'nuptk' => $item->nuptk ?? '-',
                        'judul' => $item->judul ?? '-',
                        'jenis' => $item->jenis ?? '-',
                        'file_path' => $item->file_path ? asset('storage/' . $item->file_path) : null,
                        'url' => $item->url ?? null,
                        'pengarang' => $item->pengarang ?? '-',
                        'penerbit' => $item->penerbit ?? '-',
                        'cover' => $item->cover ?? 'default/default-book.png',
                        'dilihat' => $item->dilihat ?? 0,
                    ];
                });

            $userNuptk = auth()->user()->nisn;

            // Gabung dan unikkan dulu
            $gabunganKonten = collect($kontenSeringDilihat)
                ->merge($kontenTerbaru)
                ->unique('judul');

            // Ambil konten milik sendiri
            $kontenSendiri = $gabunganKonten->filter(function ($item) use ($userNuptk) {
                return $item->nuptk == $userNuptk;
            });

            // Ambil konten lainnya (bukan milik sendiri)
            $kontenLain = $gabunganKonten->reject(function ($item) use ($userNuptk) {
                return $item->pengarang == $userNuptk;
            });

            // Gabungkan ulang dengan milik sendiri di awal
            $allKonten = $kontenSendiri->merge($kontenLain)->values();


            // ---------- Wajib Dilihat ----------
            $wajibDilihat = collect();

            // Selang-seling buku dan konten
            $maxLength = $allKonten->count();
            for ($i = 0; $i < $maxLength; $i++) {
                // if (isset($allBuku[$i])) {
                //     $wajibDilihat->push($allBuku[$i]);
                // }
                if (isset($allKonten[$i])) {
                    $wajibDilihat->push($allKonten[$i]);
                }
            }

            return view('pages.guru.dashboard', compact(
                'dataPengunjung',
                'dataPeminjam',
                'dataKembali',
                'persenPengunjung',
                'persenPeminjam',
                'persenKembali',
                'bukuFavorit',
                'wajibDilihat',
                'kontenSeringDilihat'
            ));
        } elseif ($roles == 'admin') {  //DASHBOARD ADMIN
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
            $lastMonth = Carbon::now()->subMonth()->month;
            $lastYear = Carbon::now()->subYear()->year;

            // Statistik harian
            // Pengunjung
            $dataPengunjung = Kunjungan::whereDate('created_at', $today)->count();
            $dataPengunjungKemarin = Kunjungan::whereDate('created_at', $yesterday)->count();

            // Peminjam
            $dataPeminjam = Peminjaman::whereDate('tgl_pinjam', $today)->count();
            $dataPeminjamKemarin = Peminjaman::whereDate('tgl_pinjam', $yesterday)->count();

            // Kembali
            $dataKembali = Peminjaman::whereDate('tgl_kembali', $today)->count();
            $dataKembaliKemarin = Peminjaman::whereDate('tgl_kembali', $yesterday)->count();

            $denda = Peminjaman::whereDate('tgl_pinjam', $today)->sum('denda');
            $dendabulan = Peminjaman::whereMonth('tgl_pinjam', $month)->sum('denda');
            $dendatahun = Peminjaman::whereYear('tgl_pinjam', $year)->sum('denda');

            $dendaKemarin = Peminjaman::whereDate('tgl_pinjam', $yesterday)->sum('denda');
            $dendabulanKemarin = Peminjaman::whereMonth('tgl_pinjam', $lastMonth)->sum('denda');
            $dendatahunKemarin = Peminjaman::whereYear('tgl_pinjam', $lastYear)->sum('denda');

            $persenPengunjung = hitungProgres($dataPengunjung, $dataPengunjungKemarin);
            $persenPeminjam   = hitungProgres($dataPeminjam, $dataPeminjamKemarin);
            $persenKembali    = hitungProgres($dataKembali, $dataKembaliKemarin);

            // ---------- BUKU ----------
            $bukuQuery = Buku::query();

            $bukuFavorit = $bukuQuery->select('judul', DB::raw('MIN(no_regis) as regis_terendah'), DB::raw('CAST(SUM(stok) AS UNSIGNED) as total_stok'))
                ->groupBy('judul')
                ->get()
                ->map(function ($buku) {
                    $totalPinjam = Peminjaman::where('judul', $buku->judul)
                        ->where('pinjam', 'terima')
                        ->count();

                    $data = Buku::where('no_regis', $buku->regis_terendah)->first();

                    return (object)[
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
                        'created_at' => $data->created_at ?? now()->subYears(10)
                    ];
                })->filter(function ($item) {
                    return $item->total_pinjam > 0;
                })
                ->sortByDesc(function ($item) {
                    return [$item->total_pinjam, $item->created_at]; // Urutkan berdasarkan total_pinjam dan created_at
                })
                ->take(5)
                ->values();

            $bukuTerbaru = Buku::latest()
                ->get()
                ->map(function ($data) {
                    return (object)[
                        'no_regis' => $data->no_regis ?? '-',
                        'judul' => $data->judul ?? '-',
                        'total_pinjam' => Peminjaman::where('judul', $data->judul)->count(),
                        'file_cover' => $data->file_cover ?? 'default/default-book.png',
                        'file_buku' => $data->file_buku ?? 'default/default-book.png',
                        'stok' => $data->stok ?? 0,
                        'pengarang' => $data->pengarang ?? '-',
                        'penerbit' => $data->penerbit ?? '-',
                        'keterangan' => $data->keterangan ?? '-',
                        'tahun' => $data->tahun ?? '-',
                    ];
                });

            $allBuku = collect($bukuFavorit)
                ->merge($bukuTerbaru)
                ->unique('judul')
                ->values();

            // ---------- KONTEN DIGITAL ----------
            $kontenQuery = KontenDigital::query();

            $kontenSeringDilihat = (clone $kontenQuery)->orderByDesc('dilihat')->orderByDesc('created_at')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'judul' => $item->judul ?? '-',
                        'jenis' => $item->jenis ?? '-',
                        'file_path' => $item->file_path ? asset('storage/' . $item->file_path) : null,
                        'url' => $item->url ?? null,
                        'pengarang' => $item->pengarang ?? '-',
                        'penerbit' => $item->penerbit ?? '-',
                        'cover' => $item->cover ?? 'default/default-book.png',
                        'dilihat' => $item->dilihat ?? 0,
                    ];
                });

            $kontenTerbaru = KontenDigital::orderByDesc('created_at')
                ->take(10)
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'judul' => $item->judul ?? '-',
                        'jenis' => $item->jenis ?? '-',
                        'file_path' => $item->file_path ? asset('storage/' . $item->file_path) : null,
                        'url' => $item->url ?? null,
                        'pengarang' => $item->pengarang ?? '-',
                        'penerbit' => $item->penerbit ?? '-',
                        'cover' => $item->cover ?? 'default/default-book.png',
                        'dilihat' => $item->dilihat ?? 0,
                    ];
                });

            $allKonten = collect($kontenSeringDilihat)
                ->merge($kontenTerbaru)
                ->unique('judul')
                ->values();

            // ---------- Wajib Dilihat ----------
            $wajibDilihat = collect();

            // Selang-seling buku dan konten
            $maxLength = max($allBuku->count(), $allKonten->count());
            for ($i = 0; $i < $maxLength; $i++) {
                if (isset($allBuku[$i])) {
                    $wajibDilihat->push($allBuku[$i]);
                }
                if (isset($allKonten[$i])) {
                    $wajibDilihat->push($allKonten[$i]);
                }
            }

            return view('pages.admin.dashboard', compact(
                'dataPengunjung',
                'dataPengunjungKemarin',
                'dataPeminjam',
                'dataPeminjamKemarin',
                'dataKembali',
                'dataKembaliKemarin',
                'persenPengunjung',
                'persenPeminjam',
                'persenKembali',
                'bukuFavorit',
                'wajibDilihat',
                'kontenSeringDilihat',
                'denda',
                'dendaKemarin',
                'dendabulan',
                'dendabulanKemarin',
                'dendatahun',
                'dendatahunKemarin',
            ));
        } else {
            return view('auth.signin');
        }
    }
}
