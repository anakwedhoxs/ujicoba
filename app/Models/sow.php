<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sow extends Model
{
    protected $fillable = [
        'inventaris_id',
        'tanggal_penggunaan',
        'tanggal_perbaikan',
        'nomor_perbaikan',
        'helpdesk',
        'form',
        'hostname',
        'divisi',
        'keterangan',
        'pic',
        'foto',
        'status',
    ];

    // 🔹 CAST TANGGAL SUPAYA FORMAT Excel BISA BERFUNGSI
    protected $casts = [ 'tanggal_penggunaan' => 'date', 'tanggal_perbaikan' => 'date', // 🔹 Supaya otomatis jadi boolean & date 'is_archived' => 'boolean', 'arsip_at' => 'datetime', ];
        'tanggal_penggunaan' => 'date',
        'tanggal_perbaikan' => 'date',
        // 🔹 Supaya otomatis jadi boolean & date
        'is_archived' => 'boolean',
        'arsip_at' => 'datetime',
    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

}
