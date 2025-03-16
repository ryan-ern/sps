<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
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
        $perPage = $request->input('per_page', 5);

        // Ambil nilai pencarian
        $search = $request->input('search');

        // Ambil tanggal range
        $dateRange = $request->input('dates');

        // Query dasar untuk Kunjungan
        $DataQuery = Kunjungan::query()->orderBy('created_at', 'desc');

        // Filter berdasarkan pencarian
        if ($search) {
            $DataQuery
                ->where('fullname', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%")
                ->orWhere('kelas', 'like', "%{$search}%");
        }

        // Filter berdasarkan rentang tanggal
        if ($dateRange) {
            $dates = explode(" - ", $dateRange);
            if (count($dates) === 2) {
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();

                $DataQuery->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Jika per_page adalah "Semua", tampilkan semua data
        if ($request->per_page == 'Semua') {
            $kunjungans = $DataQuery->paginate(1000000);
        } else {
            $kunjungans = $DataQuery->paginate($perPage);
        }

        return view('pages.admin.kunjungan', compact('kunjungans', 'perPage', 'search', 'dateRange'));
    }
}
