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

});
