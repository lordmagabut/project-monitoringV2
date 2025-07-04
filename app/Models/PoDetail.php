<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoDetail extends Model
{
    protected $table = 'po_detail';

    protected $fillable = [
        'po_id', 'kode_item', 'uraian', 'qty', 'uom', 'harga', 'diskon_persen', 'diskon_rupiah', 'ppn_persen', 'ppn_rupiah', 'total'
    ];

    public function po()
    {
        return $this->belongsTo(Po::class, 'po_id');
    }
}
