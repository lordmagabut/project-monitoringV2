<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RabScheduleDetail extends Model
{
    use HasFactory;

    protected $table = 'rab_schedule_detail';

    protected $fillable = [
        'rab_header_id',
        'proyek_id',
        'minggu_ke',
        'bobot_mingguan',
    ];

    public function rabHeader()
    {
        return $this->belongsTo(RabHeader::class, 'rab_header_id');
    }

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
