<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HsdMaterial extends Model
{
    protected $table = 'hsd_material';
    protected $fillable = ['kode', 'nama', 'satuan', 'harga_satuan', 'keterangan'];
}
