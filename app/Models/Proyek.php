<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyek';

    protected $fillable = [
        'perusahaan_id',
        'nama_proyek',
        'pemberi_kerja_id',
        'no_spk',
        'nilai_spk',
        'file_spk',
        'jenis_proyek',
    ];

    public function pemberiKerja()
    {
        return $this->belongsTo(PemberiKerja::class);
    }
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

}
