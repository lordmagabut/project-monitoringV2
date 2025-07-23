<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AhspHeader extends Model
{
    protected $table = 'ahsp_header';
    protected $fillable = [
        'kode_pekerjaan', 'nama_pekerjaan', 'satuan',
        'kategori_id', 'total_harga', 'is_locked'
    ];

    public function kategori()
    {
        return $this->belongsTo(AhspKategori::class, 'kategori_id');
    }

    public function details()
    {
        return $this->hasMany(AhspDetail::class, 'ahsp_id');
    }
}
