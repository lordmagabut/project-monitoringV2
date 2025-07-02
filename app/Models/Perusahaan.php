<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan'; // Nama tabel di database

    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'email',
        'no_telp',
        'npwp',
        'tipe_perusahaan',
    ];
}
