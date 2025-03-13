<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $referensi = Buku::where('jenis', 'referensi')->paginate($perPage);
        $paket = Buku::where('jenis', 'paket')->paginate($perPage);

        return view('pages.admin.buku', compact('referensi', 'paket', 'perPage'));
    }


    public function store(Request $request)
    {
        $fileBukuPath = '-';
        $fileCoverPath = '-';
        $stok = (int) $request->stok;
        $gagal = 0;

        // Simpan file jika ada
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

        for ($i = 0; $i < $stok; $i++) {
            $no_regis_baru = $request->no_regis + $i;

            // Cek apakah nomor registrasi sudah ada
            if (Buku::where('no_regis', $no_regis_baru)->exists()) {
                $gagal++;
                flash()->flash(
                    'warning',
                    'Nomor Registrasi ' . $no_regis_baru . ' Sudah digunakan!',
                    [],
                    'Tambah Data Dengan Nomor Regis ' . $no_regis_baru . ' Gagal Ditambahkan'
                );
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
                'Sebanyak ' . ($stok - $gagal) . ' buku berhasil ditambahkan, tetapi ' . $gagal . ' buku gagal karena nomor registrasi sudah digunakan.',
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
}
