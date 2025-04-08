<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->nisn . ',nisn',
            'fullname' => 'required|string',
            'kelas' => 'required|string',
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6',
        ]);

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
