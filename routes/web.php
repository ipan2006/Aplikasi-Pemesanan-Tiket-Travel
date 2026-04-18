<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\DataDiriController;
use App\Http\Controllers\EtiketController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Auth\AuthenticatedSessionController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama langsung ke dashboard (home = dashboard)
Route::get('/', fn() => view('dashboard'))->name('home');

// Dashboard (hanya bisa diakses setelah login & verifikasi)
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Jadwal (publik, bisa diakses tanpa login)
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

// ==================== USER ROUTES ====================
Route::middleware('auth')->group(function () {
    // Pemesanan
    Route::post('/pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');

    // Profile bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tiket & Riwayat
    Route::get('/tiket', [TiketController::class, 'index'])->name('tiket.index');
    Route::get('/tiket/{id}', [TiketController::class, 'show'])->name('tiket.show');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    // Data Diri
    Route::get('/data-diri/create/{id}', [DataDiriController::class, 'create'])->name('data_diri.create');
    Route::post('/data-diri/store', [DataDiriController::class, 'store'])->name('data_diri.store');

    // Pembayaran
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
    Route::post('/pembayaran/{id}', [PembayaranController::class, 'store'])->name('pembayaran.store');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Pemesanan
    Route::get('/pemesanan', [AdminController::class, 'pemesanan'])->name('admin.pemesanan.index');
    Route::put('/pemesanan/{id}/acc', [AdminController::class, 'acc'])->name('admin.pemesanan.acc');
    Route::put('/pemesanan/{id}/reject', [AdminController::class, 'reject'])->name('admin.pemesanan.reject');
    Route::put('/pemesanan/{id}/ubah-jadwal', [AdminController::class, 'ubahJadwal'])->name('admin.pemesanan.ubahJadwal');
    Route::put('/pemesanan/{id}/batal', [AdminController::class, 'batal'])->name('admin.pemesanan.batal');
    Route::delete('/pemesanan/{id}', [AdminController::class, 'hapus'])->name('admin.pemesanan.hapus');

    // Jadwal & Rute
    Route::get('/jadwal', [AdminController::class, 'jadwal'])->name('admin.jadwal.index');
    Route::put('/jadwal/{id}/jam', [AdminController::class, 'updateJam'])->name('admin.jadwal.updateJam');
    Route::get('/rute', [AdminController::class, 'rute'])->name('admin.rute.index');
});

// ==================== STATIC ROUTES ====================
Route::view('/rute', 'rute.index')->name('rute.index');

// Route login/register bawaan Breeze
require __DIR__.'/auth.php';

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');



Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/pemesanan', [AdminController::class, 'pemesanan'])->name('admin.pemesanan.index');
    Route::put('/pemesanan/{id}/acc', [AdminController::class, 'acc'])->name('admin.pemesanan.acc');
    Route::put('/pemesanan/{id}/reject', [AdminController::class, 'reject'])->name('admin.pemesanan.reject');
    Route::put('/pemesanan/{id}/ubah-jadwal', [AdminController::class, 'ubahJadwal'])->name('admin.pemesanan.ubahJadwal');
    Route::put('/pemesanan/{id}/batal', [AdminController::class, 'batal'])->name('admin.pemesanan.batal');
    Route::delete('/pemesanan/{id}/hapus', [AdminController::class, 'hapus'])->name('admin.pemesanan.hapus');
});

Route::post('/pemesanan/{id}/upload-bukti', [AdminController::class, 'uploadBukti'])
    ->name('pemesanan.uploadBukti');

   Route::middleware(['auth'])->group(function () {
    Route::get('/tiket/{id}', [TiketController::class, 'show'])->name('tiket.show');
});
