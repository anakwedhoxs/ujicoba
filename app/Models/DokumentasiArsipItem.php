<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumentasiArsipItem extends Model
{
    protected $fillable = [
        'dokumentasi_arsip_id',
        'nama_barang',
        'foto',
    ];

    public function arsip()
    {
        return $this->belongsTo(DokumentasiArsip::class);
    }
}
