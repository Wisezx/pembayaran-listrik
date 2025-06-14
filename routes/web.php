<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PembayaranController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PemakaianController::class, 'home'])->name('welcome');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::middleware(['role:petugas'])->group(function (){
        Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
        Route::post('/pelanggan/store', [PelangganController::class, 'store'])->name('pelanggan.store');
        Route::get('/pelanggan/edit/{id}', [PelangganController::class, 'edit'])->name('pelanggan.edit');
        Route::put('/pelanggan/edit/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
        Route::delete('/pelanggan/delete/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    });

    Route::middleware(['role:admin'])->group(function (){
        Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
        Route::get('/petugas/create', [PetugasController::class, 'create'])->name('petugas.create');
        Route::post('/petugas/store', [PetugasController::class, 'store'])->name('petugas.store');
        Route::get('/petugas/edit/{id}', [PetugasController::class, 'edit'])->name('petugas.edit');
        Route::put('/petugas/edit/{id}', [PetugasController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/delete/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');
    });

    Route::middleware(['role:admin'])->group(function (){
    Route::get('/tarif', [TarifController::class, 'index'])->name('tarif.index');
    Route::get('/tarif/create', [TarifController::class, 'create'])->name('tarif.create');
    Route::post('/Tarif/store', [TarifController::class, 'store'])->name('tarif.store');
    Route::get('/Tarif/edit/{id}', [TarifController::class, 'edit'])->name('tarif.edit');
    Route::put('/Tarif/edit/{id}', [TarifController::class, 'update'])->name('tarif.update');
    Route::delete('/Tarif/delete/{id}', [TarifController::class, 'destroy'])->name('tarif.destroy');

    Route::resource('pemakaian', PemakaianController::class)->except(['show']);
    //untuk generate laporan
    Route::get('/pemakaian/pdf', [PemakaianController::class, 'exportPdf'])->name('pemakaian.exportPdf');
});


    // Payment routes
    Route::middleware('role:petugas')->group(function(){
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/show/{NoKontrol}', [PembayaranController::class, 'show'])->name('pembayaran.show');
        Route::post('/pembayaran/bayar/{id}', [PembayaranController::class, 'bayar'])->name('pembayaran.bayar');
        // Add these routes to your existing routes
        Route::post('/pembayaran/bayar-bulk', [PembayaranController::class, 'bayarBulk'])->name('pembayaran.bayar-bulk');
        Route::get('/pembayaran/receipt/{id}', [PembayaranController::class, 'receipt'])->name('pembayaran.receipt');
    });

    Route::get('/api/pelanggan-info', [PemakaianController::class, 'getPelangganInfo']);
    // API route for fetching pelanggan info

});

require __DIR__.'/auth.php';
