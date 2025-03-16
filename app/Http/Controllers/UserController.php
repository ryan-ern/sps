<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
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

        // Query dasar untuk User
        $DataQuery = User::where('role', '!=', 'admin');

        // Filter berdasarkan pencarian
        if ($search) {
            $DataQuery
                ->where('fullname', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%")
                ->orWhere('kelas', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
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
            $users = $DataQuery->paginate(1000000);
        } else {
            $users = $DataQuery->paginate($perPage);
        }

        return view('pages.admin.anggota', compact('users', 'perPage', 'search', 'dateRange'));
    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nisn' => 'required|unique:users,nisn',
            'fullname' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'kelas' => 'required',
            'password' => 'required|min:6',
            'role' => 'required',
            'status' => 'required',
        ], [
            'nisn.unique' => 'NISN sudah terdaftar',
            'nisn.required' => 'NISN harus diisi',
            'fullname.required' => 'Nama lengkap harus diisi',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'kelas.required' => 'Kelas harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role harus diisi',
            'status.required' => 'Status harus diisi',
        ]);

        if ($validasi->fails()) {
            $errorMessages = implode(', ', $validasi->errors()->all());
            flash()->flash(
                'error',
                'Data anggota gagal ditambahkan: ' . $errorMessages,
                [],
                'Tambah Data Gagal'
            );
            return redirect()->route('anggota.read')->withErrors($validasi)->withInput();
        }

        // Simpan data ke dalam database
        User::create([
            'nisn' => $request->nisn,
            'fullname' => $request->fullname,
            'username' => $request->username,
            'kelas' => $request->kelas,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        flash()->flash(
            'success',
            'Data anggota berhasil ditambahkan!',
            [],
            'Tambah Data Sukses'
        );

        return redirect()->route('anggota.read');
    }


    public function update(Request $request)
    {
        // Cari anggota berdasarkan NISN
        $anggota = User::where('nisn', $request->nisn)->first();

        // Jika anggota tidak ditemukan, tampilkan pesan error
        if (!$anggota) {
            flash()->flash(
                'error',
                'Anggota tidak ditemukan.',
                [],
                'Ubah Data Gagal'
            );
            return redirect()->route('anggota.read');
        }

        // Validasi data input
        $validasi = Validator::make($request->all(), [
            'nisn' => 'required',
            'fullname' => 'required',
            'username' => 'required',
            'kelas' => 'required',
            'password' => 'nullable|min:6',
            'role' => 'required',
            'status' => 'required',
            'email' => 'nullable|email|unique:users,email',
        ], [
            'nisn.required' => 'NISN harus diisi',
            'fullname.required' => 'Nama lengkap harus diisi',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
            'kelas.required' => 'Kelas harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role harus diisi',
            'status.required' => 'Status harus diisi',
            'email.unique' => 'Email sudah digunakan, silakan pilih yang lain.',
        ]);

        if ($validasi->fails()) {
            $errorMessages = implode(', ', $validasi->errors()->all());
            flash()->flash(
                'error',
                'Data anggota gagal diubah: ' . $errorMessages,
                [],
                'Ubah Data Gagal'
            );
            return redirect()->route('anggota.read')->withErrors($validasi)->withInput();
        }

        // Perbarui data anggota kecuali password
        $anggota->update($request->except(['password', '_token', '_method']));

        // Jika password diisi, update dengan bcrypt
        if ($request->filled('password')) {
            $anggota->update([
                'password' => bcrypt($request->password),
            ]);
        }

        // Kirim pesan sukses
        flash()->flash(
            'success',
            'Data anggota berhasil diubah!',
            [],
            'Ubah Data Sukses'
        );

        return redirect()->route('anggota.read');
    }


    public function destroy($anggota)
    {
        $anggota = User::find($anggota);
        $anggota->delete();
        flash()->flash(
            'success',
            'Data anggota ' . $anggota->fullname . ' berhasil dihapus!',
            [],
            'Hapus Data Sukses'
        );
        return redirect()->route('anggota.read');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        try {
            Excel::import(new UsersImport, $request->file('file'));
            return redirect()->route('anggota.read')->with('success', 'Data anggota berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('anggota.read')->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function exportSample()
    {
        try {
            return Excel::download(new UsersExport, 'contoh_data_anggota.xlsx');
        } catch (\Exception $e) {
            return redirect()->route('anggota.read')->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
}
