<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BebasPustakaController extends Controller
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

        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $dateRange = $request->input('dates');

        // Query awal: hanya user non-admin
        $query = User::where('role', '!=', 'admin')
            ->where('status', 'aktif')
            ->with(['peminjaman', 'pengembalian']);

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tanggal pembuatan user (jika dibutuhkan)
        if ($dateRange) {
            $dates = explode(" - ", $dateRange);
            if (count($dates) === 2) {
                $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Ambil data berdasarkan pagination
        if ($perPage === 'Semua') {
            $users = $query->get();
        } else {
            $users = $query->paginate($perPage);
        }

        // Tambahkan atribut status bebas pustaka
        $users->transform(function ($user) {
            $user->dipinjam = $user->peminjaman->count();
            $user->dikembalikan = $user->pengembalian->count();
            $user->status = $user->dipinjam == $user->dikembalikan ? 'Sesuai' : 'Tidak Sesuai';
            return $user;
        });

        return view('pages.admin.bebas-pustaka', compact('users', 'perPage', 'search', 'dateRange'));
    }

    public function cardDownload(Request $request, $nisn)
    {
        // Query dasar
        $anggota = User::where('nisn', $nisn)
            ->where('status', 'aktif')
            ->where('role', '!=', 'admin')
            ->first();
        // Buat PDF
        $pdf = Pdf::loadView('pages.admin.kartu-bebas-pustaka', compact('anggota'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Kartu Bebas Pustaka ' . $anggota->fullname . '.pdf');
    }
}
