<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users'; // Pastikan tabel yang digunakan sudah sesuai
    protected $fillable = [
        'username',
        'password',
        'akses_perusahaan',
        'buat_perusahaan',
        'edit_perusahaan',
        'hapus_perusahaan',
        'akses_pemberikerja',
        'buat_pemberikerja',
        'edit_pemberikerja',
        'hapus_pemberikerja',
        'akses_proyek',
        'buat_proyek',
        'edit_proyek',
        'hapus_proyek',
        'akses_supplier',
        'buat_supplier',
        'edit_supplier',
        'hapus_supplier',
        'akses_barang',
        'buat_barang',
        'edit_barang',
        'hapus_barang',
        'akses_coa',
        'buat_coa',
        'edit_coa',
        'hapus_coa',
        'akses_po',
        'buat_po',
        'edit_po',
        'hapus_po',
        'akses_user_manager', 
    ];

    protected $hidden = [
        'password',
    ];
}
