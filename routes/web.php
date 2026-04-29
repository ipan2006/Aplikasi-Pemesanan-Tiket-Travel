<?php

use App\Http\Controllers\PaymentController;
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

// Home
Route::get('/', fn() => view('dashboard'))->name('home');

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Jadwal
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');


// ==================== USER ====================
Route::middleware('auth')->group(function () {

    Route::post('/pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/tiket', [TiketController::class, 'index'])->name('tiket.index');
    Route::get('/tiket/{id}', [TiketController::class, 'show'])->name('tiket.show'); // cukup 1

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    Route::get('/data-diri/create/{id}', [DataDiriController::class, 'create'])->name('data_diri.create');
    Route::post('/data-diri/store', [DataDiriController::class, 'store'])->name('data_diri.store');

    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
    Route::post('/pembayaran/{id}', [PembayaranController::class, 'store'])->name('pembayaran.store');
});


// ==================== ADMIN ====================
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/pemesanan', [AdminController::class, 'pemesanan'])->name('admin.pemesanan.index');
    Route::put('/pemesanan/{id}/acc', [AdminController::class, 'acc'])->name('admin.pemesanan.acc');
    Route::put('/pemesanan/{id}/reject', [AdminController::class, 'reject'])->name('admin.pemesanan.reject');
    Route::put('/pemesanan/{id}/ubah-jadwal', [AdminController::class, 'ubahJadwal'])->name('admin.pemesanan.ubahJadwal');
    Route::put('/pemesanan/{id}/batal', [AdminController::class, 'batal'])->name('admin.pemesanan.batal');
    Route::delete('/pemesanan/{id}', [AdminController::class, 'hapus'])->name('admin.pemesanan.hapus');

    Route::get('/jadwal', [AdminController::class, 'jadwal'])->name('admin.jadwal.index');
    Route::put('/jadwal/{id}/jam', [AdminController::class, 'updateJam'])->name('admin.jadwal.updateJam');

    Route::get('/rute', [AdminController::class, 'rute'])->name('admin.rute.index');
});


// ==================== STATIC ====================
Route::view('/rute', 'rute.index')->name('rute.index');


// ==================== AUTH ====================
require __DIR__.'/auth.php';

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


// ==================== MIDTRANS ====================
Route::get('/pay', [PaymentController::class, 'pay']);

Route::post('/callback', [PaymentController::class, 'callback'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/payment/{order_id}', [PaymentController::class, 'show']);

Route::get('/test-update/{id}', function($id) {
    $trx = \App\Models\Transaction::where('order_id', $id)->first();
    $trx->status = 'success';
    $trx->save();
    return 'updated';
});

// ==================== EXTRA ====================
Route::post('/pemesanan/{id}/upload-bukti', [AdminController::class, 'uploadBukti'])
    ->name('pemesanan.uploadBukti'); 
    