<?php

namespace App\Http\Controllers;

use App\Models\KontenDigital;
use Illuminate\Http\Request;

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
}
