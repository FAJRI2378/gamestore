<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PesanController;

// Halaman Utama: langsung redirect ke halaman login
Route::get('/', function () {
    return view('auth.login');
});

// =============================
// Autentikasi (Laravel UI)
// =============================
Auth::routes(); 

// =============================
// Dashboard berdasarkan role
// =============================
// Dashboard untuk user biasa
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Dashboard untuk admin
Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');

// Dashboard untuk manager, dengan middleware auth dan role:manager
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
});

// Logout menggunakan POST (disarankan)
Route::post('/logout', [LogoutController::class, 'signout'])->name('logout');

// =============================
// Route yang hanya bisa diakses oleh pengguna yang sudah login
// =============================
Route::middleware(['auth'])->group(function () {

    // Resource route produk (CRUD)
    Route::resource('produk', ProdukController::class);

    // Routes untuk keranjang
    Route::prefix('keranjang')->name('keranjang.')->group(function () {
        Route::get('/', [KeranjangController::class, 'index'])->name('index');
        Route::post('/store', [KeranjangController::class, 'store'])->name('store');
        Route::post('/update', [KeranjangController::class, 'update'])->name('update');
        Route::post('/remove', [KeranjangController::class, 'remove'])->name('remove');
        Route::post('/clear', [KeranjangController::class, 'clear'])->name('clear');
        Route::get('/checkout', [KeranjangController::class, 'checkout'])->name('checkout');
    });

    // Routes transaksi
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionsController::class, 'index'])->name('index');
        Route::get('/history', [TransactionsController::class, 'history'])->name('history');
        Route::get('/print/{id}', [TransactionsController::class, 'print'])->name('print');
        Route::get('/{id}/receipt', [TransactionsController::class, 'printReceipt'])->name('receipt');
        Route::put('/{id}/updatestatus', [TransactionsController::class, 'updateStatus'])->name('updateStatus');
    });

    // Resource route kategori (CRUD)
    Route::resource('kategori', KategoriController::class);

    // Route game store
    Route::get('/game-store', [ProdukController::class, 'gameStore'])->name('game.store');

    // Routes pesan
    Route::prefix('pesan')->name('pesan.')->group(function () {
        Route::get('/', [PesanController::class, 'index'])->name('index');
        Route::get('/create', [PesanController::class, 'create'])->name('create');
        Route::post('/', [PesanController::class, 'store'])->name('store');
       Route::get('/produk/{produk}/play', [ProdukController::class, 'playGame'])->name('produk.play');

    });

    // Produk Live Search
    Route::get('/produk/live-search', [ProdukController::class, 'liveSearch'])->name('produk.live-search');

});
