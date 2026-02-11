<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class RekapArsipItem extends Model
{
    protected $fillable = [
        'rekap_arsip_id',
        'kategori',
        'merk',
        'seri',
        'jumlah',
    ];


    protected $casts = [
        'jumlah' => 'integer',
    ];


    public function arsip()
    {
        return $this->belongsTo(RekapArsip::class);
    }
}





