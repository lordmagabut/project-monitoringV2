<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabDetail extends Model
{
    protected $table = 'rab_detail';

    protected $fillable = [
        'proyek_id',
        'rab_header_id',
        'kode',
        'kode_sort',
        'deskripsi',
        'area',
        'spesifikasi',
        'satuan',
        'volume',
        'harga_satuan',
        'total',
        'bobot',
    ];

    // Relasi ke header (sub-induk)
    public function header()
    {
        return $this->belongsTo(RabHeader::class, 'rab_header_id');
    }

    // Relasi ke proyek
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
