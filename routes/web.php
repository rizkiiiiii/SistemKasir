<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Depan langsung lempar ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Dashboard (Pake Controller DashboardController biar widget PBD muncul)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. Group Route yang butuh Login (Auth)
Route::middleware('auth')->group(function () {
    // Profile Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fitur Kasir (POS)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');

    // Fitur Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Fitur Produk (CRUD)
    Route::resource('products', ProductController::class);
});

// 4. INI BARIS SAKTI YANG MANGGIL KODINGAN PANJANG LO TADI
require __DIR__ . '/auth.php';
