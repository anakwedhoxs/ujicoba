<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class RekapArsip extends Model
{
    protected $fillable = [
        'nama_arsip',
        'keterangan',
    ];


    public function items()
    {
        return $this->hasMany(RekapArsipItem::class);
    }
}





