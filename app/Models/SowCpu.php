<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventaris;
use App\Models\Pic;
use App\Models\Hostname;
use App\Traits\UpdatesRekap; 

class SowCpu extends Model
{
    use UpdatesRekap;

    protected $table = 'sow_cpu';
   protected $fillable = [
        'prosesor_id',
        'ram_id',
        'motherboard_id',
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



public function prosesor()
{
    return $this->belongsTo(Inventaris::class, 'prosesor_id');
}


public function ram()
{
    return $this->belongsTo(Inventaris::class, 'ram_id');
}


public function motherboard()
{
    return $this->belongsTo(Inventaris::class, 'motherboard_id');
}


public function pic()
{
    return $this->belongsTo(\App\Models\Pic::class, 'pic_id');
}


public function hostname()
{
    return $this->belongsTo(\App\Models\Hostname::class, 'hostname_id');
}

protected static function booted()
    {
        static::created(fn ($model) => $model->triggerRekap());
        static::updated(fn ($model) => $model->triggerRekap());
        static::deleted(fn ($model) => $model->triggerRekap());
        
    }
}

