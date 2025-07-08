<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'tipe_id',
        'coa_persediaan_id',
        'coa_beban_id'
    ];

    public function tipe()
    {
        return $this->belongsTo(TipeBarangJasa::class, 'tipe_id');
    }
}
