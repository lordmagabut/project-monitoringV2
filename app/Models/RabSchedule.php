<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabSchedule extends Model
{
    protected $table = 'rab_schedule';

    protected $fillable = [
        'proyek_id',
        'rab_header_id',
        'minggu_ke',
        'durasi',
    ];

    // Relasi ke proyek
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    // Relasi ke header RAB (sub-induk)
    public function header()
    {
        return $this->belongsTo(RabHeader::class, 'rab_header_id');
    }

}
