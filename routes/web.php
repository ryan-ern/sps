<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::fallback(function () {
    return redirect('/pages/not-found');
});

Route::get('/pages/not-found', function () {
    return view('error.404');
})->name('not-found');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect('/apps/dashboard');
    });

    Route::get('/apps/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['role:siswa,guru,admin']);

    Route::post('/apps/data-buku/create', [BukuController::class, 'store'])->name('data-buku.create')->middleware(['role:admin']);
    Route::get('/apps/data-buku', [BukuController::class, 'index'])->name('data-buku.read')->middleware(['role:admin']);
    Route::put('/apps/data-buku/update/{buku}', [BukuController::class, 'update'])->name('data-buku.update')->middleware(['role:admin']);
    Route::delete('/apps/data-buku/delete', [BukuController::class, 'destroy'])->name('data-buku.delete')->middleware(['role:admin']);

    Route::post('/apps/anggota/create', [UserController::class, 'store'])->name('anggota.create')->middleware(['role:admin']);
    Route::get('/apps/anggota', [UserController::class, 'index'])->name('anggota.read')->middleware(['role:admin']);
    Route::put('/apps/anggota/update/{anggota}', [UserController::class, 'update'])->name('anggota.update')->middleware(['role:admin']);
    Route::delete('/apps/anggota/delete/{anggota}', [UserController::class, 'destroy'])->name('anggota.delete')->middleware(['role:admin']);
    Route::post('/anggota/import', [UserController::class, 'import'])->name('anggota.import');
    Route::get('/anggota/export-sample', [UserController::class, 'exportSample'])->name('anggota.exportSample');

    Route::get('/apps/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.read')->middleware(['role:admin']);

    Route::get('/apps/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.read')->middleware(['role:admin']);

    Route::get('/apps/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.read')->middleware(['role:admin']);

    Route::get('/apps/siswa', function () {
        return view('example');
    })->name('siswa')->middleware(['role:siswa']);
});

Route::get('/tables', function () {
    return view('tables');
})->name('tables')->middleware('auth');

Route::get('/wallet', function () {
    return view('wallet');
})->name('wallet')->middleware('auth');

Route::get('/RTL', function () {
    return view('RTL');
})->name('RTL')->middleware('auth');

Route::get('/profile', function () {
    return view('account-pages.profile');
})->name('profile')->middleware('auth');

Route::get('/signin', function () {
    return view('account-pages.signin');
})->name('signin');

Route::get('/signup', function () {
    return view('account-pages.signup');
})->name('signup')->middleware('guest');

Route::get('/sign-up', [RegisterController::class, 'create'])
    ->middleware('guest')
    ->name('sign-up');

Route::post('/sign-up', [RegisterController::class, 'store'])
    ->middleware('guest');

Route::get('/sign-in', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('sign-in');

Route::post('/sign-in', [LoginController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware('guest');

Route::get('/laravel-examples/user-profile', [ProfileController::class, 'index'])->name('users.profile')->middleware('auth');
Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('users.update')->middleware('auth');
Route::get('/laravel-examples/users-management', [UserController::class, 'index'])->name('users-management')->middleware('auth');
