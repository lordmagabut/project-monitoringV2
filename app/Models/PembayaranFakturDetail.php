<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranFakturDetail extends Model
{
    protected $table = 'pembayaran_faktur_detail';

    protected $fillable = [
        'id_pembayaran',
        'id_faktur_detail',
        'jumlah_dibayar',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranFaktur::class, 'id_pembayaran');
    }

    public function fakturDetail()
    {
        return $this->belongsTo(FakturDetail::class, 'id_faktur_detail');
    }
}
