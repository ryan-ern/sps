<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $referensi = Buku::where('jenis', 'referensi')->get();
        $paket = Buku::where('jenis', 'paket')->get();
        return view('pages.admin.buku', [
            'referensi' => $referensi,
            'paket' => $paket
        ]);
    }

    public function store(Request $request)
    {
        // Menambahkan nomor registrasi yang bertambah setiap iterasi
        for ($i = 0; $i < $request->stok; $i++) {
            $buku = new Buku();

            $buku->no_regis = $request->no_regis + $i;
            $buku->judul = $request->judul;
            $buku->pengarang = $request->pengarang;
            $buku->penerbit = $request->penerbit;
            $buku->tahun = $request->tahun;
            $buku->stok = $request->stok;
            $buku->keterangan = $request->keterangan;
            $buku->jenis = $request->jenis;

            // Menyimpan file buku (hanya pertama kali)
            if ($i == 0 && $request->hasFile('file_buku')) {
                $fileBuku = $request->file('file_buku');
                $fileBukuName = time() . '_buku_' . $fileBuku->getClientOriginalName();
                $fileBukuPath = $fileBuku->storeAs('uploads/buku', $fileBukuName, 'public');
                $buku->file_buku = $fileBukuPath;
            }

            // Menyimpan file cover (hanya pertama kali)
            if ($i == 0 && $request->hasFile('file_cover')) {
                $fileCover = $request->file('file_cover');
                $fileCoverName = time() . '_cover_' . $fileCover->getClientOriginalName();
                $fileCoverPath = $fileCover->storeAs('uploads/cover', $fileCoverName, 'public');
                $buku->file_cover = $fileCoverPath;
            }

            $buku->save();
        }

        flash()->flash(
            'success',
            'Berhasil Menambahkan Buku',
            [/*options */],
            'Tambah Data Sukses'
        );
        return redirect()->back();
    }
}
