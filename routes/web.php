<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PlatoController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductController;

Route::middleware(['auth'])->group(function () {
    Route::get('/mis-productos', [ProductController::class, 'index'])->name('mis-productos');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/mis-platos', [PlatoController::class, 'index'])->name('mis-platos');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/mis-menus', [MenuController::class, 'index'])->name('mis-menus');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/pdf/mis-platos', [PdfController::class, 'userPlatos'])
        ->name('pdf.user.platos');
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
