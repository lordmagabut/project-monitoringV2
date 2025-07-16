<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PemberiKerjaController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\PoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\FakturController;

// Route Login - Tidak Perlu Proteksi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route yang Diproteksi Login
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Manager Route
    Route::get('/user-manager', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/{id}/edit-permission', [UserController::class, 'editPermission'])->name('user.edit.permission');
    Route::post('/user/{id}/update-permission', [UserController::class, 'updatePermission'])->name('user.update.permission');

    // Route Create User
    Route::get('/user-manager/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user-manager/store', [UserController::class, 'store'])->name('user.store');

    // Reset Password
    Route::get('/user/{id}/reset-password', [UserController::class, 'showResetPasswordForm'])->name('user.reset.password');
    Route::post('/user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset.password.store');
    
    // Hapus User
    Route::delete('/user/{id}/delete', [UserController::class, 'destroy'])->name('user.destroy');

    // PO
    Route::get('/po/{id}/print', [PoController::class, 'print'])->name('po.print');
    Route::put('/po/revisi/{id}', [PoController::class, 'revisi'])->name('po.revisi');
    Route::resource('po', \App\Http\Controllers\PoController::class)
    ->middleware('cek_akses_po');

    // Faktur Pembelian
    Route::get('/faktur/create-from-po/{po}', [FakturController::class, 'createFromPo'])->name('faktur.createFromPo');
    Route::get('/faktur/create', [FakturController::class, 'create'])->name('faktur.create');
    Route::post('/faktur/store', [FakturController::class, 'store'])->name('faktur.store');
    Route::get('/faktur', [FakturController::class, 'index'])->name('faktur.index');
    Route::get('/faktur/{id}', [FakturController::class, 'show'])->name('faktur.show');
    Route::delete('/faktur/{id}', [FakturController::class, 'destroy'])->name('faktur.destroy');
    Route::post('/faktur/{id}/revisi', [App\Http\Controllers\FakturController::class, 'revisi'])->name('faktur.revisi');
    Route::post('/faktur/{id}/approve', [\App\Http\Controllers\FakturController::class, 'approve'])
    ->name('faktur.approve');


    // COA
    Route::resource('coa', \App\Http\Controllers\CoaController::class)
    ->middleware('cek_akses_coa');

    // Barang
    Route::resource('barang', \App\Http\Controllers\BarangController::class)
    ->middleware('cek_akses_barang');

    // Proyek
    Route::resource('proyek', \App\Http\Controllers\ProyekController::class)
    ->middleware('cek_akses_proyek');

    // Pemberi Kerja
    Route::resource('pemberiKerja', PemberiKerjaController::class)->middleware('cek_akses_pemberi_kerja');
    
    // Supplier
    Route::resource('supplier', \App\Http\Controllers\SupplierController::class)
    ->middleware('cek_akses_supplier');

    // Perusahaan
    Route::resource('perusahaan', \App\Http\Controllers\PerusahaanController::class)
    ->middleware('cek_akses_perusahaan');

    // Jurnal
    Route::get('/jurnal/detail/{id}', [\App\Http\Controllers\JurnalController::class, 'showDetail'])->name('jurnal.showDetail');
    Route::resource('jurnal', \App\Http\Controllers\JurnalController::class)
    ->middleware('cek_akses_jurnal');


    //Buku Besar
    Route::get('/buku-besar', [\App\Http\Controllers\BukuBesarController::class, 'index'])->name('buku-besar.index');

    //Neraca dan Laba Rugi
    Route::get('/laporan/neraca', [\App\Http\Controllers\LaporanController::class, 'neraca'])->name('laporan.neraca');
    Route::get('/laporan/laba-rugi', [\App\Http\Controllers\LaporanController::class, 'labaRugi'])->name('laporan.labaRugi');



});
