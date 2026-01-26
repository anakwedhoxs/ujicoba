<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumentasiArsip extends Model
{
    protected $fillable = ['judul'];

    public function items()
    {
        return $this->hasMany(DokumentasiArsipItem::class);
    }
}
