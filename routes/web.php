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
    Route::resource('po', PoController::class);

    // COA
    Route::resource('coa', CoaController::class);

    // Barang
    Route::resource('barang', BarangController::class);

    // Proyek
    Route::get('/proyek/{id}/delete', [ProyekController::class, 'destroy'])->name('proyek.destroy');
    Route::get('/proyek/{id}/edit', [ProyekController::class, 'edit'])->name('proyek.edit');
    Route::post('/proyek/{id}/update', [ProyekController::class, 'update'])->name('proyek.update');
    Route::get('/proyek', [ProyekController::class, 'index'])->name('proyek.index');
    Route::get('/proyek/create', [ProyekController::class, 'create'])->name('proyek.create');
    Route::post('/proyek/store', [ProyekController::class, 'store'])->name('proyek.store');

    // Pemberi Kerja
    Route::get('/pemberiKerja', [PemberiKerjaController::class, 'index'])->name('pemberiKerja.index');
    Route::get('/pemberiKerja/create', [PemberiKerjaController::class, 'create'])->name('pemberiKerja.create');
    Route::post('/pemberiKerja/store', [PemberiKerjaController::class, 'store'])->name('pemberiKerja.store');
    Route::get('/pemberiKerja/{id}/edit', [PemberiKerjaController::class, 'edit'])->name('pemberiKerja.edit');
    Route::post('/pemberiKerja/{id}/update', [PemberiKerjaController::class, 'update'])->name('pemberiKerja.update');
    Route::get('/pemberiKerja/{id}/delete', [PemberiKerjaController::class, 'destroy'])->name('pemberiKerja.destroy');

    // Supplier
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::post('/supplier/{id}/update', [SupplierController::class, 'update'])->name('supplier.update');
    Route::get('/supplier/{id}/delete', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    // Perusahaan
    Route::resource('perusahaan', \App\Http\Controllers\PerusahaanController::class)
    ->middleware('cek_akses_perusahaan');

});
