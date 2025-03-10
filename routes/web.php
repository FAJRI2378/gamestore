<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProdukController;
use App\Http\Middleware\UserAccess;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KeranjangController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Autentikasi bawaan Laravel
Auth::routes();

// Middleware untuk user biasa
// Middleware untuk user biasa

    Route::get('/home', [HomeController::class, 'index'])->name('home');
// Middleware untuk admin
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');


// Middleware untuk manager
Route::middleware(['auth', 'user-access:manager'])->group(function () {
    Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
});

// Route Logout
Route::post('/logout', [LogoutController::class, 'signout'])->name('logout');

// Middleware untuk Produk (hanya user yang login bisa mengakses)
Route::middleware(['auth'])->group(function () {
    // Route resource untuk produk yang akan otomatis menangani seluruh CRUD
    Route::resource('produk', ProdukController::class);
});


Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/store', [KeranjangController::class, 'store'])->name('keranjang.store');
Route::post('/keranjang/update', [KeranjangController::class, 'update'])->name('keranjang.update');
Route::post('/keranjang/remove', [KeranjangController::class, 'remove'])->name('keranjang.remove');
Route::post('/keranjang/clear', [KeranjangController::class, 'clear'])->name('keranjang.clear');
Route::get('/keranjang/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');
