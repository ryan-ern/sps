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
            $datas = $kontenQuery->paginate(1000000);
        } else {
            $datas = $kontenQuery->paginate($perPage);
        }

        return view('pages.guru.konten', compact('datas', 'perPage', 'search', 'dateRange'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required|in:video,buku digital',
            'judul' => 'required|string|max:255',
            'pembuat' => 'required|string|max:255',
            'url' => 'nullable|url',
            'file_path' => 'nullable|file|mimes:pdf|max:10000',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
            'cover.image' => 'Cover harus berupa gambar.',
            'cover.mimes' => 'Cover harus berformat JPEG, PNG, atau JPG.',
            'cover.max' => 'Ukuran cover maksimal 2MB.',
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
        $konten->nuptk = $request->nuptk;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_konten_' . $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9 ]/', '', $fileName);
            $extension = $file->getClientOriginalExtension();
            $filePath = $file->storeAs('uploads/konten_digital', $cleanName . '.' . $extension, 'public');
            $konten->file_path = $filePath;
            $konten->url = null;
        } else {
            $konten->url = $request->url;
            $konten->file_path = null;
        }

        if ($request->hasFile('cover')) {
            $fileCover = $request->file('cover');
            $coverName = time() . '_cover_' . $fileCover->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9 ]/', '', $coverName);
            $extension = $file->getClientOriginalExtension();
            $coverPath = $fileCover->storeAs('uploads/konten_cover', $cleanName . '.' . $extension, 'public');
        } else {
            $coverPath = 'default/default-book.png';
        }

        $konten->cover = $coverPath;

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
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_path' => 'nullable|file|mimes:pdf|max:10000',
        ]);

        $konten->jenis = $request->jenis;
        $konten->judul = $request->judul;
        $konten->nuptk = $request->nuptk;
        $konten->pembuat = $request->pembuat;

        if ($request->hasFile('file_path')) {
            // Hapus file lama jika ada
            if ($konten->file_path && Storage::disk('public')->exists($konten->file_path)) {
                Storage::disk('public')->delete($konten->file_path);
            }

            $file = $request->file('file_path');
            $fileName = time() . '_konten_' . $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9 ]/', '', $fileName);
            $extension = $file->getClientOriginalExtension();
            $filePath = $file->storeAs('uploads/konten_digital', $cleanName . '.' . $extension, 'public');
            $konten->file_path = $filePath;
            $konten->url = null;
        } else {
            $konten->url = $request->url;
            $konten->file_path = null;
        }

        if ($request->hasFile('cover')) {
            $fileCover = $request->file('cover');
            $coverName = time() . '_cover_' . $fileCover->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9 ]/', '', $coverName);
            $extension = $file->getClientOriginalExtension();
            $coverPath = $fileCover->storeAs('uploads/konten_cover', $coverName . '.' . $extension, 'public');
        } else {
            $coverPath = $konten->cover ?? 'default/default-book.png';
        }

        $konten->cover = $coverPath;

        $konten->save();
        flash()->flash(
            'success',
            'Konten digital ' . $konten->judul . ' berhasil diperbarui',
            [],
            'Perbarui Data Sukses'
        );
        return redirect()->back();
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

    public function tambahDilihat($id)
    {
        $konten = KontenDigital::findOrFail($id);
        $konten->increment('dilihat');
        return response()->json(['success' => true, 'dilihat' => $konten->dilihat]);
    }
}
