<?php

use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::resource('products', ProductController::class);

});

require __DIR__ . '/auth.php';
