<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\UpdatesRekap; // ✅ Tambahkan trait

class Sow extends Model
{
    use UpdatesRekap; // ✅ Pakai trait untuk update rekap otomatis

    protected $fillable = [
        'inventaris_id',
        'pic_id',
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

    public function pic(): BelongsTo
    {
        return $this->belongsTo(Pic::class);
    }

    public function hostname(): BelongsTo
    {
        return $this->belongsTo(Hostname::class);
    }

    /**
     * 🔹 Event booted untuk trigger rekap otomatis
     */
    protected static function booted()
    {
        static::created(fn ($model) => $model->triggerRekap());
        static::updated(fn ($model) => $model->triggerRekap());
        static::deleted(fn ($model) => $model->triggerRekap());
        
    }
}