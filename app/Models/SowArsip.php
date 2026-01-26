<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SowArsip extends Model
{
    protected $fillable = ['judul'];

    public function items()
    {
        return $this->hasMany(SowArsipItem::class, 'sow_arsip_id');
    }
}
