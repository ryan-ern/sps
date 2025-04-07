<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input request
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
        $referensiQuery = Buku::where('jenis', 'referensi')->orderBy('created_at', 'desc');
        $paketQuery = Buku::where('jenis', 'paket')->orderBy('created_at', 'desc');

        // Filter berdasarkan pencarian
        if ($search) {
            $referensiQuery
                ->where('judul', 'like', "%{$search}%")
                ->orWhere('no_regis', 'like', "%{$search}%")
                ->orWhere('pengarang', 'like', "%{$search}%")
                ->orWhere('penerbit', 'like', "%{$search}%")
                ->orWhere('tahun', 'like', "%{$search}%");
            $paketQuery
                ->where('judul', 'like', "%{$search}%")
                ->orWhere('no_regis', 'like', "%{$search}%")
                ->orWhere('pengarang', 'like', "%{$search}%")
                ->orWhere('penerbit', 'like', "%{$search}%")
                ->orWhere('tahun', 'like', "%{$search}%");
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

        return view('pages.admin.buku', compact('referensi', 'paket', 'perPage', 'search', 'dateRange'));
    }


    private function buatRentang($nomorRegistrasi)
    {
        $rentang = [];
        $start = $nomorRegistrasi[0];
        $end = $start;

        for ($i = 1; $i < count($nomorRegistrasi); $i++) {
            if ($nomorRegistrasi[$i] == $end + 1) {
                $end = $nomorRegistrasi[$i];
            } else {
                $rentang[] = $start == $end ? $start : "$start-$end";
                $start = $nomorRegistrasi[$i];
                $end = $start;
            }
        }

        $rentang[] = $start == $end ? $start : "$start-$end";

        return $rentang;
    }

    public function store(Request $request)
    {
        $maxStok = Validator::make($request->all(), [
            'stok' => 'required|integer|max:10000',
        ], [
            'stok.required' => 'Stok buku harus diisi',
            'stok.integer' => 'Stok buku harus berupa angka',
            'stok.max' => 'Stok buku tidak boleh lebih dari 10000',
        ]);
        if ($maxStok->fails()) {
            flash()->flash(
                'error',
                'Stok buku tidak boleh lebih dari 10.000',
                [],
                'Tambah Data Gagal'
            );
            return redirect()->route('data-buku.read');
        }
        $fileBukuPath = '-';
        $fileCoverPath = '-';
        $stok = (int) $request->stok;
        $gagal = 0;

        // Simpan file jika ada
        if ($request->hasFile('file_buku')) {
            $fileBuku = $request->file('file_buku');
            $fileBukuName = time() . '_buku_' . $fileBuku->getClientOriginalName();
            $fileBukuPath = $fileBuku->storeAs('uploads/buku', $fileBukuName, 'public');
        } else {
            $fileBukuPath = 'default/default-book.png';
        }

        if ($request->hasFile('file_cover')) {
            $fileCover = $request->file('file_cover');
            $fileCoverName = time() . '_cover_' . $fileCover->getClientOriginalName();
            $fileCoverPath = $fileCover->storeAs('uploads/cover', $fileCoverName, 'public');
        } else {
            $fileCoverPath = 'default/default-book.png';
        }

        for ($i = 0; $i < $stok; $i++) {
            $no_regis_baru = $request->no_regis + $i;

            // Cek apakah nomor registrasi sudah ada
            if (Buku::where('no_regis', $no_regis_baru)->exists()) {
                $gagal++;
                $gagalRegistrasi[] = $no_regis_baru;
                continue; // Lewati iterasi jika nomor registrasi sudah ada
            }

            // Simpan buku baru
            $buku = new Buku();
            $buku->no_regis = $no_regis_baru;
            $buku->judul = $request->judul;
            $buku->pengarang = $request->pengarang;
            $buku->penerbit = $request->penerbit;
            $buku->tahun = $request->tahun;
            $buku->stok = $request->stok;
            $buku->keterangan = $request->keterangan;
            $buku->jenis = $request->jenis;
            $buku->file_buku = $fileBukuPath;
            $buku->file_cover = $fileCoverPath;

            $buku->save();
        }

        if (!empty($gagalRegistrasi)) {
            sort($gagalRegistrasi); // Urutkan array
            $rentangGagal = $this->buatRentang($gagalRegistrasi); // Buat rentang dari array gagal
        }

        // Jika ada kegagalan, tampilkan pesan dengan rentang
        if ($gagal > 0) {
            flash()->priority(1)->flash(
                'warning',
                'Sebanyak ' . $gagal . ' buku gagal ditambahkan karena nomor registrasi sudah digunakan dalam  id: ' . implode(', ', $rentangGagal),
                [],
                'Tambah Data Sebagian Berhasil'
            );
        }
        // Tentukan pesan sukses dan gagal
        if ($gagal === $stok) {
            // Semua gagal
            flash()->priority(1)->flash(
                'error',
                'Gagal menambahkan semua buku. Semua nomor registrasi sudah digunakan!',
                [],
                'Tambah Data Gagal'
            );
        } elseif ($gagal > 0) {
            // Sebagian gagal
            flash()->priority(1)->flash(
                'warning',
                'Sebanyak ' . ($stok - $gagal) . ' buku berhasil ditambahkan, tetapi ' . $gagal . ' buku gagal karena nomor registrasi sudah digunakan dalam rentang id ' . implode(', ', $rentangGagal),
                [],
                'Tambah Data Sebagian Berhasil'
            );
        } else {
            // Semua berhasil
            flash()->flash(
                'success',
                'Berhasil menambahkan ' . $stok . ' buku.',
                [],
                'Tambah Data Sukses'
            );
        }

        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $judul = $request->judul;
        $stok = $request->stok;

        // Cek apakah ada buku yang cocok
        $buku = Buku::where('judul', $judul)->where('stok', $stok)->get();

        if ($buku->isEmpty()) {
            flash()->flash(
                'danger',
                'Data buku dengan judul ' . $judul . ' tidak ditemukan.',
                [],
                'Hapus Data Gagal'
            );
            return redirect()->back();
        }

        // Hapus semua buku yang sesuai
        Buku::where('judul', $judul)->where('stok', $stok)->delete();

        flash()->flash(
            'success',
            'Semua buku dengan judul ' . $judul . ' berhasil dihapus.',
            [],
            'Hapus Data Sukses'
        );

        return redirect()->back();
    }


    public function update(Request $request, Buku $buku)
    {
        $bukuYangSama = Buku::where('judul', $buku->judul)->where('stok', $buku->stok)->get();

        $fileBukuPath = Buku::where('judul', $buku->judul)->where('stok', $buku->stok)->first()->file_buku ?? '-';
        $fileCoverPath = Buku::where('judul', $buku->judul)->where('stok', $buku->stok)->first()->file_cover ?? '-';

        if ($request->hasFile('file_buku')) {
            $fileBuku = $request->file('file_buku');
            $fileBukuName = time() . '_buku_' . $fileBuku->getClientOriginalName();
            $fileBukuPath = $fileBuku->storeAs('uploads/buku', $fileBukuName, 'public');
        }

        if ($request->hasFile('file_cover')) {
            $fileCover = $request->file('file_cover');
            $fileCoverName = time() . '_cover_' . $fileCover->getClientOriginalName();
            $fileCoverPath = $fileCover->storeAs('uploads/cover', $fileCoverName, 'public');
        }


        foreach ($bukuYangSama as $b) {
            $b->update([
                'judul' => $request->judul,
                'pengarang' => $request->pengarang,
                'penerbit' => $request->penerbit,
                'tahun' => $request->tahun,
                'stok' => $request->stok,
                'keterangan' => $request->keterangan,
                'jenis' => $request->jenis,
                'file_buku' => $fileBukuPath,
                'file_cover' => $fileCoverPath
            ]);
        }

        flash()->flash(
            'success',
            'Berhasil memperbarui semua buku dengan judul ' . $buku->judul,
            [],
            'Perbarui Data Sukses'
        );

        return redirect()->route('data-buku.read');
    }
}
