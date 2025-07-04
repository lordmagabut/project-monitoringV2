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
        'akses_proyek',
        'akses_barang',
        'akses_coa',
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
