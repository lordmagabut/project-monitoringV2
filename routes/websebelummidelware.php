<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PemberiKerjaController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\PoController;
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/po/{id}/print', [PoController::class, 'print'])->name('po.print');
Route::resource('po', PoController::class);

Route::resource('coa', CoaController::class);

Route::resource('barang', BarangController::class);

Route::get('/proyek/{id}/delete', [ProyekController::class, 'destroy'])->name('proyek.destroy');
Route::get('/proyek/{id}/edit', [ProyekController::class, 'edit'])->name('proyek.edit');
Route::post('/proyek/{id}/update', [ProyekController::class, 'update'])->name('proyek.update');
Route::get('/proyek', [ProyekController::class, 'index'])->name('proyek.index');
Route::get('/proyek/create', [ProyekController::class, 'create'])->name('proyek.create');
Route::post('/proyek/store', [ProyekController::class, 'store'])->name('proyek.store');

Route::get('/pemberiKerja', [PemberiKerjaController::class, 'index'])->name('pemberiKerja.index');
Route::get('/pemberiKerja/create', [PemberiKerjaController::class, 'create'])->name('pemberiKerja.create');
Route::post('/pemberiKerja/store', [PemberiKerjaController::class, 'store'])->name('pemberiKerja.store');
Route::get('/pemberiKerja/{id}/edit', [PemberiKerjaController::class, 'edit'])->name('pemberiKerja.edit');
Route::post('/pemberiKerja/{id}/update', [PemberiKerjaController::class, 'update'])->name('pemberiKerja.update');
Route::get('/pemberiKerja/{id}/delete', [PemberiKerjaController::class, 'destroy'])->name('pemberiKerja.destroy');

Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
Route::post('/supplier/{id}/update', [SupplierController::class, 'update'])->name('supplier.update');
Route::get('/supplier/{id}/delete', [SupplierController::class, 'destroy'])->name('supplier.destroy');

Route::get('/perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.index');
Route::get('/perusahaan/create', [PerusahaanController::class, 'create'])->name('perusahaan.create');
Route::post('/perusahaan/store', [PerusahaanController::class, 'store'])->name('perusahaan.store');
Route::get('/perusahaan/{id}/edit', [PerusahaanController::class, 'edit'])->name('perusahaan.edit');
Route::post('/perusahaan/{id}/update', [PerusahaanController::class, 'update'])->name('perusahaan.update');
Route::get('/perusahaan/{id}/delete', [PerusahaanController::class, 'destroy'])->name('perusahaan.destroy');


Route::get('/', function () {
    return view('dashboard');
});
