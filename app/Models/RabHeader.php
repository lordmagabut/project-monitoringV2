<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabHeader extends Model
{
    protected $table = 'rab_header';

    protected $fillable = [
        'proyek_id',
        'kode',
        'kode_sort',
        'deskripsi',
        'nilai',
        'bobot',
    ];

    // Relasi ke proyek
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    // Relasi ke detail-detailnya
    public function details()
    {
        return $this->hasMany(RabDetail::class, 'rab_header_id');
    }
    
    public function rabDetails()
    {
        return $this->hasMany(RabDetail::class, 'rab_header_id');
    }

    public function schedule()
    {
        return $this->hasOne(\App\Models\RabSchedule::class);
    }
}
