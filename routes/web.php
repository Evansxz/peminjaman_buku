<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::middleware('auth')->group(function () {
    Route::resource('buku', BukuController::class);
    
    Route::get('/peminjaman', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/peminjaman', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/cari-buku/{kode_buku}', [TransaksiController::class, 'cariBuku'])->name('transaksi.cariBuku');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});