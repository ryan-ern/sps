<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $roles = Auth::user()->role;
        if ($roles == 'siswa') {
            return view('pages.siswa.dashboard');
        } elseif ($roles == 'guru') {
            return view('pages.guru.dashboard');
        } elseif ($roles == 'admin') {
            return view('pages.admin.dashboard');
        } else {
            return view('auth.signin');
        }
    }
}
