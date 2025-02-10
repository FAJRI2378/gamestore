<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProdukController;
use App\Http\Middleware\UserAccess;
use Illuminate\Support\Facades\Auth;

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
