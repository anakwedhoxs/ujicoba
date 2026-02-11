<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SowArsipItem extends Model
{
    protected $fillable = [
        'sow_arsip_id',
        'inventaris_id',
        'tanggal_penggunaan',
        'tanggal_perbaikan',
        'helpdesk',
        'form',
        'nomor_perbaikan',
        'hostname',
        'divisi',
        'keterangan',
        'pic',

    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

}
