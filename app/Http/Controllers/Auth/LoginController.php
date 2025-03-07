<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class LoginController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.signin');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password'], 'status' => 'aktif'])) {
            $request->session()->regenerate();
            flash()->flash(
                'success',
                'Selamat Datang ' . Auth::user()->fullname,
                ['timeout' => 1500],
                'Login Sukses'
            );
            return redirect()->intended('/apps/dashboard');
        }

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password'], 'status' => 'tidak aktif'])) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            flash()->flash(
                'warning',
                'Akun Anda Sudah Tidak Aktif, Hubungi Admin atau Petugas',
                [/*options */],
                'Login Gagal'
            );

            return back()->withInput($request->only('username'));
        }

        flash()->flash(
            'error',
            'Username atau Password Salah',
            [/*options */],
            'Login Gagal'
        );
        return back()->withInput($request->only('username'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        flash()->flash(
            'info',
            'Akun Anda Berhasil Dikeluarkan',
            [/*options */],
            'Logout Sukses'
        );

        return redirect('/sign-in');
    }
}
