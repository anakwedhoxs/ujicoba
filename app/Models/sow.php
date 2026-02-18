<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Sow extends Model
{
    protected $fillable = [
        'inventaris_id',
        'pic_id', // ✅ tambahkan ini
        'tanggal_penggunaan',
        'tanggal_perbaikan',
        'nomor_perbaikan',
        'helpdesk',
        'form',
        'hostname',
        'divisi',
        'keterangan',
        'foto',
        'status',
    ];


    protected $casts = [
        'tanggal_penggunaan' => 'date',
        'tanggal_perbaikan'  => 'date',
        'is_archived'        => 'boolean',
        'arsip_at'           => 'datetime',
    ];


    public function inventaris(): BelongsTo
    {
        return $this->belongsTo(Inventaris::class);
    }


    // ✅ RELASI KE PIC
    public function pic(): BelongsTo
    {
        return $this->belongsTo(Pic::class);
    }
}





