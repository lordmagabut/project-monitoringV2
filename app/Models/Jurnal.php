<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table = 'jurnal';

    protected $fillable = [
        'no_jurnal',
        'id_perusahaan',
        'tanggal',
        'keterangan',
        'tipe',
        'total',
        'ref_id',
        'ref_table'
    ];

    public function details()
    {
        return $this->hasMany(JurnalDetail::class, 'jurnal_id');
    }

    public function perusahaan()
{
    return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
}

}
