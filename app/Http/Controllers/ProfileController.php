<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->user()->nisn);

        return view('pages.siswa.profil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find(auth()->user()->nisn);

        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|unique:users,email,' . $user->nisn . ',nisn',
                'fullname' => 'required|string',
                'kelas' => 'required|string',
                'old_password' => 'nullable|string',
                'new_password' => 'nullable|string|min:6',
            ],
            [
                'email.required' => 'Email harus diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'fullname.required' => 'Nama lengkap harus diisi.',
                'fullname.string' => 'Nama lengkap harus berupa teks.',
                'kelas.required' => 'Kelas harus diisi.',
                'kelas.string' => 'Kelas harus berupa teks.',
                'old_password.string' => 'Password lama harus berupa teks.',
                'new_password.string' => 'Password baru harus berupa teks.',
                'new_password.min' => 'Password baru minimal 6 karakter.',
            ]
        );

        if ($validator->fails()) {
            $errorMessages = implode(', ', $validator->errors()->all());
            flash()->flash(
                'error',
                'Ubah data gagal: ' . $errorMessages,
                [],
                'Ubah Data Gagal'
            );
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->email = $request->email;
        $user->kelas = $request->kelas;
        $user->fullname = $request->fullname;

        if ($request->filled('old_password') && $request->filled('new_password')) {
            if (!Hash::check($request->input('old_password'), $user->password)) {
                flash()->flash(
                    'error',
                    'Password Lama Tidak Sesuai',
                    [],
                    'Pembaruan Data Gagal'
                );
                return redirect()->route('profil.read');
            }

            $user->password = bcrypt($request->input('new_password'));
        }

        $user->save();

        flash()->flash(
            'success',
            'Profil berhasil diperbarui',
            [],
            'Pembaruan Data Sukses'
        );

        return redirect()->route('profil.read');
    }
}
