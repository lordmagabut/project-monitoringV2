<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranFaktur extends Model
{
    protected $table = 'pembayaran_faktur';

    protected $fillable = [
        'id_faktur',
        'tanggal_pembayaran',
        'metode',
        'jumlah',
        'id_perusahaan',
        'id_proyek',
        'keterangan',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
    
    public function faktur()
    {
        return $this->belongsTo(\App\Models\Faktur::class, 'id_faktur');
    }
    

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function detail()
    {
        return $this->hasMany(PembayaranFakturDetail::class, 'id_pembayaran');
    }
}
