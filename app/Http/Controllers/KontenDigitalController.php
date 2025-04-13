<?php

namespace App\Http\Controllers;

use App\Models\KontenDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KontenDigitalController extends Controller
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

        // Query dasar untuk Konten Digital
        $kontenQuery = KontenDigital::where('nuptk', auth()->user()->nisn)->orderBy('created_at', 'desc');

        // Filter berdasarkan pencarian
        if ($search) {
            $kontenQuery
                ->where('judul', 'like', "%{$search}%")
                ->orWhere('pembuat', 'like', "%{$search}%")
                ->orWhere('jenis', 'like', "%{$search}%");
        }

        // Filter berdasarkan rentang tanggal
        if ($dateRange) {
            $dates = explode(" - ", $dateRange);
            if (count($dates) === 2) {
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();

                $kontenQuery->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Jika per_page adalah "Semua", tampilkan semua data
        if ($request->per_page == 'Semua') {
            $data = $kontenQuery->paginate(1000000);
        } else {
            $data = $kontenQuery->paginate($perPage);
        }

        return view('pages.guru.konten', compact('data', 'perPage', 'search', 'dateRange'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required|in:video,buku digital',
            'judul' => 'required|string|max:255',
            'pembuat' => 'required|string|max:255',
            'url' => 'nullable|url',
            'file_path' => 'nullable|file|mimes:pdf|max:10000',
            'nuptk' => 'required',
        ], [
            'nuptk.required' => 'NUPTK harus diisi.',
            'jenis.required' => 'Jenis konten harus dipilih.',
            'jenis.in' => 'Jenis konten harus berupa video atau buku digital.',
            'judul.required' => 'Judul konten harus diisi.',
            'judul.string' => 'Judul konten harus berupa teks.',
            'judul.max' => 'Judul konten maksimal 255 karakter.',
            'pembuat.required' => 'Nama pembuat harus diisi.',
            'pembuat.string' => 'Nama pembuat harus berupa teks.',
            'pembuat.max' => 'Nama pembuat maksimal 255 karakter.',
            'url.url' => 'URL harus berupa tautan yang valid.',
            'file_path.file' => 'File yang diunggah harus berupa file.',
            'file_path.mimes' => 'File harus berformat PDF.',
            'file_path.max' => 'Ukuran file maksimal 10MB.',
        ]);

        if ($validator->fails()) {
            $errorMessages = implode(', ', $validator->errors()->all());
            flash()->flash(
                'error',
                'Data Konten Digital Gagal disimpan, karena ' . $errorMessages,
                [],
                'Tambah Data Gagal'
            );
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $konten = new KontenDigital();
        $konten->jenis = $request->jenis;
        $konten->judul = $request->judul;
        $konten->pembuat = $request->pembuat;
        $konten->url = $request->url ?? null;
        $konten->nuptk = $request->nuptk;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_konten_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/konten_digital', $fileName, 'public');
            $konten->file_path = $filePath;
        } else {
            $konten->file_path = '-';
        }

        $konten->save();

        flash()->flash(
            'success',
            'Berhasil Menambah Konten "' . $konten->judul . '"',
            [],
            'Tambah Data Sukses'
        );

        return redirect()->back();
    }


    public function update(Request $request, $id)
    {
        $konten = KontenDigital::findOrFail($id);

        $request->validate([
            'jenis' => 'required|in:video,buku digital',
            'judul' => 'required|string|max:255',
            'pembuat' => 'required|string|max:255',
            'nuptk' => 'required',
            'url' => 'nullable|url',
            'file_path' => 'nullable|file|mimes:pdf|max:10000',
        ]);

        $konten->jenis = $request->jenis;
        $konten->judul = $request->judul;
        $konten->nuptk = $request->nuptk;
        $konten->pembuat = $request->pembuat;
        $konten->url = $request->url;

        if ($request->hasFile('file_path')) {
            // Hapus file lama jika ada
            if ($konten->file_path && Storage::disk('public')->exists($konten->file_path)) {
                Storage::disk('public')->delete($konten->file_path);
            }

            $file = $request->file('file_path');
            $fileName = time() . '_konten_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/konten_digital', $fileName, 'public');
            $konten->file_path = $filePath;
        }

        $konten->save();

        return redirect()->back()->with('success', 'Konten digital berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $konten = KontenDigital::findOrFail($id);

        if ($konten->file_path && Storage::disk('public')->exists($konten->file_path)) {
            Storage::disk('public')->delete($konten->file_path);
        }


        $konten->delete();
        flash()->flash(
            'success',
            'Berhasil Menghapus Konten ' . $konten->judul,
            [],
            'Hapus Data Sukses'
        );
        return redirect()->back();
    }
}
